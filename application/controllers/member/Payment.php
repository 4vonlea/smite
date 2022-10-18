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
		if($this->user_session_expired() && in_array($this->router->method,['checkout','after_checkout']))
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
			}else if ($transaction == 'expired') {
				$update['status_payment'] = Transaction_m::STATUS_EXPIRE;
			}
			$update['checkout'] = 1;//$notif->status_message;
			$this->Transaction_m->update($update, $notif->order_id);
			if($update['status_payment'] == Transaction_m::STATUS_FINISH){
				$this->load->model("Notification_m");
				$tr = $this->Transaction_m->findOne($notif->order_id);
				$member = $tr->member;

				$invoice = $tr->exportInvoice()->output();
				$this->Notification_m->sendMessageWithAttachment($member->email,"INVOICE","Thank you for participating on events, Below is your invoice",$invoice,"INVOICE.pdf");

				$details = $tr->detailsWithEvent();
				$file = [];
				foreach($details as $row){
					if($row->event_name) {
						$event = ['name' => $row->event_name,
							'held_on' => $row->held_on,
							'held_in' => $row->held_in,
							'theme' => $row->theme
						];
						if(env('send_card_member','1') == '1') {
							try {
								$file[$row->event_name . ".pdf"] = $member->getCard($row->event_id)->output();
							}catch (ErrorException $ex){
								log_message("error",$ex->getMessage());
							}
						}
					}
				}
				$file['Bukti Registrasi'] = $tr->exportPaymentProof()->output();
				$this->Notification_m->sendMessageWithAttachment($member->email,"Official Payment Proof And Name Tag","Thank you for registering and fulfilling your payment, below is offical Bukti Registrasi",$file,"OFFICIAL_BUKTI_REGISTRASI.pdf");

//				$file = $tr->exportPaymentProof()->output();
//				$this->Notification_m->sendMessageWithAttachment($member->email,"Official Bukti Registrasi-And Name Tag","Thank you for registering and fulfilling your payment, below is offical Bukti Registrasi",$file,"OFFICIAL_PAYMENT_PROOF.pdf");
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
		if($this->config->item("use_midtrans")) {
			$transaction = $this->Transaction_m->findOne(['member_id' => $user['id'], 'checkout' => 0]);
			if ($transaction) {
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
				if ($total_price == 0) {
					$check = $this->Transaction_m->findOne(['member_id' => $user['id'], 'status_payment' => Transaction_m::STATUS_FINISH]);
					if ($check) {
						$transaction->status_payment = Transaction_m::STATUS_FINISH;
						$transaction->channel = "FREE EVENT";
						$transaction->checkout = 1;
						$transaction->message_payment = "Participant follow a free event";
						$transaction->save();
						$this->output->set_content_type("application/json")
							->_display(json_encode(['status' => true, 'info' => true, 'message' => 'Thank you for your participation you have been added to follow a free event, No need payment !']));
						exit;
					} else {
						$this->output->set_content_type("application/json")
							->_display(json_encode(['status' => false, 'message' => 'You need to follow a paid event before follow a free (Rp 0,00) event !']));
						exit;
					}
				}
				if (count($item_details) == 0) {
					$response['status'] = false;
					$response['message'] = "No Transaction available to checkout";
				} else {
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
						'phone' => $user['phone'],
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
						'duration' => 3
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
			} else {
				$response['status'] = false;
				$response['message'] = "No Transaction available to checkout";
			}
		}else{
			$manual_payment = json_decode(Settings_m::getSetting(Settings_m::MANUAL_PAYMENT),true);
			$transaction = $this->Transaction_m->findOne(['member_id' => $user['id'], 'checkout' => 0]);
			$transaction->checkout = 1;
			$transaction->status_payment = Transaction_m::STATUS_PENDING;
			$transaction->channel = "MANUAL TRANSFER";
			$transaction->save();
			$response['status'] = true;
			$response['manual'] = $manual_payment;
			$this->load->model(["Member_m","Notification_m"]);

			$member = $this->Member_m->findOne(['id'=>$transaction->member_id]);
			$attc = [
				$member->fullname.'-invoice.pdf' => $transaction->exportInvoice()->output(),
			];
			$this->Notification_m->sendMessageWithAttachment($member->email, 'Invoice', "Thank you for your participation, the following is an invoice for the event you participated in", $attc);
	
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}


	//Method Untuk Espay

	public function check_ip(){
		echo $this->request("https://git.ulm.ac.id/ip.php",[]);
	}

	public function inquiry_espay(){
		$description = "";
		$date = date("Y-m-d H:i:s");
		$amount = 0;

		$order_id = $this->input->post("order_id");
		$this->load->model(["Transaction_m","Notification_m","Member_m"]);
		$trx = $this->Transaction_m->findOne(['id'=>$order_id]);
		$espayConfig = Settings_m::getEspay();

		$response['rq_uuid'] = $this->input->post("rq_uuid");
		$response['rs_datetime'] = $date;
		$response['order_id'] = $this->input->post("order_id");
		$response['ccy'] = "IDR";
		if($trx){
			foreach($trx->detailsWithEvent() as $row){
				$amount += $row->price;
				$description .= $row->product_name."\n";
			}
			$data = $this->input->post();
			$data['amount_me'] = $amount;
			$this->Transaction_m->update(['checkout'=>1,'channel'=>'ESPAY','status_payment'=>Transaction_m::STATUS_PENDING],$order_id);
			$tr = $this->Transaction_m->findOne(['id'=>$order_id]);
			$member = $this->Member_m->findOne(['id'=>$tr->member_id]);

			$response['error_code'] = "0000";
			$response['error_message'] = "Success";
			$response['description'] = $description;
			$response['amount'] = $amount;
			$response['trx_date'] = $date;
			$response['installment_period'] = "30D";
			if($member){
				$result = run_job("job","send_unpaid_invoice",[
					$member->id,
					$order_id,
					30,
				]);
				// $response['customer_details'] = [
				// 	'firstname'=>$member->fullname,
				// 	'phone_number'=>$member->phone,
				// 	'email'=>$member->email,
				// ];
				// $attc = [
				// 	$member->fullname.'-invoice.pdf' => $tr->exportInvoice()->output(),
				// ];
				// $message = $this->load->view("template/email/send_unpaid_invoice",$member->toArray(),true);
				// $this->Notification_m->sendMessageWithAttachment($member->email, 'Unpaid Invoice (MA)', $message, $attc);
			}
		}else{
			$response['error_code'] = 1;
			$response['error_message'] = "Invalid Order ID";
		}
		$response['signature'] = $this->create_signature_espay($espayConfig['signature'],$order_id,"INQUIRY-RS",$date,$this->input->post("rq_uuid"),$response['error_code']);

		$this->log("inquiry",$response);
		$this->output->set_header("content-type: application/json")->set_output(json_encode($response));
//		echo implode(";",[$error_code,$error_message,$order_id,$amount,$ccy,$description,$date]);
	}

	public function log($type, $response = [],$invoice_id = "-"){
		$data = array_merge($this->input->post(),$this->input->get());
		$data['ip_address'] = $this->input->ip_address();
		$this->db->insert("log_payment",[
			'invoice'=>$data['order_id'] ?? $response['order_id'] ?? $data['invoice'] ?? $invoice_id ?? "-",
			'action'=>$type,
			'request'=>json_encode($data),
			'response'=>is_string($response) ? $response : json_encode($response),
		]);
		// $name = ($this->input->post("order_id") ? $this->input->post("order_id"):date("Ymdhis") );
		// file_put_contents(APPPATH."logs/".$name."_$type.json",json_encode($data));
	}

	public function notification_espay()
	{
		$this->load->model("Transaction_m");
		$success_flag = "0";
		$error_message = "Success";
		$order_id = $this->input->post("order_id");
		$reconcile_id = "SC$order_id";
		$reconcile_datetime = date("Y-m-d H:i:s");
		$message_payment = json_encode($this->input->post());
		if(in_array($this->input->ip_address(),["::1","127.0.0.1","139.255.109.146","116.90.162.173"])){
			$signature = $this->input->post("signature");
			if($this->check_signature($signature,$order_id,"PAYMENTREPORT",$this->input->post("rq_datetime"))){
				$statusPayment = $this->input->post("tx_status") == "S" ? Transaction_m::STATUS_FINISH : Transaction_m::STATUS_NEED_VERIFY;
				$this->Transaction_m->update(['message_payment'=>$message_payment,'status_payment'=>$statusPayment],$order_id);
				if($statusPayment == Transaction_m::STATUS_FINISH){
					$tr = $this->Transaction_m->findOne($order_id);
					$member = $tr->member;
					if($member){
						$this->load->model("Notification_m");
						$file['Registration Proof'] = $tr->exportPaymentProof()->output();
						$this->Notification_m->sendMessageWithAttachment($member->email,"Autosettlement Payment Proof","<p>Dear Participant.</p><p>Thank you for fulfilling your payment and we have automatically settle your transaction. We also have attached your Offical Registration Proof below. Please use it accordingly.</p><p>If you have any inquery, please contact the committee.</p><p>Thank you and we are happy to meet you in this event(s).</p><p>Registration Committee</p>",$file,"REGISTRATION_PROOF.pdf");
					}
				}
			}
		}
		$this->check_payment($order_id);
		$this->log("notif",[$success_flag,$error_message,$reconcile_id ,$order_id,$reconcile_datetime]);
		echo implode(",",[$success_flag,$error_message,$reconcile_id ,$order_id,$reconcile_datetime]);
	}

	public function check_payment($invoice = null){
		$orders = [];
		$this->load->model(["Transaction_m","Notification_m"]);
		if($invoice === null && $this->session->has_userdata("user_session")){
			$rs = $this->Transaction_m->find()->where("status_payment","pending")
				->where("member_id",$this->session->user_session['id'] ?? "")
				->where("channel","ESPAY")->get();
				foreach($rs->result_array() as $row){
					$orders[] = $row['id'];
				}
		}else if($invoice == null){
			$sql = "SELECT 
					DISTINCT t.id,t.status_payment,
					t.channel,
					CONVERT_TZ(NOW(),'SYSTEM','+08:00'), 
					STR_TO_DATE(JSON_EXTRACT(l.response,\"$.expired\"),'\"%Y-%m-%d %H:%i:%s\"') AS ex,
					JSON_EXTRACT(l.response,\"$.expired\") AS ex_string 
				FROM transaction t
				LEFT JOIN log_payment l  ON t.id = l.invoice AND l.`action` = \"check_status\"
				WHERE t.channel = \"ESPAY\" AND ((t.status_payment = \"pending\" AND (l.id IS NULL OR CONVERT_TZ(NOW(),'SYSTEM','+08:00') >= STR_TO_DATE(JSON_EXTRACT(l.response,\"$.expired\"),'\"%Y-%m-%d %H:%i:%s\"'))) OR t.status_payment = 'need_verification')";
			$rs = $this->db->query($sql);
			foreach($rs->result_array() as $row){
				$orders[] = $row['id'];
			}
		}else{
			$orders[] = urldecode($invoice);
		}

		$espayConfig = Settings_m::getEspay();

		foreach($orders as $order_id){
			$rs_date =  date("Y-m-d H:i:s");
			$signature = $this->create_signature_espay($espayConfig['signature'],$order_id,"CHECKSTATUS",$rs_date);
			$response = $this->request($espayConfig['apiLink']."status",[
				'uuid'=>md5($rs_date),
				'rq_datetime'=>$rs_date,
				'comm_code'=>$espayConfig['merchantCode'],
				'signature'=>$signature,
				'order_id'=>$order_id
			]);
			$this->log("check_status",$response,$order_id);
			$resJson = json_decode($response,true);
			$tr = $this->Transaction_m->findOne(['id'=>$order_id]);
			if($tr){
				$status = $tr->status_payment;
				if(isset($resJson['tx_status'])){
					if($resJson['tx_status'] == "S"){
						$status = Transaction_m::STATUS_FINISH;
					}elseif($resJson['tx_status'] == "F" || $resJson['tx_status'] == "EX"){
						$status = Transaction_m::STATUS_EXPIRE;
					}else{
						if(strtolower($resJson['tx_reason']) == strtolower('expired'))
							$status = Transaction_m::STATUS_EXPIRE;
						else
							$status = Transaction_m::STATUS_PENDING;
					}
					if($tr->status_payment == Transaction_m::STATUS_FINISH){
						$status =  Transaction_m::STATUS_FINISH;
					}
					$this->Transaction_m->update(['midtrans_data'=>$response,'status_payment'=>$status],$order_id);
					if($status == Transaction_m::STATUS_EXPIRE && $tr->status_payment != Transaction_m::STATUS_EXPIRE){
						$member = $tr->member;
						if($member){
							$message = $this->load->view("template/email/expired_transaction",['nama'=>$member->fullname],true);
							$this->Notification_m->sendMessage($member->email, 'Transaction Expired : '.$order_id, $message);
						}
					}
				}
				// file_put_contents(APPPATH."logs/".$order_id."_status.json",$response);
			}
		}
	}

	protected function create_signature_espay($keySignature,$order_id,$service_name,$rq_datetime ,$rq_uuid = "",$error_code = "",$amount =""){
		$sign = "";
		$espayConfig = Settings_m::getEspay();
		$comm_code = $espayConfig['merchantCode'];
		if($service_name == "INQUIRY-RS"){
			$sign = "##$keySignature##$rq_uuid##$rq_datetime##$order_id##$error_code##$service_name##";
		}else if($service_name == "SENDINVOICE"){
			$sign = "##$keySignature##$rq_uuid##$rq_datetime##$order_id##$amount##IDR##$comm_code##$service_name##";
		}else {
			$sign = "##$keySignature##$rq_datetime##$order_id##$service_name##";
		}
		$sign = strtoupper($sign);
		return hash("sha256",$sign);
	}

	protected function check_signature($signatureToValidate,$order_id,$service_name,$rq_datetime){
		$espayConfig = Settings_m::getEspay();
		$keySignature = $espayConfig['signature'];
		$sign = "##$keySignature##$rq_datetime##$order_id##$service_name##";
		$sign = strtoupper($sign);
		return $signatureToValidate == hash("sha256",$sign);
	}

	public function settlement_espay()
	{
		$this->log("settlement");
	}

	public function merchant_info_espay(){
		$espayConfig = Settings_m::getEspay();
		$response = $this->request($espayConfig['apiLink']."merchantinfo",[
			'key'=>$espayConfig['apiKey'],
		]);
		$this->output->set_header("content-type: application/json")->set_output($response);
	}

	public function send_invoice_espay(){
		$id_invoice = $this->input->post("id_invoice");
		$bankCode = $this->input->post("bank_code");
		$espayConfig = Settings_m::getEspay();
		$rs_date =  date("Y-m-d H:i:s");
		$uuid = md5($rs_date);
		$this->load->model(["Transaction_m","Notification_m"]);

		$tr = $this->Transaction_m->findOne(['id' => $id_invoice]);
		if(strpos($tr->member_id,"REGISTER-GROUP") !== false){
			$email = $tr->email_group;
			$name = str_replace("REGISTER-GROUP :","",$tr->member_id);
			$noHp = "";
		}else{
			$member = $tr->member;
			$email = $member->email;
			$name = $member->fullname;
			$noHp = $member->phone;
		}
		$total = 0;
		foreach($tr->details as $row){
			$total += $row->price;
		}
		$signature = $this->create_signature_espay($espayConfig['signature'],$id_invoice,"SENDINVOICE",$rs_date,$uuid,"",$total);
		$link = rtrim($espayConfig['apiLink'],"/")."pg/";
		$response = $this->request($link."sendinvoice",[
			'rq_uuid'=>$uuid,
			'rq_datetime'=>$rs_date,
			'order_id'=>$id_invoice,
			'ccy'=>'IDR',
			'comm_code'=>$espayConfig['merchantCode'],
			'remark1'=>$noHp,
			'remark2'=>$name,
			'remark3'=>$email,
			'update'=>'N',
			'bank_code'=>$bankCode,
			'va_expired'=>60 * 24 *5,//Dalam Menit
			'amount'=>$total,
			'signature'=>$signature,
		]);
		echo $response;
	}

	public function set_expired($id_invoice,$code){
		if($code == 'Smite2022'){
			$espayConfig = Settings_m::getEspay();
			$rs_date =  date("Y-m-d H:i:s");
			$uuid = md5($rs_date);
			$signature = $this->create_signature_espay($espayConfig['signature'],$id_invoice,'EXPIRETRANSACTION',$rs_date,$uuid,"","");
			$response = $this->request($espayConfig['apiLink']."updateexpire",[
				'uuid'=>$uuid,
				'rq_datetime'=>$rs_date,
				'comm_code'=>$espayConfig['merchantCode'],
				'order_id'=>$id_invoice,
				'tx_remark'=>'EXPIRED',
				'signature'=>$signature
			]);
			var_dump($response);
		}
	}

	protected function request($url,$params){
		$curl = curl_init();
			curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $params,
		));
		$response = curl_exec($curl);
		curl_close($curl);
		return $response;
	}
}
