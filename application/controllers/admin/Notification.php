<?php


class Notification  extends Admin_Controller
{
	public function index(){
		$this->layout->render("notification");
	}

	public function message(){
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

	}
}
