<?php

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Committee extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
		'save'=>'insert',
		'delete'=>'delete',
		'nametag'=>'view',
		'delete_attribute'=>'delete',
		'grid'=>'view',
	];

	public function index()
	{
		$this->load->model("Event_m");
		$events = $this->Event_m->find()->get()->result_array();
		$this->layout->render("committee", ['events' => $events]);
	}

	public function save()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model(['Committee_m', 'Committee_attributes_m']);

		$post = $this->input->post();

		$this->Committee_m->getDB()->trans_start();
		if (isset($post['id'])) {
			$id = $post['id'];
			$event = $this->Committee_m->findOne($post['id']);
		} else
			$event = new Committee_m();

		$event->name = $post['name'];
		$event->email = $post['email'];
		$event->no_contact = $post['no_contact'];
		$event->save();

		if (!isset($post['id']))
			$id = $event->getLastInsertID();

		if($this->input->post('attributes')){
			foreach ($post['attributes'] as $row) {
				if (isset($row['id'])) {
					$attr = $this->Committee_attributes_m->findOne($row['id']);
				} else
					$attr = new Committee_attributes_m();
				$attr->event_id = $row['event'];
				$attr->status = $row['status'];
				$attr->committee_id = $id;
				$attr->save();
			}
		}

		$this->Committee_m->getDB()->trans_complete();
		$status = $this->Committee_m->getDB()->trans_status();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status,'message'=>$message]));
	}

	public function delete()
	{

		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$message = "";
		$this->load->model(["Committee_m", "Committee_attributes_m"]);
		$post = $this->input->post();

		$this->Committee_m->getDB()->trans_start();
		$this->Committee_m->find()->where(['id' => $post['id']])->delete();
		$this->Committee_attributes_m->find()->where(['committee_id' => $post['id']])->delete();
		$this->Committee_m->getDB()->trans_complete();
		$status = $this->Committee_m->getDB()->trans_status();
		if ($status == false)
			$message = "Failed to delete member, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function nametag($id){
		$this->load->model('Committee_attributes_m');
		$com = $this->Committee_attributes_m->findOne($id);
		try {
			$com->getCard()->stream($com->committee->name . "-nametag.pdf", array("Attachment" => false));
		}catch (ErrorException $ex){
			show_error($ex->getMessage());
		}
	}

	public function send_certificate($id){
		$this->load->model(["Notification_m", "Committee_attributes_m"]);
		$com = $this->Committee_attributes_m->findOne($id);
		$commiteMember = $com->committee;
		if($commiteMember->email){
			$cert = $com->exportCertificate()->output();
			$status = $this->Notification_m->sendMessageWithAttachment($commiteMember->email, "Certificate of Event", "Thank you for your participation <br/> Below is your certificate of '" . $com->event->name."'", $cert, "CERTIFICATE.pdf");
			if($status['status']){
				$this->session->set_flashdata('message', 'Certificate sent successfully to '.$commiteMember->email);
			redirect(base_url("admin/committee"));
			}else{
				$this->session->set_flashdata('message', $status['message']);
			}
			redirect(base_url("admin/committee"));
		}else{
			show_error("Committee email is not yet set !",404);
		}

	}

	public function certificate($id){
		$this->load->model('Committee_attributes_m');
		$com = $this->Committee_attributes_m->findOne($id);
		try {
			$com->exportCertificate()->stream($com->committee->name . "-Certificate.pdf", array("Attachment" => false));
		}catch (ErrorException $ex){
			show_error($ex->getMessage());
		}
	}

	public function delete_attribute(){
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$post = $this->input->post();
		$this->load->model("Committee_attributes_m");
		$status = $this->Committee_attributes_m->delete(['id' => $post['id']]);
		$message = "";
		if ($status == false)
			$message = "Failed to delete member, error on server !";
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));

	}

	public function grid()
	{
		$this->load->model('Committee_m');

		$grid = $this->Committee_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function download_committee($tipe){
		$this->load->model("Committee_m");
    	$data =  $this->Committee_m->getDataCommittee();
    	$this->export($tipe,"Committee of Events",$data);
	}

	public function export($tipe = null,$title = null,$data = null){
    	if($this->input->post('tipe'))
    		$tipe = $this->input->post('tipe');
		if($this->input->post('data'))
			$data = $this->input->post('data');
		if($this->input->post('title'))
			$title = $this->input->post('title');

		$this->load->library('Exporter');
		$exporter = new Exporter();
		$exporter->setData($data);
		$exporter->setTitle($title);
		if($tipe == 'excel'){
			$exporter->asExcel();
		}elseif($tipe == "pdf"){
			$exporter->asPDF();
		}elseif($tipe == "csv"){
			$exporter->asCSV();
		}
	}

	public function search_member(){
		$this->load->model('Member_m');
		$data = $this->Member_m->setAlias("t")->find()
			->select("fullname as value,phone,email")
			->like('fullname',$this->input->post('cari'))
			->get()->result_array();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode([
				'inputPhrase'=>$this->input->post('cari'),
				'items'=>$data
			]));

	}

	public function download_template(){
		$this->load->model('Event_m');
		$data = $this->Event_m->findAll();

		$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader("Xlsx");
		$spreadsheet = $reader->load(APPPATH."/uploads/template_committe.xlsx");
		$sheet = $spreadsheet->getActiveSheet();

		$j = 2;
		foreach($data as $row){
			$sheet->setCellValueByColumnAndRow(9, $j, $row->id);
			$sheet->setCellValueByColumnAndRow(10, $j, $row->name);
			$j++;
		}

		$sheet->getStyleByColumnAndRow(9,2, 10, $j-1)->applyFromArray([
			'borders' => ['allBorders' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => ['argb' => '000']]
			]
		]);

		$writer = new Xlsx($spreadsheet);
		header('filename:template_committee.xlsx');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="template_committee.xlsx"');
		$writer->save("php://output");

	}

	public function import(){
		$config['upload_path'] = APPPATH."/uploads/tmp/";
		$config['allowed_types'] = 'xls|xlsx';
		$config['file_name'] = time();
		$this->load->library('upload', $config);
		$status = true;
		$message = "";
		if($this->upload->do_upload('import')){
			$uploadData =  $this->upload->data();
			$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(APPPATH."/uploads/tmp/".$uploadData['file_name']);
			$sheet = $spreadsheet->getActiveSheet();
			$data = [];
			$i = 2;
			while($sheet->getCellByColumnAndRow(1,$i)->getValue() != ""){
				$data[] = [
					'name'=>$sheet->getCellByColumnAndRow(1,$i)->getValue(),
					'email'=>$sheet->getCellByColumnAndRow(2,$i)->getValue(),
					'no_contact'=>$sheet->getCellByColumnAndRow(3,$i)->getValue(),
					'status'=>$sheet->getCellByColumnAndRow(4,$i)->getValue(),
					'event_id'=>$sheet->getCellByColumnAndRow(5,$i)->getValue(),
				];
				$i++;
			}
			$this->load->model(['Committee_m','Committee_attributes_m']);
			$success = 0;
			foreach($data as $row){
				$idCom = '';
				$statusPart = true;
				$com = $this->Committee_m->find()->where([
					'name'=>$row['name'],
					'email'=>$row['email'],
				])->get()->row();
				if($com){
					$idCom = $com->id;
				}else{
					$statusPart = $this->Committee_m->insert([
						'name'=>$row['name'],
						'email'=>$row['email'],
						'no_contact'=>$row['no_contact']
					]);
					$idCom = $this->Committee_m->getLastInsertID();
				}
				$dataAttr = [
					'committee_id'=>$idCom,
					'event_id'=>$row['event_id'],
					'status'=>$row['status']
				];
				$attribute = $this->Committee_attributes_m->find()->where($dataAttr)->get()->row();
				if(!$attribute){
					$statusPart = $this->Committee_attributes_m->insert($dataAttr) & $statusPart;
				}
				if($statusPart){
					$success++;
				}
			}
			$message = "$success dari ".count($data)." Berhasil diimport";
			unlink(APPPATH."/uploads/tmp/".$uploadData['file_name']);
		}else{
			$message =  $this->upload->display_errors("","");
		}
		$this->session->set_flashdata('import',$status.";".$message);
		redirect(base_url('admin/committee'));
		// $this->output
		// 	->set_content_type("application/json")
		// 	->_display(json_encode([
		// 		'status'=>$status,
		// 		'message'=>$message
		// 	]));
	}
}
