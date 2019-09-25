<?php


class Administration extends Admin_Controller
{
	public function index()
	{
		$this->load->model(['Event_m']);
		$this->layout->render("administration",[
			'event'=>Event_m::asList(Event_m::findAll(),'id','name')
		]);
	}

	public function get_participant(){
		if($this->input->method() != "post")
			show_404();
		$id = $this->input->post("id");
		$this->load->model("Event_m");
		$data = $this->Event_m->getParticipant()->where('t.id',$id)->get()->result_array();
		$this->output->set_content_type("application/json")
			->_display(json_encode(['status' => true, 'data' => $data]));
	}
}
