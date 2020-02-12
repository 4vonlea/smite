<?php


class Member extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
		'card'=>'view',
		'add_status'=>'insert',
		'remove_status'=>'delete',
		'verification_status'=>'update',
		'verify'=>'update',
		'get_proof'=>'view',
		'send_certificate'=>'view',
		'register'=>'insert',
		'grid'=>'view',
		'save_profile'=>'update',
		'save_check'=>'update',
		'get_event'=>'view',
		'handlingImage'=>'insert',
		'delete'=>'delete',
	];

	public function index()
	{
		$this->load->model(['Category_member_m','Univ_m']);
		$this->load->helper("form");
		$statusList = $this->Category_member_m->find()->select('*')->get()->result_array();
		foreach($statusList as $i=>$r){
			$statusList[$i]['need_verify'] = (bool)$r['need_verify'];
		}
		$univList = $this->Univ_m->find()->order_by("univ_nama")->get();
		$univDl = Univ_m::asList($univList->result_array(),"univ_id","univ_nama");

		$this->layout->render('member', ['statusList' => $statusList,'univDl'=>$univDl]);
	}


	public function card($event_id,$member_id)
	{
		$this->load->model('Member_m');
		$member = $this->Member_m->findOne($member_id);
		try{
			$member->getCard($event_id)->stream($member->fullname."-nametag.pdf");
		}catch (ErrorException $ex){
			show_error($ex->getMessage());
		}
	}

	public function add_status()
	{
		$this->load->model('Category_member_m');
		$data = $this->input->post('value');
		$return = [];
		foreach ($data as $i => $row) {
			$model = null;
			if (isset($row['id']))
				$model = Category_member_m::findOne($row['id']);
			if ($model == null)
				$model = new Category_member_m();
			$model->setAttributes($row);
			$model->save();
			$return[] = $model->toArray();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));
	}

	public function remove_status()
	{
		$this->load->model('Category_member_m');
		$status = $this->Category_member_m->delete($this->input->post('id'));
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function verification_status()
	{
		$this->load->model('Category_member_m');
		$status = $this->Category_member_m->findOne($this->input->post('id'));
		$status->need_verify = $this->input->post('need_verify') == "true";
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status->save()]));
	}

	public function verify()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Member_m");
		$post = $this->input->post();
		$status = false;
		$message = '';
		if (isset($post['response'])) {
			$member = Member_m::findOne($post['id']);
			if ($post['response'] == 1)
				$member->verified_by_admin = 1;
			elseif ($post['response'] == 0) {
				$member->verified_by_admin = 1;
				$member->status = $post['status'];
			}
			$status = $member->save(false);
		} else {
			$message = "Response has not been set";
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function get_proof($id)
	{
		$this->load->model("Member_m");
		$extension = explode("|", Member_m::$proofExtension);
		foreach ($extension as $ext) {
			$filepath = APPPATH . "uploads/proof/$id.$ext";
			if (file_exists($filepath)) {
				header('Content-Description: File Transfer');
				header('Content-Type: ' . mime_content_type($filepath));
				header('Content-Disposition: attachment; filename="' . $id . '.' . $ext . '"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($filepath));
				flush(); // Flush system output buffer
				readfile($filepath);
				break;
			}
		}

	}

	public function send_certificate(){
		if($this->input->post()) {
			$this->load->model(["Gmail_api", "Event_m"]);
			$id = $this->input->post("id");
			$member = $this->input->post();
			if(file_exists(APPPATH."uploads/cert_template/$id.txt")) {
				$cert = $this->Event_m->exportCertificate($member, $id)->output();
				$status = $this->Gmail_api->sendMessageWithAttachment("muhammad.zaien17@gmail.com", "Certificate of Event", "Thank you for your participation <br/> Below is your certificate of '" . $member['event_name']."'", $cert, "CERTIFICATE.pdf");
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => true]));
			}else{
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false,'message'=>'Template Certificate is not found ! please set on Setting']));
			}
		}
	}

	public function register()
	{
		$this->load->model(["Category_member_m", "Event_m","Univ_m"]);
		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory', 'Please Select your status');
		if ($this->input->post()) {
			$this->load->model(['Member_m', 'User_account_m', 'Gmail_api', 'Transaction_m', 'Transaction_detail_m']);
			$this->load->library('Uuid');

			$data = $this->input->post();
			$id_invoice = $this->Transaction_m->generateInvoiceId();
			$data['id'] = Uuid::v4();
			$data['password'] = strtoupper(substr(uniqid(), -5));
			$data['confirm_password'] = $data['password'];

			$uploadStatus = true;
			if(isset($_FILES['proof']) && is_uploaded_file($_FILES['proof']['tmp_name'])) {
				$config['upload_path'] = APPPATH . 'uploads/proof/';
				$config['allowed_types'] = 'jpg|png|jpeg';
				$config['max_size'] = 2048;
				$config['overwrite'] = true;
				$config['file_name'] = $id_invoice;

				$this->load->library('upload', $config);
				if ($this->upload->do_upload('proof')) {
					$dataUpload = $this->upload->data();
					$data['payment_proof'] = $dataUpload['file_name'];
				} else {
					$uploadStatus = false;
				}
			}

			if ($this->Member_m->validate($data) && $uploadStatus){// && $this->handlingImage('image', $data['id'])) {
				$data['username_account'] = $data['email'];
				$data['verified_by_admin'] = 1;
				$data['verified_email'] = 1;
				$data['region'] = 0;
				$data['country'] = 0;
				$data['image'] = "";// $upl['file_name'];

				$token = uniqid();
				$this->Member_m->getDB()->trans_start();
				if($data['univ'] == Univ_m::UNIV_OTHER){
					$this->Univ_m->insert(['univ_nama'=>strtoupper($data['other_institution'])]);
					$data['univ'] = $this->Univ_m->last_insert_id;
				}

				$this->Member_m->insert(array_intersect_key($data, array_flip($this->Member_m->fillable)), false);
				$this->User_account_m->insert([
					'username' => $data['email'],
					'password' => password_hash($data['password'], PASSWORD_DEFAULT),
					'role' => 0,
					'token_reset' => "verifyemail_" . $token
				], false);
				$this->Transaction_m->insert([
					'id' => $id_invoice,
					'member_id' => $data['id'],
					'checkout' => 1,
					'message_payment' => $data['message_payment'],
					'channel' => $data['channel'],
					'status_payment' => Transaction_m::STATUS_FINISH,
					'payment_proof' => $data['payment_proof']
				]);
				$details = [];
				foreach ($data['transaction']['event'] as $tr) {
					$t = explode(",", $tr);
					$details[] = [
						'member_id' => $data['id'],
						'product_name' => $t[2],
						'transaction_id' => $id_invoice,
						'event_pricing_id' => $t[0],
						'price' => $t[1],
					];
				}
				$this->Transaction_detail_m->batchInsert($details);

				$this->Member_m->getDB()->trans_complete();
				$error['status'] = $this->Member_m->getDB()->trans_status();
				$error['message'] = $this->Member_m->getDB()->error();
				$error['url'] = base_url("admin/member/index?q=").$data['id'];
				$error['email'] = $data['email'];
				if ($error['status']) {
					$tr = $this->Transaction_m->findOne($id_invoice);
					$data['participantsCategory'] = $participantsCategory;
					$email_message = $this->load->view('template/success_register_onsite', $data, true);
					$attc = [
						$data['fullname'].'-invoice.pdf' => $tr->exportInvoice()->output(),
						$data['fullname'].'-bukti_registrasi.pdf' => $tr->exportPaymentProof()->output()
					];
					$details = $tr->detailsWithEvent();
					foreach($details as $row){
						if($row->event_name) {
							$event = ['name' => $row->event_name,
								'held_on' => $row->held_on,
								'held_in' => $row->held_in,
								'theme' => $row->theme
							];
							if(env('send_card_member','1') == '1') {
								try {
									$attc[$data['fullname'] . "_" . $row->event_name . ".pdf"] = $this->Member_m->getCard($event, $data)->output();
								}catch (ErrorException $ex){
								log_message("error",$ex->getMessage());
								}
							}
						}
					}
					$this->Gmail_api->sendMessageWithAttachment($data['email'], 'Registered On Site Succesfully - Invoice, Bukti Registrasi', $email_message, $attc);
				}
			} else {
				$error['status'] = false;
				$error['validation_error'] = array_merge($this->Member_m->getErrors(), ['proof' => ($uploadStatus == false ? $this->upload->display_errors("", "") : null)]);
//				$error['validation_error'] = $this->Member_m->getErrors();;
			}
			$this->output->set_content_type("application/json")
				->set_output(json_encode($error));

		} else {
			$this->load->model(["Category_member_m", "Event_m"]);
			$this->load->helper("form");
			$events = $this->Event_m->eventAvailableNow();
			$univList = $this->Univ_m->find()->order_by("univ_id,univ_nama")->get();
			$univDl = Univ_m::asList($univList->result_array(),"univ_id","univ_nama");
			$this->layout->render("register", [
				'participantsCategory' => $participantsCategory,
				'events' => $events,
				'univDl'=>$univDl
			]);
		}
	}

	public function grid()
	{
		$this->load->model('Member_m');

		$grid = $this->Member_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function save_profile(){
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model('Member_m');
		$data = $this->input->post();
		$status =  $this->Member_m->update([
			'fullname'=>$data['fullname'],
			'gender'=>$data['gender'],
			'phone'=>$data['phone'],
			'city'=>$data['city'],
			'address'=>$data['address'],
			'univ'=>$data['univ'],
			'sponsor'=>$data['sponsor'],
		],['id'=>$data['id']],false);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$status]));
	}

	public function save_check(){
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model('Transaction_detail_m');
		$data = $this->input->post('transaction');
		foreach($data as $row){
			$this->Transaction_detail_m->update(['checklist'=>json_encode($row['checklist'])],['id'=>$row['id']]);
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>true]));
	}

	public function get_event(){
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model('Event_m');

		$member_id = $this->input->post('id');
		$result = $this->Event_m->getParticipant()->where('m.id',$member_id)->get();
		$return = [];
		foreach($result->result_array() as $i=>$row){
			if($row['checklist'] != "")
				$row['checklist'] = json_decode($row['checklist'],true);
			else
				$row['checklist'] = ['nametag'=>false,'seminarkit'=>false,'taker'=>''];
			$return[$i] = $row;
		}

		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));

	}
	/**
	 * @param $name
	 * @return boolean
	 */
	protected function handlingImage($name, $filename)
	{

		$config['upload_path'] = 'themes/uploads/profile/';
		$config['allowed_types'] = 'jpg|png|pdf';
		$config['max_size'] = 2048;
		$config['overwrite'] = true;
		$config['file_name'] = $filename;
		$this->load->library('upload', $config);
		return $this->upload->do_upload($name);

	}

	public function delete()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model(["Member_m","Transaction_m","User_account_m"]);
		$post = $this->input->post();
		$check = $this->Transaction_m->find()->select("count(*) as c")->where(['member_id'=>$post['id']])
			->get()->row_array();
		$status = true;$message = "";
		if($check['c'] > 0){
			$status = false;
			$message = "Cannot delete this member, The Member already have a transaction !";
		}else{
			$this->Member_m->getDB()->trans_start();
			$this->Member_m->find()->where(['id'=>$post['id']])->delete();
			$this->Transaction_m->find()->where(['member_id'=>$post['id']])->delete();
			//$this->User_account_m->find()->where(['username'=>$post['email']])->delete();
			$this->Member_m->getDB()->trans_complete();
			$status = $this->Member_m->getDB()->trans_status();
			if($status == false)
				$message = "Failed to delete member, error on server !";

		}

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

}
