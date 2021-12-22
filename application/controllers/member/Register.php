<?php

/**
 * Class Register
 * @property Member_m $Member_m
 */

class Register extends MY_Controller
{
	private $theme;

	public function __construct()
	{
		parent::__construct();
		$this->theme = $this->config->item("theme");
		$this->layout->setLayout("layouts/$this->theme");
		$this->load->model(['Sponsor_link_m']);
	}

	public function index()
	{
		$this->load->model('Category_member_m');
		$this->load->model('Univ_m');

		$status = $this->Category_member_m->find()->select("id,kategory,need_verify")->where('is_hide', '0')->get()->result_array();
		$univ = $this->Univ_m->find()->select("univ_id, univ_nama")->order_by('univ_id')->get()->result_array();
		if ($this->input->post()) {

			$eventAdded = json_decode($this->input->post('eventAdded'));

			$this->load->model(['Member_m', 'User_account_m', 'Notification_m']);
			$this->load->library('Uuid');

			$data = $this->input->post();
			unset($data['eventAdded']);

			$data['id'] = Uuid::v4();
			$univ = Univ_m::withKey($univ, "univ_id");
			$status = Category_member_m::withKey($status, "id");
			$need_verify = (isset($status[$data['status']]) && $status[$data['status']]['need_verify'] == "1");
			if (($this->Member_m->validate($data) && $this->handlingProof('proof', $data['id'], $need_verify))) {
				$data['username_account'] = $data['email'];
				$data['verified_by_admin'] = !$need_verify;
				$data['verified_email'] = 0;
				$data['region'] = 0;
				$data['country'] = 0;

				$token = uniqid();
				$this->Member_m->getDB()->trans_start();
				if ($data['univ'] == Univ_m::UNIV_OTHER) {
					$this->Univ_m->insert(['univ_nama' => strtoupper($data['other_institution'])]);
					$data['univ'] = $this->Univ_m->last_insert_id;
				}
				$this->Member_m->insert(array_intersect_key($data, array_flip($this->Member_m->fillable)), false);
				$this->User_account_m->insert([
					'username' => $data['email'],
					'password' => password_hash($data['password'], PASSWORD_DEFAULT),
					'role' => 0,
					'token_reset' => "verifyemail_" . $token
				], false);

				/* -------------------------------------------------------------------------- */
				/*                              NOTE Transactions                             */
				/* -------------------------------------------------------------------------- */
				$this->transactions($data, $eventAdded);
				$error['transactions'] = $this->getTransactions($data);

				/* -------------------------------------------------------------------------- */
				/*                               NOTE Checkouot                               */
				/* -------------------------------------------------------------------------- */
				$error['paymentBank'] = $this->checkout($data);

				$this->Member_m->getDB()->trans_complete();
				$error['status'] = $this->Member_m->getDB()->trans_status();
				$error['message'] = $this->Member_m->getDB()->error();
				if ($error['status']) {
					$email_message = $this->load->view('template/email_confirmation', ['token' => $token, 'name' => $data['fullname']], true);
					$this->Notification_m->sendMessage($data['email'], 'Email Confirmation', $email_message);
				}
			} else {
				$error['status'] = false;
				$error['validation_error'] = array_merge(
					$this->Member_m->getErrors(),
					[
						'proof' => (isset($this->upload) ? $this->upload->display_errors("", "") : null)
					],
				);
			}
			$this->output->set_content_type("application/json")
				->set_output(json_encode($error));
		} else {
			$this->load->helper("form");
			$participantsCategory = Category_member_m::asList($status, 'id', 'kategory', 'Please Select your status');
			$participantsUniv = Univ_m::asList($univ, 'univ_id', 'univ_nama', 'Please Select your institution');

			$data = [
				'participantsCategory' => $participantsCategory,
				'statusList' => $status,
				'participantsUniv' => $participantsUniv,
				'univlist' => $univ,
				'events' => $this->getEvents(),
				'paymentMethod' => Settings_m::getEnablePayment(),
			];

			$this->layout->render('member/' . $this->theme . '/register', $data);
		}
	}

	public function confirm_email()
	{
		$title = "Token Invalid/Expired";
		$message = "Invalid link !";

		if ($this->input->get('token')) {
			$token = $this->input->get('token');
			$this->load->model(['User_account_m', 'Member_m']);
			$result = $this->User_account_m->findOne(['token_reset' => 'verifyemail_' . $token]);
			if ($result) {
				$this->Member_m->update(['verified_email' => '1'], ['username_account' => $result->username], false);
				$result->token_reset = "";
				$result->save();
				$title = "Email Confirmed";
				$message = "Your email has been confirmed,  Follow this link to login " . anchor(base_url('site/login'), 'Click Here');
			} else {
				$message = "This link has been used to verify email";
			}
		}
		$this->layout->render('member/' . $this->theme . '/notif', ['message' => $message, 'title' => $title]);
	}

	/**
	 * @param $name
	 * @return boolean
	 */
	protected function handlingProof($name, $filename, $need_upload)
	{
		if ($need_upload == false)
			return true;

		$config['upload_path'] = './application/uploads/proof/';
		$config['allowed_types'] = Member_m::$proofExtension;
		$config['max_size'] = 2048;
		$config['file_name'] = $filename;

		$this->load->library('upload', $config);
		return $this->upload->do_upload($name);
	}

