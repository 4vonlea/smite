<?php

/**
 * Class Presence
 * @property Dashboard_m $Dashboard_m
 * @property Presence_m $Presence_m
 */
class Presence extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
		'save'=>'insert',
		'report'=>'view',
		'get_data'=>'view',
		'get_detail'=>'view',
	];
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

	public function report($id_event){
		$this->load->library("Exporter");
		$this->load->model(["Presence_m","Event_m"]);
		$event = $this->Event_m->findOne($id_event);
		$data =  $this->Presence_m->getReportData($id_event);
		$exporter = new Exporter();
		$exporter->setData($data);
		$exporter->setFilename("PRESENCE-$event->name");
		$exporter->setTitle("Report Presence Event : ".$event->name);
		$exporter->asExcel();
	}

	public function get_detail(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$id_event = $this->input->post('id');
		$data = [];$column = [];
		if($id_event) {
			$this->load->model("Presence_m");

			$data = $this->Presence_m->getReportData($id_event);
			if(count($data) > 0)
				$column = array_keys($data[0]);
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>"true",'data'=>$data,'column'=>$column]));
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
