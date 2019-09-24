<?php

use Dompdf\Dompdf;

/**
 * Class Payment
 * @property Midtrans $midtrans
 */
class Payment extends MY_Controller
{
	public function __construct(){
		parent::__construct();
		if($this->user_session_expired() && $this->router->method != "notification")
			redirect(base_url("site/login"));

		$config = $this->config->item("midtrans");
		$params = array('server_key' =>$config['server_key'], 'production' => (ENVIRONMENT == "production"));
		$this->load->library(['midtrans','veritrans']);
		$this->midtrans->config($params);
		$this->veritrans->config($params);
	}

	public function notification(){
		$json_result = file_get_contents('php://input');
		$result = json_decode($json_result);
		if($result){
			$notif = $this->veritrans->status($result->order_id);
		}
		error_log(print_r($result,TRUE));
		if(isset($notif)) {
			$this->load->model("Transaction_m");
			$update['midtrans_data'] = json_encode($notif);
			$update['channel'] = $notif->payment_type;

			$transaction = $notif->transaction_status;
			$type = $notif->payment_type;
			$fraud = $notif->fraud_status;
			if ($transaction == 'capture') {
				// For credit card transaction, we need to check whether transaction is challenge by FDS or not
				if ($type == 'credit_card'){
					if($fraud == 'challenge'){
						$update['status_payment'] = "Challenge by FDS";
					}
					else {
						$update['status_payment'] = Transaction_m::STATUS_FINISH;
					}
				}
			}
			else if ($transaction == 'settlement'){
				$update['status_payment'] = Transaction_m::STATUS_FINISH;
			}
			else if($transaction == 'pending'){
				$update['status_payment'] = Transaction_m::STATUS_PENDING;
			}
			else if ($transaction == 'deny') {
				$update['status_payment'] = Transaction_m::STATUS_UNFINISH;
			}
			$update['checkout'] = 1;//$notif->status_message;
			$this->Transaction_m->update($update, $notif->order_id);
			if($update['status_payment'] == Transaction_m::STATUS_FINISH){
				$this->load->model("Gmail_api");
				$tr = $this->Transaction_m->findOne($notif->order_id);
				$member = $tr->member;

				$invoice = $tr->exportInvoice()->output();
				$this->Gmail_api->sendMessageWithAttachment($member->email,"INVOICE","Thank you for participating on events, Below is your invoice",$invoice,"INVOICE.pdf");

				$details = $tr->detailsWithEvent();
				$file = [];
				foreach($details as $row){
					if($row->event_name) {
						$event = ['name' => $row->event_name,
							'held_on' => $row->held_on,
							'held_in' => $row->held_in,
							'theme' => $row->theme
						];
						$file[$row->event_name.".pdf"] = $member->getCard($event)->output();
					}
				}
				$file['Payment Proof'] = $tr->exportPaymentProof()->output();
				$this->Gmail_api->sendMessageWithAttachment($member->email,"Official Payment Proof And Participant Card","Thank you for registering and fulfilling your payment, below is offical payment proof",$file,"OFFICIAL_PAYMENT_PROOF.pdf");

//				$file = $tr->exportPaymentProof()->output();
//				$this->Gmail_api->sendMessageWithAttachment($member->email,"Official Payment Proof-And Member Card","Thank you for registering and fulfilling your payment, below is offical payment proof",$file,"OFFICIAL_PAYMENT_PROOF.pdf");
			}
		}
	}

	public function after_checkout(){
		$data = $this->input->post();
		$this->load->model("Transaction_m");
		$checkout = 1;
		if($this->input->post('error')){
			$checkout = 0;
		}

		if(is_array($data['message_payment']))
			$message_payment = implode(",",$data['message_payment']);
		$this->Transaction_m->update(['checkout'=>$checkout,'message_payment'=>$message_payment,'created_at'=>date("Y-m-d H:i:s")],$data['id']);
	}

	public function checkout(){
		$this->load->model("Transaction_m");
		$user = $this->session->user_session;
		$transaction = $this->Transaction_m->findOne(['member_id'=>$user['id'],'checkout'=>0]);
		if($transaction) {
			$total_price = 0;
			$item_details = [];
			foreach ($transaction->details as $row) {
				$item_details[] = [
					'id' => $row->id,
					'price' => $row->price,
					'quantity' => 1,
					'name' => $row->product_name
				];
				$total_price += $row->price;
			}
			if($total_price == 0){
				$check = $this->Transaction_m->findOne(['member_id'=>$user['id'],'status_payment'=>Transaction_m::STATUS_FINISH]);
				if($check){
					$transaction->status_payment = Transaction_m::STATUS_FINISH;
					$transaction->channel = "FREE EVENT";
					$transaction->checkout = 1;
					$transaction->message_payment = "Participant follow a free event";
					$transaction->save();
					$this->output->set_content_type("application/json")
						->_display(json_encode(['status'=>true,'info'=>true,'message'=>'Thank you for your participation you have been added to follow a free event, No need payment !']));
					exit;
				}else{
					$this->output->set_content_type("application/json")
						->_display(json_encode(['status'=>false,'message'=>'You need to follow a paid event before follow a free (Rp 0,00) event !']));
					exit;
				}
			}
			if(count($item_details) == 0){
				$response['status'] = false;
				$response['message'] = "No Transaction available to checkout";
			}else {
				$transaction_details = array(
					'order_id' => $transaction->id,
					'gross_amount' => $total_price,
				);

				$fullname = explode(" ", trim($user['fullname']));
				$firstname = (isset($fullname[0]) ? $fullname[0] : "");
				$lastname = (isset($fullname[1]) ? $fullname[1] : "");
				$billing_address = array(
					'first_name' => $firstname,
					'last_name' => $lastname,
					'address' => $user['address'],
					'city' => $user['city'],
//			'postal_code'   => "",
					'phone' => $user['phone'],
//			'country_code'  => 'IDN'
				);

				$customer_details = array(
					'first_name' => $firstname,
					'last_name' => $lastname,
					'email' => $user['email'],
					'phone' => $user['phone'],
					'billing_address' => $billing_address,
				);

				$credit_card['secure'] = true;

				$time = time();
				$custom_expiry = array(
					'start_time' => date("Y-m-d H:i:s O", $time),
					'unit' => 'day',
					'duration' => 1
				);

				$transaction_data = array(
					'transaction_details' => $transaction_details,
					'item_details' => $item_details,
					'customer_details' => $customer_details,
					'credit_card' => $credit_card,
					'expiry' => $custom_expiry,
				);
				try {
					error_log(json_encode($transaction_data));
					$snapToken = $this->midtrans->getSnapToken($transaction_data);
					error_log($snapToken);
					$response['status'] = true;
					$response['token'] = $snapToken;
					$response['invoice'] = $transaction->id;
				} catch (Exception $ex) {
					$response['status'] = false;
					$response['message'] = $ex->getMessage();
				}
			}
		}else{
			$response['status'] = false;
			$response['message'] = "No Transaction available to checkout";
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}


}