	public function getEvents()
	{
		$this->load->model("Event_m");
		$events = $this->Event_m->eventVueModel();
		return $events;
	}

	/**
	 * transactions
	 *
	 * Mengisi data transaksi
	 *
	 * @return void
	 */
	private function transactions($data, $eventAdded)
	{
		foreach ($eventAdded as $key => $event) {
			$event = (array)$event;
			$response = ['status' => true];
			$this->load->model(["Transaction_m", "Transaction_detail_m", "Event_m"]);
			$this->Transaction_m->getDB()->trans_start();
			$transaction = $this->Transaction_m->findOne(['member_id' => $data['id'], 'checkout' => 0]);
			if (!$transaction) {
				$id = $this->Transaction_m->generateInvoiceID();
				$transaction = new Transaction_m();
				$transaction->id = $id;
				$transaction->checkout = 0;
				$transaction->status_payment = Transaction_m::STATUS_WAITING;
				$transaction->member_id = $data['id'];
				$transaction->save();
				$transaction->id = $id;
			}
			$detail = $this->Transaction_detail_m->findOne(['transaction_id' => $transaction->id, 'event_pricing_id' => $event['id']]);
			$fee = $this->Transaction_detail_m->findOne(['transaction_id' => $transaction->id, 'event_pricing_id' => 0]);
			if (!$detail) {
				$detail = new Transaction_detail_m();
			}
			$feeAlready = false;
			if (!$fee) {
				$fee = new Transaction_detail_m();
			} else {
				$feeAlready = true;
			}

			if ($this->Event_m->validateFollowing($event['id'], $event['member_status'])) {
				$detail->event_pricing_id = $event['id'];
				$detail->transaction_id = $transaction->id;
				$detail->price = $event['price'];
				$detail->member_id = $data['id'];
				$detail->product_name = "$event[event_name] ($event[member_status])";
				$detail->save();
				if ($event['price'] > 0 && $feeAlready == false) {
					$fee->event_pricing_id = 0; //$event['id'];
					$fee->transaction_id = $transaction->id;
					$fee->price = rand(100, 500); //"6000";//$event['price'];
					$fee->member_id = $data['id'];
					$fee->product_name = "Unique Additional Price";
					$fee->save();
				}
			} else {
				$response['status'] = false;
				$response['message'] = "You are prohibited from following !";
			}
			$this->Transaction_m->getDB()->trans_complete();
		}
	}

	/**
	 * checkout
	 *
	 * description
	 *
	 * @return void
	 */
	private function checkout($data)
	{
		$this->load->model("Transaction_m");
		if ($this->config->item("use_midtrans")) {
			$transaction = $this->Transaction_m->findOne(['member_id' => $data['id'], 'checkout' => 0]);
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
					$check = $this->Transaction_m->findOne(['member_id' => $data['id'], 'status_payment' => Transaction_m::STATUS_FINISH]);
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

					$fullname = explode(" ", trim($data['fullname']));
					$firstname = (isset($fullname[0]) ? $fullname[0] : "");
					$lastname = (isset($fullname[1]) ? $fullname[1] : "");
					$billing_address = array(
						'first_name' => $firstname,
						'last_name' => $lastname,
						'address' => $data['address'],
						'city' => $data['city'],
						//			'postal_code'   => "",
						'phone' => $data['phone'],
						//			'country_code'  => 'IDN'
					);

					$customer_details = array(
						'first_name' => $firstname,
						'last_name' => $lastname,
						'email' => $data['email'],
						'phone' => $data['phone'],
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
		} else {
			$manual_payment = json_decode(Settings_m::getSetting(Settings_m::MANUAL_PAYMENT), true);
			$transaction = $this->Transaction_m->findOne(['member_id' => $data['id'], 'checkout' => 0]);
			$transaction->checkout = 1;
			$transaction->status_payment = Transaction_m::STATUS_PENDING;
			$transaction->channel = "MANUAL TRANSFER";
			$transaction->save();
			$response['status'] = true;
			$response['manual'] = $manual_payment;
			$this->load->model(["Member_m", "Notification_m"]);

			$member = $this->Member_m->findOne(['id' => $transaction->member_id]);
			$attc = [
				$member->fullname . '-invoice.pdf' => $transaction->exportInvoice()->output(),
			];
			$this->Notification_m->sendMessageWithAttachment($member->email, 'Invoice', "Terima kasih atas partisipasi anda berikut adalah invoice acara yang anda ikuti", $attc);
		}

		return $response;
	}

	/**
	 * getTransactions
	 *
	 * Mengambil data transaksi
	 *
	 * @return void
	 */
	public function getTransactions($data)
	{
		$this->load->model(["Transaction_m"]);
		$transactions = $this->Transaction_m->findAll(['member_id' => $data['id']]);
		$response = ['status' => true, 'cart' => null, 'transaction' => null];
		foreach ($transactions as $trans) {
			if ($trans->checkout == 0) {
				$response['current_invoice'] = $trans->id;
				foreach ($trans->details as $row) {
					$response['cart'][] = $row->toArray();
				}
			} else {
				$detail = [];
				foreach ($trans->details as $row) {
					$detail[] = $row->toArray();
				}
				$response['transaction'][] = array_merge($trans->toArray(), ['detail' => $detail]);
			}
		}
		return $response;
	}
}
