<?php


class Transaction extends Admin_Controller
{

    public function index(){
		$this->load->model("Transaction_m");
		$this->layout->render('transaction');
    }

    public function resend($type,$id){
		$this->load->model(['Transaction_m','Member_m','Gmail_api']);
		$tr = $this->Transaction_m->findOne(['id'=>$id]);
		$member = $this->Member_m->findOne(['id'=>$tr->member_id]);
		if($type == "invoice") {
			$filename = $member->fullname."-Invoice.pdf";
			$file = $tr->exportInvoice();
		}elseif($type == "proof") {
			$filename = $member->fullname."-Bukti_Registrasi.pdf";
			$file = $tr->exportPaymentProof();
		}
		$message = "<p>Dear Participant</p>
					<p>Thank you for participating in the event. Please download your 'Registration Proof' here.</p>
					<p>Best regards.<br/>
					Committee of ".Settings_m::getSetting('site_title')."</p>";
		$result = $this->Gmail_api->sendMessageWithAttachment($member->email, 'Kirim Ulang : Bukti Registrasi', $message,[$filename=>$file->output()]);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$result]));
	}

    public function download($type,$id){
		$this->load->model(['Transaction_m','Member_m']);
		$tr = $this->Transaction_m->findOne(['id'=>$id]);
		$member = $this->Member_m->findOne(['id'=>$tr->member_id]);
		if($type == "invoice")
			$tr->exportInvoice()->stream($member->fullname."-Invoice.pdf", array("Attachment" => false));
		elseif($type == "proof")
			$tr->exportPaymentProof()->stream($member->fullname."-Bukti_Registrasi.pdf", array("Attachment" => false));
		else
			show_404();
	}

	public function grid()
	{
		$this->load->model('Transaction_m');

		$grid = $this->Transaction_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function detail(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model("Transaction_m");
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		if($detail){
			$response = $detail->toArray();
			$response['member'] = $detail->member->toArray();
			foreach($detail->details as $row){
				$response['details'][] = $row->toArray();
			}
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));

	}

	public function expire(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model(["Transaction_m","Member_m","Gmail_api"]);
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		$detail->status_payment = Transaction_m::STATUS_EXPIRE;
		$status = $detail->save();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$status]));
	}

	public function verify(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Transaction_m","Member_m","Gmail_api"]);
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		$detail->status_payment = Transaction_m::STATUS_FINISH;
		$detail->message_payment = "Verified manual";
		$status = $detail->save();
		if($status){
			$member = $this->Member_m->findOne(['id'=>$detail->member_id]);
			$attc = [
				$member->fullname.'-invoice.pdf' => $detail->exportInvoice()->output(),
				$member->fullname.'-bukti_registrasi.pdf' => $detail->exportPaymentProof()->output()
			];
			$details = $detail->detailsWithEvent();
			foreach($details as $row){
				if($row->event_name) {
					$event = ['name' => $row->event_name,
						'held_on' => $row->held_on,
						'held_in' => $row->held_in,
						'theme' => $row->theme
					];
					if(env('send_card_member','1') == '1')
						$attc[$member->fullname."_".$row->event_name.".pdf"] = $member->getCard($event)->output();
				}
			}
			$this->Gmail_api->sendMessageWithAttachment($member->email, 'Invoice, Bukti Registrasi And Name Tag', "Thank you for registering and fulfilling your payment, below is your invoice and offical Bukti Registrasi", $attc);

		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$status]));

	}

	public function file($name)
	{
		$filepath = APPPATH . "uploads/proof/" . $name;
		if (file_exists($filepath)) {
			list(,$ext) = explode(".",$name);
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="Paper-' . $name . '.'.$ext.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		}
	}

}
