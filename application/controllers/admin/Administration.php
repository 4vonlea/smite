<?php
/**
 * Class Administration
 * @property  Event_m $Event_m
 * @property  Member_m $Member_m
 */

class Administration extends Admin_Controller
{
	public function index()
	{
		$this->load->model(['Event_m']);
		$this->layout->render("administration", [
			'event' => Event_m::asList(Event_m::findAll(), 'id', 'name')
		]);
	}

	public function download_all($type,$event_id){
		set_time_limit(0);
		ini_set("memory_limit","500M");
		$this->load->model( ["Event_m","Member_m"]);
		$this->load->helper("file");

		$rs = $this->Event_m->getParticipant()->where("t.id",$event_id)->select("m.id as m_id,km.kategory as status_member")->get();
		$participant = $rs->result_array();

		$timeCreate = time();
		$folderTemp = APPPATH . "uploads/tmp/$timeCreate";
		mkdir($folderTemp);
		try {
			foreach ($participant as $row) {
				$member = [
					'fullname' => $row['fullname'],
					'email' => $row['email'],
					'status_member' => $row['status_member'],
					'id' => $row['m_id'],
				];
				if ($type == 'certificate') {
					$dom = $this->Event_m->exportCertificate($member, $event_id);
				} elseif ($type == 'nametag') {
					$dom = $this->Member_m->getCard($event_id, $member);
				}
				file_put_contents($folderTemp . "/$row[id].pdf", $dom->output());
			}
		}catch (ErrorException $ex){
			show_error($ex->getMessage());
		}
		//Merge File
		$pdfFile = APPPATH . "uploads/tmp/" . $timeCreate . ".pdf";

		require(APPPATH . 'third_party/fpdf/fpdf.php');

		$pdf = new setasign\Fpdi\Fpdi();

		foreach (glob($folderTemp . "/*.pdf") as $file) {
			$size = $pdf->setSourceFile($file);
			for($i=1;$i<=$size;$i++) {
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
		header('Content-Description: File Transfer');
		header('Content-Type: ' . mime_content_type($pdfFile));
		header('Content-Disposition: attachment; filename="all_'.$type.'_'.$participant[0]['event_name'].'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($pdfFile));
		flush(); // Flush system output buffer
		readfile($pdfFile);
	}

	public function certificate($event_id, $member_id)
	{
		$this->load->model(["Member_m", "Event_m"]);
		$member = $this->Member_m->setAlias('t')->find()->join('kategory_members kt', 'kt.id = t.id ')
			->select('fullname,email,kt.kategory as status_member')->where("t.id", $member_id)->get()->row_array();
		if (file_exists(APPPATH . "uploads/cert_template/$event_id.txt")) {
			$this->Event_m->exportCertificate($member, $event_id)->stream("Certificate.pdf", array("Attachment" => false));
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
		$this->load->model("Event_m");
		$data = $this->Event_m->getParticipant()->where('t.id', $id)->get()->result_array();
		foreach ($data as $i => $row) {
			$data[$i]['editable'] = false;
			$data[$i]['saving'] = false;
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode(['status' => true, 'data' => $data]));
	}
}
