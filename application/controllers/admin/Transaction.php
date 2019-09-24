<?php


class Transaction extends Admin_Controller
{

    public function index(){
		$this->load->model("Transaction_m");
		$this->layout->render('transaction');
    }

    public function download($type,$id){
		$this->load->model(['Transaction_m','Member_m']);
		$tr = $this->Transaction_m->findOne(['id'=>$id]);
		$member = $this->Member_m->findOne(['id'=>$tr->member_id]);
		if($type == "invoice")
			$tr->exportInvoice()->stream($member->fullname."-Invoice.pdf");
		elseif($type == "proof")
			$tr->exportPaymentProof()->stream($member->fullname."-Payment_Proof.pdf");
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
				$member->fullname.'-payment_proof.pdf' => $detail->exportPaymentProof()->output()
			];
			$details = $detail->detailsWithEvent();
			foreach($details as $row){
				if($row->event_name) {
					$event = ['name' => $row->event_name,
						'held_on' => $row->held_on,
						'held_in' => $row->held_in,
						'theme' => $row->theme
					];
					$attc[$member->fullname."_".$row->event_name.".pdf"] = $member->getCard($event)->output();
				}
			}
			$this->Gmail_api->sendMessageWithAttachment($member->email, 'Invoice, Payment Proof And Participant Card', "Thank you for registering and fulfilling your payment, below is your invoice and offical payment proof", $attc);

		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$status]));

	}

}
