<?php


class Member extends Admin_Controller
{
	public function index()
	{
		$this->load->model('Category_member_m');
		$statusList = $this->Category_member_m->find()->select('*')->get()->result_array();
		$this->layout->render('member', ['statusList' => $statusList]);
	}

	public function test()
	{
		$this->load->model(["Category_member_m", "Transaction_m"]);
		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory', 'Please Select your status');

		$tr = $this->Transaction_m->findOne("INV-20190902-00001");
		$data['participantsCategory'] = $participantsCategory;
		$email_message = $this->load->view('template/success_register_onsite', $data, true);
		$attc = [
			'invoice.pdf' => $tr->exportInvoice()->output(),
			'payment_proof.pdf' => $tr->exportPaymentProof()->output()
		];
//		$this->Gmail_api->sendMessageWithAttachment($data['email'], 'Registered On Site Succesfully - Invoice, Payment Proof', $email_message,$attc);

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
			if ($this->Member_m->validate($data) && $this->handlingImage('image', $data['id'])) {
				$upl = $this->upload->data();

				$data['username_account'] = $data['email'];
				$data['verified_by_admin'] = 1;
				$data['verified_email'] = 0;
				$data['region'] = 0;
				$data['country'] = 0;
				$data['image'] = $upl['file_name'];

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
					'message_payment' => 'Paid using register onsite',
					'channel' => 'DIRECT_ON_SITE',
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
						'invoice.pdf' => $tr->exportInvoice()->output(),
						'payment_proof.pdf' => $tr->exportPaymentProof()->output()
					];
					$this->Gmail_api->sendMessageWithAttachment($data['email'], 'Registered On Site Succesfully - Invoice, Payment Proof', $email_message, $attc);
				}
			} else {
				$error['status'] = false;
				$error['validation_error'] = array_merge($this->Member_m->getErrors(), ['image' => (isset($this->upload) ? $this->upload->display_errors("", "") : null)]);
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

}
