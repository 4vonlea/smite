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
		$this->load->helper("form");
		$report = $this->Dashboard_m->getData();
		$this->layout->render("presence",['report'=>$report]);
	}

	public function save(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Presence_m");
		$this->Presence_m->addPresenceNow($this->input->post('member_id'), $this->input->post('event_id'));
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

	public function self_registration(){
		$this->layout->render("self_registration");
	}

	public function get_event_transaction(){
		$id = $this->input->post("invoice_id");
		$this->load->model("Transaction_m");
		$data = $this->Transaction_m->getFollowedEvent($id);
		$status = count($data) > 0;
		$message = $status ? "Success in retrieving data" : "The transaction is not found or has not been settled";
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$status,'data'=>$data,'message'=>$message]));
	}

	public function card_and_presence($event_id, $member_id,$td_id)
	{
		$this->load->model(['Member_m','Presence_m','Transaction_detail_m']);
		$this->Presence_m->addPresenceNow($member_id,$event_id);
		$transaction = $this->Transaction_detail_m->findOne($td_id);
		if($transaction){
			$checklist = json_decode($transaction->checklist,true);
			$checklist['nametag'] = "true";
			$transaction->checklist = json_encode($checklist);
			$transaction->save();
		}
		$member = $this->Member_m->findOne($member_id);
		try {
			$member->getCard($event_id)->stream($member->fullname . "-nametag.pdf", array("Attachment" => false));
		}catch (ErrorException $ex){
			show_error($ex->getMessage());
		}
	}

}
