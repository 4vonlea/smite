<?php


class Transaction extends Admin_Controller
{
	protected $accessRule = [
		'index' => 'view',
		'resend' => 'insert',
		'download' => 'view',
		'grid' => 'view',
		'detail' => 'view',
		'expire' => 'update',
		'verify' => 'update',
		'file' => 'view',
	];

	public function index()
	{
		$this->load->model("Transaction_m");
		$this->layout->render('transaction');
	}

	public function resend($type, $id)
	{
		$this->load->model(['Transaction_m', 'Member_m', 'Notification_m']);
		$tr = $this->Transaction_m->findOne(['id' => $id]);
		$member = $this->Member_m->findOne(['id' => $tr->member_id]) ?? (object) ['fullname'=>str_replace("REGISTER-GROUP : ","",$tr->member_id),'email'=>$tr->email_group];
		if ($type == "invoice") {
			$result[] = $this->Notification_m->sendPaymentProof($member,$tr);
			$this->Notification_m->setType(Notification_m::TYPE_WA);
			$result[] = $this->Notification_m->sendInvoice($member,$tr);
		} elseif ($type == "proof") {
			$result[] = $this->Notification_m->sendPaymentProof($member,$tr);
			$this->Notification_m->setType(Notification_m::TYPE_WA);
			$result[] = $this->Notification_m->sendPaymentProof($member,$tr);
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $result]));
	}

	public function download($type, $id)
	{
		$this->load->model(['Transaction_m', 'Member_m']);
		$tr = $this->Transaction_m->findOne(['id' => $id]);
		$member = $this->Member_m->findOne(['id' => $tr->member_id]);
		$filename = ($member ? $member->fullname : $tr->member_id);
		if ($type == "invoice")
			$tr->exportInvoice()->stream($filename . "-Invoice.pdf", array("Attachment" => false));
		elseif ($type == "proof")
			$tr->exportPaymentProof()->stream($filename . "-Registration_proof.pdf", array("Attachment" => false));
		else
			show_404();
	}

	public function grid()
	{
		$this->load->model('Transaction_m');

		$onlyHotel = $this->input->get("onlyHotel") == "1";

		$gridConfig = $this->Transaction_m->gridConfig();
		if($onlyHotel){
			$gridConfig['filter'] = ['is_booking_hotel >'=>'0'];
		}

		$grid = $this->Transaction_m->gridData($this->input->get(),$gridConfig);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function update_detail()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model("Transaction_detail_m");
		$id = $this->input->post('id');
		$nominal = $this->input->post('price');
		$dt = $this->Transaction_detail_m->findOne($id);
		$dt->price = $nominal;
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $dt->save()]));
	}

	public function save_modify()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Transaction_m", "Transaction_detail_m"]);

		$data = $this->input->post();
		$trans = $this->Transaction_m->findOne(['id' => $data['id']]);
		$status = false;
		if ($trans) {
			$this->db->insert("log_payment",[
				'invoice'=>$data['id'],
				'action'=>"save_modify",
				'request'=>json_encode(array_intersect_key($data,array_flip(['id','status_payment','checkout','client_message']))),
				'response'=>"[]",
			]);
			$trans->note = $data['note'];
			$trans->status_payment = $data['status_payment'];
			if ($trans->status_payment != Transaction_m::STATUS_WAITING) {
				$trans->checkout = 1;
			} else {
				$trans->checkout = 0;
			}
			$trans->getDb()->trans_start();
			$trans->save();
			if (isset($data['details'])) {
				foreach ($data['details'] as $ind=>$dt) {
					if (isset($dt['id'])) {
						$detail = $this->Transaction_detail_m->findOne(['id' => $dt['id']]);
                      	if ($dt['isDeleted'] == 1) {
                          if($detail)
							 $detail->delete();
						} else {
							unset($dt['isDeleted']);
							$detail->setAttributes($dt);
							$detail->save();
						}
					} else {
						unset($dt['isDeleted']);
						$this->Transaction_detail_m->insert($dt);
					}
				}
			}
			$trans->getDb()->trans_complete();
			$status = $trans->getDb()->trans_status();
		}
		if ($status) {
			$this->detail();
		} else {
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => false, 'message' => 'Failed to save !']));
		}
	}

	public function detail()
	{

		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Transaction_m","Event_discount_m"]);
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		if ($detail) {
			$response['model'] = $detail->toArray();
			$response['model']['member'] = $detail->member ? $detail->member->toArray() : [];
			$response['model']['member']['city_name'] = $detail->member && $detail->member->city_name ? $detail->member->city_name->nama : [];
			$group = $detail->member ? false : true;
			$tempDetails = [];
			foreach ($detail->details as $row) {
				$temp =  $row->toArray();
				$member = in_array($temp['event_pricing_id'],['0','-2']) ? [] : $row->member->toArray();
				$temp['isDeleted'] = 0;
				$tempDetails[] = array_merge(['member' => ['fullname'=>$member['fullname'] ?? "",'email'=>$member['email'] ?? "", ]], $temp);
			}
			usort($tempDetails,function($a,$b){
				return $b['product_name'] < $a['product_name'];
			});
			$response['model']['details'] = $tempDetails;
		}
		$current = count($response['model']['details']) > 0 ? current($response['model']['details']) : ['member_id'=>'-'];
		$member_id = $group ? $current['member_id'] : $response['model']['member']['id'];
		$response['listEvent'] = array_merge($this->Transaction_m->getNotFollowedEvent($member_id),$this->Event_discount_m->getLikeEvent());

		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function expire()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model(["Transaction_m", "Member_m", "Notification_m"]);
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		$detail->status_payment = Transaction_m::STATUS_EXPIRE;
		$status = $detail->save();
		$member = $detail->member;
		if($member){
			$this->Notification_m->sendExpiredTransaction($member, $id);
			$status = $this->Notification_m->setType(Notification_m::TYPE_WA)
								 ->sendExpiredTransaction($member, $id);
        }
		$this->db->insert("log_payment",[
			'invoice'=>$id,
			'action'=>"set_expire",
			'request'=>json_encode(array_intersect_key($this->input->post(),array_flip(['id','status_payment','checkout','client_message']))),
			'response'=>"[]",
		]);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function verify()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Transaction_m", "Member_m", "Notification_m"]);
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		$detail->status_payment = Transaction_m::STATUS_FINISH;
		$detail->message_payment = "Verified manual";
		$detail->checkout = 1;
		$status = $detail->save();
		if ($status) {
			$member = $this->Member_m->findOne(['id' => $detail->member_id]);
			$this->db->insert("log_payment",[
				'invoice'=>$id,
				'action'=>"verify",
				'request'=>json_encode(array_intersect_key($this->input->post(),array_flip(['id','status_payment','checkout','client_message']))),
				'response'=>"[]",
			]);
			if($member){
				$this->Notification_m->sendPaymentProof($member,$detail);
				$this->Notification_m->setType(Notification_m::TYPE_WA)->sendPaymentProof($member,$detail);
			}
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function file($name)
	{
		$filepath = APPPATH . "uploads/proof/" . $name;
		if (file_exists($filepath)) {
			list(, $ext) = explode(".", $name);
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="Paper-' . $name . '.' . $ext . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		}
	}

	public function gl(){
		$this->load->helper("form");
		$this->load->model("Transaction_m");
		$this->layout->set_breadcrumb("Transaction Guarantee Letter");
		$this->layout->render('transaction_gl');

	}

	public function save_gl(){
		$id_invoice = $this->input->post("id");
		$data = $this->input->post('midtrans_data');
		$uploadStatus = true;
		$uploadStatusReceipt = true;
		$this->load->library("form_validation");
		$this->form_validation->set_rules('midtrans_data[sponsorName]', 'Sponsor', 'required');
		$this->form_validation->set_rules('midtrans_data[payPlanDate]', 'Payment Plan Date', 'required');
		$response = [
			'status'=>true,
			'validation_error'=>null,
		];
		$errorUpload = [];
		if (isset($_FILES['fileName']) && is_uploaded_file($_FILES['fileName']['tmp_name'])) {
			if(file_exists(APPPATH . 'uploads/guarantee_letter/') == false){
				mkdir(APPPATH . 'uploads/guarantee_letter/');
			}

			$config['upload_path'] = APPPATH . 'uploads/guarantee_letter/';
			$config['allowed_types'] = 'jpg|png|jpeg|pdf';
			$config['max_size'] = 2048;
			$config['overwrite'] = true;
			$config['file_name'] = $id_invoice;

			$this->load->library('upload', $config);
			if ($this->upload->do_upload('fileName')) {
				$dataUpload = $this->upload->data();
				$data['fileName'] = $dataUpload['file_name'];
			} else {
				$uploadStatus = false;
				$errorUpload = $this->upload->display_errors("", "");
			}
		}
		$errorReceipt = [];
		if (isset($_FILES['receiptPayment']) && is_uploaded_file($_FILES['receiptPayment']['tmp_name'])) {
			if(file_exists(APPPATH . 'uploads/guarantee_letter/') == false){
				mkdir(APPPATH . 'uploads/guarantee_letter/');
			}
			$config['upload_path'] = APPPATH . 'uploads/guarantee_letter/';
			$config['allowed_types'] = 'jpg|png|jpeg|pdf';
			$config['max_size'] = 2048;
			$config['overwrite'] = true;
			$config['file_name'] = "RP-".$id_invoice;
			if(isset($this->upload)){
				$this->upload->initialize($config);
			}else{
				$this->load->library('upload', $config);
			}
			if ($this->upload->do_upload('receiptPayment')) {
				$dataUpload = $this->upload->data();
				$data['receiptPayment'] = $dataUpload['file_name'];
			} else {
				$uploadStatusReceipt = false;
				$errorReceipt = $this->upload->display_errors("", "");
			}
		}
		if($uploadStatus && $uploadStatusReceipt && $this->form_validation->run()){
			$this->load->model("Transaction_m");
			$response['status'] = $this->Transaction_m->update([
				'channel'=>$this->input->post('channel') ?? Transaction_m::CHANNEL_GL,
				'message_payment'=>$this->input->post('message_payment') ?? "-",
				'midtrans_data'=>json_encode($data)
			],$id_invoice);
		}else{
			$response['status'] = false;
			$response['validation_error'] = array_merge(
				$this->form_validation->error_array(), 
				['fileName' => ($uploadStatus == false ? $errorUpload : null)],
				['receiptPayment' => ($uploadStatusReceipt == false ? $errorReceipt : null)],
			);
		}
		$this->output->set_content_type("application/json")
				->set_output(json_encode($response));
	}

	public function file_gl($name,$is_receipt = false)
	{
		$filepath = APPPATH . "uploads/guarantee_letter/" . $name;
		if (file_exists($filepath)) {
			$filename = $is_receipt ? "Receipt Payment-".$name:"Guarantee Letter-".$name;
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			// header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		} else {
			show_404('File not found on server !');
		}
	}


	public function set_status_gl(){

	}

	public function grid_gl()
	{
		$this->load->model('Transaction_m');

		$grid = $this->Transaction_m->gridDataGl($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}
}
