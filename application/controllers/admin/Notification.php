<?php


class Notification  extends Admin_Controller
{
	public function index(){
		$this->load->model("Event_m");
		$this->load->helper('form_helper');
		$event = $this->Event_m->find()->get()->result_array();
		$event = Event_m::asList($event,'id','name',"Select Event");
		$this->layout->render("notification",[
			'event'=>$event,
		]);
	}

	public function send_cert($preparing = null){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(['Event_m','Settings_m']);
		if($preparing){
			$id = $this->input->post("id");
			if(Settings_m::getSetting("config_cert_$id") != ""  && file_exists(APPPATH."uploads/cert_template/$id.txt")) {
				$result = $this->Event_m->getParticipant()->where('t.id', $id)->get();
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status'=>true,'data'=>$result->result_array()]));
			}else{
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status'=>false,'message'=>"Template of certificate is not found !"]));
			}
		}else{
			$this->load->model("Gmail_api");
			$member = $this->input->post();
			$event = [
				'id'=>$member['event_id'],
				'name'=>$member['event_name']
			];
			$cert = $this->Event_m->exportCertificate($member,$event['id'])->output();
			$status = $this->Gmail_api->sendMessageWithAttachment($member['email'],"Certificate of Event", "Thank you for your participation <br/> Below is your certiface of ".$event['name'], $cert, "CERTIFICATE.pdf");
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status'=>true,'log'=>$status]));
		}

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
