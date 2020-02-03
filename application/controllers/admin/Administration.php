<?php
/**
 * Class Administration
 * @property  Event_m $Event_m
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
		$this->load->model( "Event_m");

		$rs = $this->Event_m->getParticipant()->where("t.id",$event_id)->select("km.kategory as status_member")->get();
		$participant = $rs->result_array();
	}

	public function certificate($event_id, $member_id)
	{
		$this->load->model(["Member_m", "Event_m"]);
		$member = $this->Member_m->setAlias('t')->find()->join('kategory_members kt', 'kt.id = t.id ')
			->select('fullname,email,kt.kategory as status_member')->where("t.id", $member_id)->get()->row_array();
		if (file_exists(APPPATH . "uploads/cert_template/$event_id.txt")) {
			$this->Event_m->exportCertificate($member, $event_id)->stream("Certifate.pdf", array("Attachment" => false));
		} else {
			show_error("Template Certificate is not found ! please set on Setting");
		}
	}


	public function card($event_id, $member_id)
	{
		$this->load->model('Member_m');
		$member = $this->Member_m->findOne($member_id);
		$member->getCard($event_id)->stream($member->fullname . "-nametag.pdf", array("Attachment" => false));
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
