<?php


class Notification  extends Admin_Controller
{
	public function index(){
		$this->load->model("Event_m");
		$this->load->helper('form_helper');
		$event = $this->Event_m->find()->get()->result_array();
		$event = Event_m::asList($event,'id','name');
		$this->layout->render("notification",[
			'event'=>$event,
		]);
	}

	public function send_message(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$data = $this->input->post();
		$this->load->model("Member_m");
		$to = [];
		if($data['target'] == "all"){
			$res = $this->Member_m->findAll();
			foreach($res as $row){
				$to['email'][] = $row->email;
				$to['wa'][] = $row->phone;
			}
		}else{
			$res = $this->Member_m->findOne($data['to']);
			$to['email'][] = $res->email;
			$to['wa'][] = $res->phone;
		}
		if(in_array("email",$data['via'])){
			$this->load->model("Gmail_api");
			foreach($to['email'] as $receiver) {
				$this->Gmail_api->sendMessage($receiver,$data['subject'],$data['text']);
			}
		}

		if(in_array("wa",$data['via'])){
			$this->load->model("Whatsapp_api");
			foreach($to['wa'] as $receiver) {
				$this->Whatsapp_api->sendMessage($receiver,$data['subject'],$data['text']);
			}
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>true]));
	}

}
