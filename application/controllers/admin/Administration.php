<?php
/**
 * Class Administration
 * @property  Event_m $Event_m
 * @property  Member_m $Member_m
 */

class Administration extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
		'download_all'=>'view',
		'certificate'=>'view',
		'card'=>'view',
		'get_participant'=>'view',
	];

	public function index()
	{
		$this->load->model(['Event_m','Category_member_m','Notification_m']);
		$this->layout->render("administration", [
			'event' => Event_m::asList(Event_m::findAll(), 'id', 'name'),
			'statusMember' => Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory')
		]);
	}

	public function download_all($file = null){
		set_time_limit(0);
		ini_set("memory_limit","1024M");
		if($file == null) {
			$this->load->model(["Event_m", "Member_m"]);
			$this->load->helper("file");
			$action = $this->input->post("action");
			$type = $this->input->post("type");
			$event_id = $this->input->post("event_id");
			switch ($action) {
				case "retrieval":
					$rs = $this->Event_m->getParticipant("m.id as m_id,t.id as event_id")->where("t.id", $event_id)->get();
					$participant = $rs->result_array();

					$timeCreate = time();
					$folderTemp = APPPATH . "uploads/tmp/$timeCreate";
                	if(!file_exists(APPPATH."uploads/tmp/"))
                       mkdir(APPPATH."uploads/tmp/");
					mkdir($folderTemp);
					$this->output->set_content_type("application/json")
						->_display(json_encode(['status' => true, 'data' => $participant, 'timeCreate' => $timeCreate]));
					break;
				case "processing":
					try {
						$participant = $this->input->post("participant");
						$timeCreate = $this->input->post("timeCreate");
						$folderTemp = APPPATH . "uploads/tmp/$timeCreate";
						foreach ($participant as $row) {
							$member = $this->Event_m->getParticipant()->where("m.id", $row['m_id'])->where("t.id", $row['event_id'])->get()->row_array();
							if ($type == 'certificate') {
								$member['status_member'] = "Peserta";
								$dom = $this->Event_m->exportCertificate($member, $event_id);
							} elseif ($type == 'nametag') {
								$dom = $this->Member_m->getCard($event_id, $member);
							}
							file_put_contents($folderTemp . "/$row[m_id].pdf", $dom->output());
						}
						$this->output->set_content_type("application/json")
							->_display(json_encode(['status' => true,'processed'=>count($participant)]));
					} catch (ErrorException $ex) {
						$this->output->set_content_type("application/json")
							->_display(json_encode(['status' => false, 'message' => $ex->getMessage()]));
					}
					break;
				case "compilation";
					$timeCreate = $this->input->post("timeCreate");
					$folderTemp = APPPATH . "uploads/tmp/$timeCreate";
					$pdfFile = APPPATH . "uploads/tmp/" . $timeCreate . ".pdf";

					require(APPPATH . 'third_party/fpdf/fpdf.php');
					$pdf = new setasign\Fpdi\Fpdi();

					foreach (glob($folderTemp . "/*.pdf") as $file) {
						$size = $pdf->setSourceFile($file);
						for ($i = 1; $i <= $size; $i++) {
							$tpl = $pdf->importPage($i);
							$orientation = $pdf->getTemplateSize($tpl);
							if ($orientation['width'] > $orientation['height']) {
								$pdf->AddPage('L', array($orientation['width'], $orientation['height']));
							} else {
								$pdf->AddPage('P', array($orientation['width'], $orientation['height']));
							}
							$pdf->useTemplate($tpl);
						}
					}
					$pdf->Output('F', $pdfFile);
					delete_files($folderTemp, true);
					rmdir($folderTemp);
					$this->output->set_content_type("application/json")
						->_display(json_encode(['status' => true,'url'=>base_url('admin/administration/download_all/'.$timeCreate)]));
					break;
			}
		}else{
			$pdfFile = APPPATH . "uploads/tmp/" . $file . ".pdf";
			if(file_exists($pdfFile)){
				header('Content-Description: File Transfer');
				header('Content-Type: ' . mime_content_type($pdfFile));
				header('Content-Disposition: inline; filename="'.$file.'.pdf"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($pdfFile));
				flush(); // Flush system output buffer
				readfile($pdfFile);
			}
		}
	}

	public function certificate($transactionDetailId)
	{
		$this->load->model(["Member_m", "Event_m"]);
		$member = $this->Event_m->getParticipant()->where("td.id", $transactionDetailId)->get()->row_array();
		if (file_exists(APPPATH . "uploads/cert_template/$member[event_id].txt")) {
			$this->Event_m->exportCertificate($member, $member['event_id'])->stream("Certificate.pdf", array("Attachment" => false));
		} else {
			show_error("Template Certificate is not found ! please set on Setting");
		}
	}


	public function card($event_id, $member_id)
	{
		$this->load->model('Member_m');
		$member = $this->Member_m->findOne($member_id);
		try {
			$member->getCard($event_id)->stream($member->fullname . "-nametag.pdf", array("Attachment" => false));
		}catch (ErrorException $ex){
			show_error($ex->getMessage());
		}
	}

	public function get_participant()
	{
		if ($this->input->method() != "post")
			show_404();
		$id = $this->input->post("id");
		$event_id = $this->input->post("event_id");
		$member_id = $this->input->post("member_id");
		$transaction = $this->input->post("transaction_id");

		$this->load->model("Event_m");
		$select = "m.id as m_id,td.id as td_id, td.checklist as checklist,t.id as event_id,t.name as event_name,t.kategory as event_kategory,
		t.held_on as event_held_on,t.held_in as event_held_in,t.theme as event_theme,m.*,km.kategory as member_status,m.alternatif_status,
		m.alternatif_status2,td.updated_at as td_updated_at";
		$builder = $this->Event_m->getParticipant($select);

		if(isset($id))
			$builder->where('t.id', $id);
		if(isset($event_id))
			$builder->where('t.id', $event_id);
		if(isset($member_id))
			$builder->where('m.id', $member_id);
		if(isset($transaction))
			$builder->where('tr.id', $transaction);

		$data = $builder->get()->result_array();
		foreach ($data as $i => $row) {
			$data[$i]['editable'] = false;
			$data[$i]['saving'] = false;
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode(['status' => true, 'data' => $data]));
	}
}
