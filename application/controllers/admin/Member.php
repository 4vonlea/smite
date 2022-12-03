<?php


class Member extends Admin_Controller
{
	protected $accessRule = [
		'index' => 'view',
		'card' => 'view',
		'add_status' => 'insert',
		'remove_status' => 'delete',
		'verification_status' => 'update',
		'verify' => 'update',
		'get_proof' => 'view',
		'send_certificate' => 'view',
		'send_nametag' => 'view',
		'register' => 'insert',
		'grid' => 'view',
		'save_profile' => 'update',
		'save_check' => 'update',
		'get_event' => 'view',
		'handlingImage' => 'insert',
		'delete' => 'delete',
	];

	public function index()
	{
		$this->load->model(['Category_member_m', 'Univ_m']);
		$this->load->helper("form");
		$statusList = $this->Category_member_m->find()->select('*')->get()->result_array();

		$univList = $this->Univ_m->find()->order_by("univ_nama")->get();
		$univDl = Univ_m::asList($univList->result_array(), "univ_id", "univ_nama");

		$this->layout->render('member', ['statusList' => $statusList, 'univDl' => $univDl]);
	}

	public function resend_verification()
	{
		$email = $this->input->post("email");
		$this->load->model(["User_account_m", "Notification_m"]);
		$account = $this->User_account_m->findWithBiodata($email);
		if ($account) {
			$token = explode("_",$account['token_reset']);
			if (count($token) == 0) {
				$token[1] = uniqid();
				$this->User_account_m->update([
					'token_reset' => "verifyemail_" . $token[1]
				], ['username' => $email]);
			}
			$this->Notification_m->sendEmailConfirmation($account,$token[1]);
			$this->Notification_m->setType(Notification_m::TYPE_WA)->sendEmailConfirmation($account,$token[1]);

			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => true, 'message' => 'Akun pengguna tidak ditemukan']));
		} else {
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => false, 'message' => 'Akun pengguna tidak ditemukan']));
		}
	}


	public function card($event_id, $member_id)
	{
		$this->load->model('Member_m');
		$member = $this->Member_m->findOne($member_id);
		try {
			$member->getCard($event_id)->stream($member->fullname . "-nametag.pdf", array("Attachment" => false));
		} catch (ErrorException $ex) {
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
			$row['is_hide'] = 0;
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
		$status->need_verify = $this->input->post('need_verify') == "1";
		$status->is_hide = $this->input->post('is_hide') == "1";
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

	public function send_nametag()
	{
		if ($this->input->post()) {
			$this->load->model(["Notification_m", "Event_m","Member_m"]);
			$id = $this->input->post("id");
			$event_name = $this->input->post("event_name");
			if (file_exists(APPPATH . "uploads/nametag_template/$id.txt")) {
				$member = $this->Member_m->findOne($this->input->post("m_id"));
				$card = $member->getCard($id)->output();
				$status = $this->Notification_m->sendNametag($member,$card,$event_name);
				$this->Notification_m->setType(Notification_m::TYPE_WA)->sendNametag($member,$card,$event_name);
				$this->output
					->set_content_type("application/json")
					->_display(json_encode([
						'status'=>$status['status'],
						'data'=>$status,
					]));
			} else {
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => 'Template Nametag is not found ! please set on Setting']));
			}
		}
	}

	public function send_certificate()
	{
		if ($this->input->post()) {
			$this->load->model(["Notification_m", "Event_m","Member_m"]);
			$id = $this->input->post("id");
			$event_name = $this->input->post("event_name");
			if (file_exists(APPPATH . "uploads/cert_template/$id.txt")) {
				$member = $this->Event_m->getParticipant()->where("m.id",$this->input->post("m_id"))->where("t.id",$id)->get()->row_array();
				$cert = $this->Event_m->exportCertificate($member, $id)->output();
				if($this->input->post("channel")){
					$status = $this->Notification_m->setType($this->input->post("channel"))->sendCertificate($member,Notification_m::CERT_TYPE_EVENT,$event_name,$cert);

				}else{
					$status = $this->Notification_m->sendCertificate($member,Notification_m::CERT_TYPE_EVENT,$event_name,$cert);
					$status['wa'] = $this->Notification_m->setType(Notification_m::TYPE_WA)->sendCertificate($member,Notification_m::CERT_TYPE_EVENT,$event_name,$cert);
				}
				$this->output
					->set_content_type("application/json")
					->_display(json_encode([
						'status'=>$status['status'],
						'data'=>$status,
					]));
			} else {
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => 'Template Certificate is not found ! please set on Setting']));
			}
		}
	}

	public function preview_certificate($transactionDetailId)
	{
		$this->load->model(["Notification_m", "Event_m"]);
		$member = $this->Event_m->getParticipant()->where("td.id",$transactionDetailId)->get()->row_array();
		if (file_exists(APPPATH . "uploads/cert_template/$member[event_id].txt")) {
			$member['id'] = $member['m_id'];
			$this->Event_m->exportCertificate($member, $member['event_id'])->stream('preview_cert.pdf', array('Attachment' => 0));
		} else {
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => false, 'message' => 'Template Certificate is not found ! please set on Setting']));
		}
	}

	public function register()
	{
		$this->load->model(["Category_member_m", "Event_m", "Univ_m", "Country_m", "Event_pricing_m","Event_discount_m"]);
		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory', 'Please Select your status');
		if ($this->input->post()) {

			$this->load->model(['Member_m', 'User_account_m', 'Notification_m', 'Transaction_m', 'Transaction_detail_m']);
			$this->load->library('Uuid');

			$data = $this->input->post();

			$id_invoice = $this->Transaction_m->generateInvoiceId();
			$data['id'] = Uuid::v4();
			$data['password'] = strtoupper(substr(uniqid(), -5));
			$data['confirm_password'] = $data['password'];

			$uploadStatus = true;
			$data['payment_proof'] = "-";
			if (isset($_FILES['proof']) && is_uploaded_file($_FILES['proof']['tmp_name'])) {
				$config['upload_path'] = APPPATH . 'uploads/proof/';
				$config['allowed_types'] = 'jpg|png|jpeg|pdf';
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

			if ($this->Member_m->validate($data) && $uploadStatus) { // && $this->handlingImage('image', $data['id'])) {
				$data['username_account'] = $data['email'];
				$data['verified_by_admin'] = 1;
				$data['verified_email'] = 1;
				$data['region'] = 0;
				$data['image'] = ""; // $upl['file_name'];

				$token = uniqid();
				$this->Member_m->getDB()->trans_start();

				// NOTE Institution Other
				if ($data['univ'] == Univ_m::UNIV_OTHER) {
					$this->Univ_m->insert(['univ_nama' => strtoupper($data['other_institution'])]);
					$data['univ'] = $this->Univ_m->last_insert_id;
				}
				// NOTE Country Other
				if ($data['country'] == Country_m::COUNTRY_OTHER) {
					$this->Country_m->insert(['name' => strtoupper($data['other_country'])]);
					$data['country'] = $this->Country_m->last_insert_id;
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
					'status_payment' => $data['status_payment'],// Transaction_m::STATUS_FINISH,
					'payment_proof' => $data['payment_proof'],
					'midtrans_data' => $data['channel'] == Transaction_m::CHANNEL_GL ? json_encode(['sponsorName'=>$this->input->post("sponsor")]) : ""
				]);
				$details = [];
				foreach ($data['transaction']['event'] as $tr) {
					// NOTE Index 0 = id pricing, 1 = price, 2 = price in usd, 3 = product name, 4 = status
					$t = explode(",", $tr);

					// NOTE Harga sesuai dengan database
					$price = $this->Event_pricing_m->findOne(['id' => $t[0], 'condition' => $t[4]]);
					if ($price && $price->price != 0) {
						$t['1'] = $price->price;
					} elseif($price && $price->price_in_usd > 0) {
						$kurs_usd = json_decode(Settings_m::getSetting('kurs_usd'), true);
						$t['1'] = ($price->price_in_usd * $kurs_usd['value']);
					}else if($price){
						$t['1'] = $price->price;
					}

					$details[] = [
						'member_id' => $data['id'],
						'product_name' => $t[3],
						'transaction_id' => $id_invoice,
						'event_pricing_id' => $t[0],
						'price' => $t[1],
					];
				}
				$this->Transaction_detail_m->batchInsert($details);

				$this->Member_m->getDB()->trans_complete();
				$error['status'] = $this->Member_m->getDB()->trans_status();
				$error['message'] = $this->Member_m->getDB()->error();
				$error['url'] = base_url("admin/member/index?q=") . $data['id'];
				$error['email'] = $data['email'];
				if ($error['status']) {
					$tr = $this->Transaction_m->findOne($id_invoice);
					$this->Notification_m->sendRegisteredByOther($data,$tr,$participantsCategory);
					$this->Notification_m->setType(Notification_m::TYPE_WA)->sendRegisteredByOther($data,$tr,$participantsCategory);

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
			$events = $this->Event_m->eventAvailableNow(true);
			// NOTE Univ
			$univList = $this->Univ_m->find()->order_by("univ_id,univ_nama")->get();
			$univDl = Univ_m::asList($univList->result_array(), "univ_id", "univ_nama");
			// NOTE Country
			$countryList = $this->Country_m->find()->order_by("id,name")->get();
			$countryDl = Univ_m::asList($countryList->result_array(), "id", "name");

			$data = [
				'participantsCategory' => $participantsCategory,
				'events' => $events,
				'discount'=>$this->Event_discount_m->getLikeEvent(),
				'univDl' => $univDl,
				'countryDl' => $countryDl,
			];
			$this->layout->render("register", $data);
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

	public function save_profile()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model('Member_m');
		$data = $this->input->post();
		$model = $this->Member_m->findOne(['id' => $data['id']]);
		$account = $model->account;
		$data['email'] = trim($data['email']);
		if ($model->email != $data['email']) {
			if ($account) {
				$account->username = $data['email'];
				$check = $account->toArray();
				$check['username'] = $data['email'];
				if ($account->validate($check)) {
					$account->save();
					$data['username_account'] = $data['email'];
				} else {
					$this->output
						->set_content_type("application/json")
						->_display(json_encode(['status' => false, 'message' => "Username/Email sudah dipakai oleh member lain"]));
					exit;
				}
			} else {
				$token = uniqid();
				$this->User_account_m->insert([
					'username' => $data['email'],
					'password' => password_hash("1q2w3e4r", PASSWORD_DEFAULT),
					'role' => 0,
					'token_reset' => "verifyemail_" . $token
				], false);
			}
		}
		$model->setAttributes($data);
		$status = $model->save(false);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $model->getErrors()]));
	}

	public function save_check()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model('Transaction_detail_m');
		$data = $this->input->post('transaction');
		foreach ($data as $row) {
			$this->Transaction_detail_m->update(['checklist' => json_encode($row['checklist'])], ['id' => $row['id']]);
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => true]));
	}

	public function get_event()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model('Event_m');

		$member_id = $this->input->post('id');
		$result = $this->Event_m->getParticipant()->where('m.id', $member_id)->get();
		$return = [];
		foreach ($result->result_array() as $i => $row) {
			if ($row['checklist'] != "" && $row['checklist'] != "null" && $row['checklist'] != "{}")
				$row['checklist'] = json_decode($row['checklist'], true);
			else
				$row['checklist'] = ['nametag' => false, 'seminarkit' => false, 'taker' => ''];
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
		$this->load->model(["Member_m", "Transaction_m", "User_account_m"]);
		$post = $this->input->post();
		$check = $this->Transaction_m->find()->select("count(*) as c")->where(['member_id' => $post['id']])
			->get()->row_array();
		$status = true;
		$message = "";
		if ($check['c'] > 0) {
			$status = false;
			$message = "Cannot delete this member, The Member already have a transaction !";
		} else {
			$this->Member_m->getDB()->trans_start();
			$this->Member_m->find()->where(['id' => $post['id']])->delete();
			$this->Transaction_m->find()->where(['member_id' => $post['id']])->delete();
			$this->User_account_m->find()->where(['username' => $post['email']])->delete();
			$this->Member_m->getDB()->trans_complete();
			$status = $this->Member_m->getDB()->trans_status();
			if ($status == false)
				$message = "Failed to delete member, error on server !";
		}

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	/* -------------------------------------------------------------------------- */
	/*                             NOTE Register Group                            */
	/* -------------------------------------------------------------------------- */
	/**
	 * register_group
	 *
	 * Manual Registrations Group
	 *
	 * @return void
	 */
	public function register_group()
	{
		$this->load->model(["Category_member_m", "Event_m", "Univ_m", "Country_m"]);
		$this->load->library('form_validation');
		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory');
		if ($this->input->post()) {

			$this->load->model(['Member_m', 'User_account_m', 'Notification_m', 'Transaction_m', 'Transaction_detail_m', 'Event_pricing_m']);
			$this->load->library('Uuid');

			$model = json_decode($this->input->post('model'), true);

			$transaction = $this->input->post('transaction');
			$bill_to = $this->input->post('bill_to');
			$email_group = $this->input->post('email_group');

			$id_invoice = $this->Transaction_m->generateInvoiceId();
			$error = [];

			$this->form_validation->set_rules('bill_to', 'Bill To', 'required');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('email_group', 'Email', 'required|valid_email');

			$validationError = false;
			$model['validation_error'] = [];
			// NOTE Validasi Bill To dan Status
			if (!$this->form_validation->run()) {
				$validationError = true;

				$model['validation_error']['bill_to'] = form_error('bill_to');
				$model['validation_error']['status'] = form_error('status');
				$model['validation_error']['email_group'] = form_error('email_group');
			}

			// NOTE Validasi Jumlah Member
			if (count($model['members']) == 0) {
				$validationError = true;
				$model['validation_error']['members'] = 'Minimal 1 member';
			}

			$data['payment_proof'] = "-";
			if (isset($_FILES['proof']) && is_uploaded_file($_FILES['proof']['tmp_name'])) {
				$config['upload_path'] = APPPATH . 'uploads/proof/';
				$config['allowed_types'] = 'jpg|png|jpeg|pdf';
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

			// NOTE Validation Members
			$count = 0;
			foreach ($model['members'] as $key => $val) {

				$model['members'][$key]['id_invoice'] = $id_invoice;
				$model['members'][$key]['id'] = Uuid::v4();
				$model['members'][$key]['password'] = strtoupper(substr(uniqid(), -5));
				$model['members'][$key]['confirm_password'] = $model['members'][$key]['password'];
				$model['members'][$key]['phone'] = $val['phone'] ?? "0";
				$model['members'][$key]['kta'] = $val['kta'] ?? "0";
				$model['members'][$key]['country'] = '104';
				$model['members'][$key]['birthday'] = date('Y-m-d');

				$uploadStatus = true;

				$model['members'][$key]['status'] = $model['status'];
				if (!$this->Member_m->validate($model['members'][$key]) || !$uploadStatus) {
					$model['members'][$key]['validation_error'] = array_merge($this->Member_m->getErrors(), ['proof' => ($uploadStatus == false ? $this->upload->display_errors("", "") : null)]);
					$count += 1;
				}
			}

			if ($validationError || $count > 0) {
				$status = false;
			} else {
				$status = true;

				$this->Member_m->getDB()->trans_start();

				// NOTE Data Transactions
				$this->Transaction_m->insert([
					'id' => $id_invoice,
					'member_id' => "REGISTER-GROUP : {$bill_to}",
					'checkout' => 1,
					'message_payment' => '',
					'channel' => $this->input->post('channel'),//$channel,
					'status_payment' => $this->input->post('status_payment'),//Transaction_m::STATUS_FINISH,
					'payment_proof' => $data['payment_proof'],
					'email_group' => $email_group,
				]);

				foreach ($model['members'] as $key => $data) {

					// NOTE Data Member
					$data['username_account'] = $data['email'];
					$data['verified_by_admin'] = 1;
					$data['verified_email'] = 1;
					$data['region'] = 0;
					$data['image'] = ""; // $upl['file_name'];

					$token = uniqid();
					// NOTE Institution Other
					if ($data['univ'] == Univ_m::UNIV_OTHER) {
						$_POST['univ'] = $data['univ'];
						$this->Univ_m->insert(['univ_nama' => strtoupper($data['other_institution'])]);
						$data['univ'] = $this->Univ_m->last_insert_id;
					}
					// NOTE Country Other
					if ($data['country'] == Country_m::COUNTRY_OTHER) {
						$_POST['country'] = $data['country'];
						$this->Country_m->insert(['name' => strtoupper($data['other_country'])]);
						$data['country'] = $this->Country_m->last_insert_id;
					}

					$dataMember = $this->Member_m->findOne(['email' => $data['email']]);
					if ($dataMember) {
						$dataMember = $dataMember->toArray();
						$dataMember['fullname'] = $data['fullname'];
						$dataMember['univ'] = $data['univ'];
						$dataMember['sponsor'] = $data['sponsor'];
						$this->Member_m->update($dataMember, $dataMember['id'], false);

						$members[$key]['id'] = $data['id'] = $dataMember['id'];
						// NOTE Accounts
						$this->User_account_m->update([
							'username' => $data['email'],
							'password' => password_hash($data['password'], PASSWORD_DEFAULT),
							'role' => 0,
							'token_reset' => "verifyemail_" . $token
						], $data['email'], false);
					} else {
						$this->Member_m->insert(array_intersect_key($data, array_flip($this->Member_m->fillable)), false);

						// NOTE Accounts
						$this->User_account_m->insert([
							'username' => $data['email'],
							'password' => password_hash($data['password'], PASSWORD_DEFAULT),
							'role' => 0,
							'token_reset' => "verifyemail_" . $token
						], false);
					}

					// NOTE Data Detail Transactions
					// NOTE Index 0 = id pricing, 1 = price, 2 = price in usd, 3 = product name, 4 = status
					$details = [];
					foreach ($model['selected'] as $keyEvent => $event) {

						// NOTE Harga sesuai dengan database
						$price = $this->Event_pricing_m->findOne(['id' => $event[0], 'condition' => $event[4]]);
						if ($price->price != 0) {
							$event['1'] = $price->price;
						} elseif($price->price_in_usd > 0) {
							$kurs_usd = json_decode(Settings_m::getSetting('kurs_usd'), true);
							$event['1'] = ($price->price_in_usd * $kurs_usd['value']);
						}else{
							$event['1'] = $price->price;
						}

						$details[] = [
							'member_id' => $data['id'],
							'product_name' => $event[3],
							'transaction_id' => $id_invoice,
							'event_pricing_id' => $event[0],
							'price' => $event[1],
							'price_usd' => $price->price_in_usd,
						];
					}
					$this->Transaction_detail_m->batchInsert($details);

					// NOTE Send Message
					$this->Member_m->getDB()->trans_complete();
					$error['status'] = $this->Member_m->getDB()->trans_status();
					$error['message'] = $this->Member_m->getDB()->error();
					$error['url'] = base_url("admin/member/index?q=") . $data['id'];
					$error['email'] = $data['email'];
					if ($error['status']) {
						$tr = $this->Transaction_m->findOne($id_invoice);
						$this->Notification_m->sendRegisteredByOther($data,$tr,$participantsCategory);
						$this->Notification_m->setType(Notification_m::TYPE_WA)->sendRegisteredByOther($data,$tr,$participantsCategory);

					}
				}
			}
			$this->output->set_content_type("application/json")
				->set_output(json_encode(
					[
						'status' => $status,
						'members' => $model['members'],
						'validation_error' => $model['validation_error'],
					]
				));
		} else {
			$this->load->model(["Category_member_m", "Event_m"]);
			$this->load->helper("form");
			$events = $this->Event_m->eventAvailableNow(true);
			$univList = $this->Univ_m->find()->order_by("univ_id,univ_nama")->get();
			$univDl = Univ_m::asList($univList->result_array(), "univ_id", "univ_nama");

			$data = [
				'participantsCategory' => $participantsCategory,
				'events' => $events,
				'univDl' => $univDl
			];
			$this->layout->render("register_group", $data);
		}
	}

	public function cek_member_perdossi(){
		$this->load->model('Member_m');
		$id = $this->input->post("id");
		$nik = $this->input->post("nik");
		$this->load->library('Api_perdossi');
        $response = $this->api_perdossi->getMemberByNIK($nik);
		if($response['message'] == "success"){
			$member = $response['member'];
			$this->Member_m->update([
				'kta' => $member['perdossi_no'],
				'fullname' => "$member[member_title_front] $member[fullname] $member[member_title_back]",
				'phone' => $member['member_phone'],
			],$id,false);
		}else{
			$this->Member_m->update([
				'kta' => "-"
			],$id,false);
		}
        $this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function search(){
		$this->load->model('Member_m');
		$search = $this->input->post("search");
		$response = $this->Member_m->find()->select("id as code, CONCAT(fullname,' (',email,')') as label,status")
									->like("fullname",$search)
									->or_like("email",$search)
									->get()->result_array();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode([
			'search'=>$search,
			'data'=>$response,
		]));

	}
}
