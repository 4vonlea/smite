<?php


class Member extends Admin_Controller
{
	public function index()
	{
		$this->load->model('Category_member_m');
		$statusList = $this->Category_member_m->find()->select('*')->get()->result_array();
		foreach($statusList as $i=>$r){
			$statusList[$i]['need_verify'] = (bool)$r['need_verify'];
		}
		$this->layout->render('member', ['statusList' => $statusList]);
	}


	public function card($event_id,$member_id)
	{
		$this->load->model('Member_m');
		$member = $this->Member_m->findOne($member_id);
		$member->getCard($event_id)->stream($member->fullname."-member_card.pdf");
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
			$status = $member->save();
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

	public function register()
	{
		$this->load->model(["Category_member_m", "Event_m"]);
		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory', 'Please Select your status');
		if ($this->input->post()) {
			$this->load->model(['Member_m', 'User_account_m', 'Gmail_api', 'Transaction_m', 'Transaction_detail_m']);
			$this->load->library('Uuid');

			$data = $this->input->post();
			$data['id'] = Uuid::v4();
			$data['password'] = strtoupper(substr(uniqid(), -5));
			$data['confirm_password'] = $data['password'];
			if ($this->Member_m->validate($data)){// && $this->handlingImage('image', $data['id'])) {
//				$upl = $this->upload->data();

				$data['username_account'] = $data['email'];
				$data['verified_by_admin'] = 1;
				$data['verified_email'] = 1;
				$data['region'] = 0;
				$data['country'] = 0;
				$data['image'] = "";// $upl['file_name'];

				$token = uniqid();
				$this->Member_m->getDB()->trans_start();

				$this->Member_m->insert(array_intersect_key($data, array_flip($this->Member_m->fillable)), false);
				$this->User_account_m->insert([
					'username' => $data['email'],
					'password' => password_hash($data['password'], PASSWORD_DEFAULT),
					'role' => 0,
					'token_reset' => "verifyemail_" . $token
				], false);
				$id_invoice = $this->Transaction_m->generateInvoiceId();
				$this->Transaction_m->insert([
					'id' => $id_invoice,
					'member_id' => $data['id'],
					'checkout' => 1,
					'message_payment' => $data['message_payment'],
					'channel' => $data['channel'],
					'status_payment' => Transaction_m::STATUS_FINISH,
				]);
				$details = [];
				foreach ($data['transaction']['event'] as $tr) {
					$t = explode(",", $tr);
					$details[] = [
						'member_id' => $data['id'],
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
						$data['fullname'].'-payment_proof.pdf' => $tr->exportPaymentProof()->output()
					];
					$this->Gmail_api->sendMessageWithAttachment($data['email'], 'Registered On Site Succesfully - Invoice, Payment Proof', $email_message, $attc);
				}
			} else {
				$error['status'] = false;
//				$error['validation_error'] = array_merge($this->Member_m->getErrors(), ['image' => (isset($this->upload) ? $this->upload->display_errors("", "") : null)]);
				$error['validation_error'] = $this->Member_m->getErrors();;
			}
			$this->output->set_content_type("application/json")
				->set_output(json_encode($error));

		} else {
			$this->load->model(["Category_member_m", "Event_m"]);
			$this->load->helper("form");
			$events = $this->Event_m->eventAvailableNow();
			$this->layout->render("register", [
				'participantsCategory' => $participantsCategory,
				'events' => $events,
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
		$check = $this->Transaction_m->find()->select("count(*) as c")->where(['member_id'=>$post['id'],'status_payment'=>Transaction_m::STATUS_FINISH])
			->get()->row_array();
		$status = true;$message = "";
		if($check['c'] > 0){
			$status = false;
			$message = "Cannot delete this member, The Member already have a transaction !";
		}else{
			$this->Member_m->getDB()->trans_start();
			$this->Member_m->find()->where(['id'=>$post['id']])->delete();
			$this->Transaction_m->find()->where(['member_id'=>$post['id']])->delete();
			$this->User_account_m->find()->where(['username'=>$post['email']])->delete();
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
