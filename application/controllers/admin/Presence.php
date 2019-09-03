<?php

/**
 * Class Presence
 * @property Dashboard_m $Dashboard_m
 * @property Presence_m $Presence_m
 */
class Presence extends Admin_Controller
{
	public function index(){
		$this->load->model("Dashboard_m");
		$report = $this->Dashboard_m->getData();
		$this->layout->render("presence",['report'=>$report]);
	}

	public function save(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Presence_m");
		$this->Presence_m->insert($this->input->post());
	}

	public function get_data(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Presence_m");

		$event_id = $this->input->post('id');
		$data  = $this->Presence_m->getDataToday($event_id);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>"true",'data'=>$data]));
	}
}
