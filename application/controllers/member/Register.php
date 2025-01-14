<?php

/**
 * Class Register
 * @property Member_m $Member_m
 * @property Event_m $Event_m
 * @property Room_m $Room_m
 * @property CI_Output $output
 * @property CI_Input $input
 * @property Notification_m $Notification_m
 * @property CI_Form_validation $form_validation
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

	public function get_events()
	{
		ini_set('memory_limit', '2048M');
		if ($this->input->method() !== 'post')
			show_404("Page not found !");
		$this->load->model(["Event_m", "Room_m"]);
		$oldStatus = $this->session->userdata('tempStatusMember');
		if ($oldStatus !=  $this->input->post("status")) {
			$this->session->set_userdata('tempStatusMember', $this->input->post("status"));
			$this->session->set_userdata('tempStatusId', $this->input->post("statusId"));
			$this->load->model("Transaction_detail_m");
			$this->Transaction_detail_m->find()->where("member_id", $this->session->userdata('tempMemberId'))->delete();
		}
		$events = $this->Event_m->eventVueModel($this->session->userdata('tempMemberId'), $this->input->post("status"), [], false);
		$booking = $this->Room_m->bookedRoom($this->session->userdata('tempMemberId'));
		$rangeBooking = $this->Room_m->rangeBooking();
		$this->output->set_content_type("application/json")
			->_display(json_encode(['status' => true, 'events' => $events, 'booking' => $booking, 'rangeBooking' => $rangeBooking]));
	}

	public function add_cart()
	{
		if ($this->input->method() !== 'post')
			show_404("Page not found !");

		$response = ['status' => true];
		$data = $this->input->post();
		$this->load->model(["Transaction_m", "Transaction_detail_m", "Event_m", "Event_pricing_m"]);
		$this->Transaction_m->getDB()->trans_start();
		$transaction = $this->Transaction_m->findOne(['member_id' => $this->session->userdata('tempMemberId'), 'checkout' => 0]);
		if (!$transaction) {
			$id = $this->Transaction_m->generateInvoiceID();
			$transaction = new Transaction_m();
			$transaction->id = $id;
			$transaction->checkout = 0;
			$transaction->status_payment = Transaction_m::STATUS_WAITING;
			$transaction->member_id = $this->session->userdata('tempMemberId');
			$transaction->save();
			$transaction->id = $id;
		}
		$detail = $this->Transaction_detail_m->findOne(['transaction_id' => $transaction->id, 'event_pricing_id' => $data['id']]);
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

		if (!isset($data['is_hotel'])) {
			$addEventStatus = $this->Transaction_detail_m->validateAddEvent($data['id'], $this->session->userdata('tempMemberId'), $this->session->userdata('tempStatusMember'));
			if ($addEventStatus === true) {
				// NOTE Harga sesuai dengan database
				$price = $this->Event_pricing_m->findWithProductName(['id' => $data['id'], 'condition' => $this->session->userdata('tempStatusMember')]);
				if ($price->price != 0) {
					$data['price'] = $price->price;
				} else {
					$kurs_usd = json_decode(Settings_m::getSetting('kurs_usd'), true);
					$data['price'] = ($price->price_in_usd * $kurs_usd['value']);
				}
				$detail->event_pricing_id = $data['id'];
				$detail->transaction_id = $transaction->id;
				$detail->price = $data['price'];
				$detail->price_usd = $price->price_in_usd;
				$detail->member_id = $this->session->userdata('tempMemberId');
				$detail->product_name = $price->product_name;
				$detail->save();
			} else {
				$response['status'] = false;
				$response['message'] = $addEventStatus ?? "You are prohibited from following !";
			}
		} else {
			$result = $this->Transaction_detail_m->bookHotel($transaction->id, $this->session->userdata('tempMemberId'), $data);
			$data['price'] = 0;
			if ($result === true) {
				$data['price'] = 1;
				$response['status'] = true;
			} else {
				$response['status'] = false;
				$response['message'] = $result;
			}
		}

		if ($data['price'] > 0 && $feeAlready == false) {
			$fee->event_pricing_id = 0; //$data['id'];
			$fee->transaction_id = $transaction->id;
			$fee->price = Transaction_m::ADMIN_FEE_START + rand(100, 500); //"6000";//$data['price'];
			$fee->member_id = $this->session->userdata('tempMemberId');
			$fee->product_name = "Admin Fee";
			$fee->save();
		}
		$response['id'] = $transaction->id;
		$this->Transaction_m->setDiscount($transaction->id);
		$this->Transaction_m->getDB()->trans_complete();

		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function delete_item_cart()
	{
		if ($this->input->method() !== 'post')
			show_404("Page not found !");
		$id = $this->input->post('id');
		$this->load->model(["Transaction_detail_m"]);

		$this->output->set_content_type("application/json")
			->_display(json_encode($this->Transaction_detail_m->deleteItem($id, $this->input->post("transaction_id"))));
	}


	public function index()
	{
		$this->load->model('Category_member_m');
		$this->load->model('Transaction_m');
		$this->load->model('Univ_m');
		$this->load->model('Country_m');
		$this->load->model('Event_pricing_m');
		$this->load->model('Transaction_detail_m');
		$this->load->model('Room_m');
		$this->load->model('Wilayah_m');
		$this->load->model(['Member_m', 'User_account_m', 'Notification_m']);

		$status = $this->Category_member_m->find()->select("id,kategory,need_verify")->where('is_hide', '0')->get()->result_array();
		$univ = $this->Univ_m->find()->select("univ_id, univ_nama")->order_by('univ_id')->get()->result_array();
		$country = $this->Country_m->find()->select("id, name")->order_by('id')->get()->result_array();
		$country[] = ['id' => Country_m::COUNTRY_OTHER, 'name' => 'Other Country'];
		$kabupaten = $this->Wilayah_m->getKabupatenKota()->result_array();
		if ($this->input->post()) {
			$this->load->model(['Member_m', 'User_account_m', 'Notification_m']);
			$this->load->library('Uuid');

			$data = $this->input->post();
			$univ = Univ_m::withKey($univ, "univ_id");
			$status = Category_member_m::withKey($status, "id");
			$need_verify = (isset($status[$data['status']]) && $status[$data['status']]['need_verify'] == "1");
			$dataMember = $this->Member_m->findOne(['id' => $this->session->userdata("tempMemberId") ?? "-"]);
			$rules = $this->Member_m->rules(
				$dataMember &&
					$this->session->userdata('transaksiFinish') == '0' &&
					$dataMember->nik == $data['nik'] && $dataMember->email == $data['email']
			);
			$this->load->library("Form_validation");
			$this->form_validation->set_rules($rules);
			$this->form_validation->set_data($data);
			$transaction = [];
			if (($this->form_validation->run() && $this->handlingProof('proof', $data['nik'], $need_verify))) {
				$data['username_account'] = $data['email'];
				$data['verified_by_admin'] = !$need_verify;
				$data['verified_email'] = 0;
				$data['region'] = 0;
				$data['isGroup'] = false;

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

				$id = Uuid::v4();
				if ($dataMember) {
					$id = $dataMember->id;
					$data['id'] = $id;
					$dataMember = $dataMember->toArray();
					foreach ($dataMember as $key => $value) {
						if (isset($data[$key])) {
							$dataMember[$key] = $data[$key];
						}
					}
					$this->Member_m->update($dataMember, $id, false);
					$this->User_account_m->update([
						'username' => $data['email'],
						'password' => password_hash($data['password'], PASSWORD_DEFAULT),
						'role' => 0,
						'token_reset' => "verifyemail_" . $token
					], $data['email'], false);
				} else {
					$data['id'] = $id;
					$this->Member_m->insert(array_intersect_key($data, array_flip($this->Member_m->fillable)), false);
					$this->User_account_m->insert([
						'username' => $data['email'],
						'password' => password_hash($data['password'], PASSWORD_DEFAULT),
						'role' => 0,
						'token_reset' => "verifyemail_" . $token
					], false);
				}
				$tempMemberId = $this->session->userdata("tempMemberId");
				$transactionRow = $this->Transaction_m->findOne(["member_id" => $tempMemberId]);
				if ($transactionRow) {
					$transaction = $transactionRow ? $transactionRow->toArray() : [];
					$transaction['details'] = $this->Transaction_detail_m->find()->where(['transaction_id' => $transactionRow->id])->get()->result_array();
					$this->Transaction_m->find()->where("member_id", $tempMemberId)->set(['member_id' => $id])->update();
					$this->Transaction_detail_m->find()->where("member_id", $tempMemberId)->set(['member_id' => $id])->update();
					$this->Member_m->getDB()->trans_complete();
					$error['statusData'] = $this->Member_m->getDB()->trans_status();
					$error['message'] = $this->Member_m->getDB()->error();
					if ($error['statusData']) {
						$this->session->set_userdata("tempMemberId", $id);
						$this->Notification_m->sendEmailConfirmation($data, $token);
						$this->Notification_m->setType(Notification_m::TYPE_WA)->sendEmailConfirmation($data, $token);
					}
				} else {
					$error['statusData'] = false;
					$error['message'] = "Please add minimal 1 events";
				}
			} else {
				$error['statusData'] = false;
				$error['validation_error'] = $this->form_validation->error_array();
			}
			$this->output->set_content_type("application/json")
				->set_output(json_encode(array_merge($error, ['data' => $data, 'transaction' => $transaction])));
		} else {
			$this->load->helper("form");
			$participantsCategory = Category_member_m::asList($status, 'id', 'kategory', 'Please Select your status');
			$participantsUniv = Univ_m::asList($univ, 'univ_id', 'univ_nama', 'Please Select your institution');
			$participantsCountry = Country_m::asList($country, 'id', 'name', 'Please Select your country');
			if (!$this->session->has_userdata('tempMemberId')) {
				$this->session->set_userdata("tempMemberId", uniqid());
			}
			$this->session->set_userdata('transaksiFinish', '0');
			$data = [
				'tempMemberId' => $this->session->userdata('tempMemberId'),
				'defaultMember' => $this->Member_m->findOne($this->session->userdata('tempMemberId')),
				'participantsCategory' => $participantsCategory,
				'participantsUniv' => $participantsUniv,
				'participantsCountry' => $participantsCountry,
				'statusList' => $status,
				'univlist' => $univ,
				'paymentMethod' => Settings_m::getEnablePayment(),
				'rangeBooking' => $this->Room_m->rangeBooking(),
				'kabupatenList' => $kabupaten,
			];
			$this->layout->render('member/' . $this->theme . '/register', $data);
		}
	}

	public function group($id_invoice = null)
	{
		$this->load->model('Category_member_m');
		$this->load->model('Univ_m');
		$this->load->model('Country_m');

		$status = $this->Category_member_m->find()->select("id,kategory,need_verify")->where('is_hide', '0')->get()->result_array();
		$univ = $this->Univ_m->find()->select("univ_id, univ_nama")->order_by('univ_id')->get()->result_array();
		$participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory', 'Please Select your status');
		$this->load->model(['Member_m', 'User_account_m', 'Notification_m', 'Transaction_m', 'Transaction_detail_m']);

		if ($this->input->post()) {

			$eventAdded = json_decode($this->input->post('eventAdded'));

			// NOTE Validasi Event Required
			$isRequired = 0;
			foreach ($eventAdded as $key => $value) {
				if (isset($value->event_required) && $value->event_required != '') {
					$findRequired = array_search($value->event_required, array_column($eventAdded, 'event_name'));
					if ($findRequired === false) {
						$isRequired += 1;
					}
				}
			}

			$this->load->model(['Member_m', 'User_account_m', 'Notification_m', 'Transaction_m', 'Transaction_detail_m']);
			$this->load->library('form_validation');
			$this->load->library('Uuid');

			$data = $this->input->post();
			unset($data['eventAdded']);

			$data['id'] = Uuid::v4();
			$univ = Univ_m::withKey($univ, "univ_id");
			$status = Category_member_m::withKey($status, "id");
			$need_verify = (isset($status[$data['status']]) && $status[$data['status']]['need_verify'] == "1");

			// NOTE Data Members
			$members = json_decode($data['members'], true);

			// NOTE Data Sebelumnya
			$dataSebelumnya = json_decode($data['data'], true);

			// NOTE Delete Member
			if (isset($dataSebelumnya['members'])) {
				foreach ($dataSebelumnya['members'] as $key => $value) {
					$find = array_search($value['email'], array_column($members, 'email'));
					if ($find === false) {
						$this->Member_m->delete(['email' => $value['email']]);
						$this->User_account_m->delete($value['email']);
					}
				}
			}

			// NOTE Hapus Transactions Detail jika sudah dikirim id_invoice
			if (isset($dataSebelumnya['id_invoice']) && $dataSebelumnya['id_invoice'] != '') {
				$this->Transaction_detail_m->delete(['transaction_id' => $dataSebelumnya['id_invoice']]);
			}

			$id_invoice = (isset($dataSebelumnya['id_invoice']) && $dataSebelumnya['id_invoice'] != '') ? $dataSebelumnya['id_invoice'] : $this->Transaction_m->generateInvoiceId();

			$statusParticipant = $data['status'];
			$bill_to = "REGISTER-GROUP : {$data['bill_to']}";
			$bill_to_input = $data['bill_to'];
			$email_group = $data['email_group'];

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
			if (count($members) == 0) {
				$validationError = true;
				$model['validation_error']['members'] = 'Minimal 1 member';
			}

			// NOTE Validation Members
			$count = 0;
			foreach ($members as $key => $data) {
				$members[$key]['id_invoice'] = $id_invoice;
				$members[$key]['bill_to'] = $bill_to;
				$members[$key]['bill_to_input'] = $bill_to_input;
				$members[$key]['id'] = Uuid::v4();
				$members[$key]['password'] = isset($data['password']) ? $data['password'] : strtoupper(substr(uniqid(), -5));
				$members[$key]['confirm_password'] = $members[$key]['password'];
				$members[$key]['phone'] = $data['phone'] ?? "0";
				$members[$key]['nik'] = $data['nik'] ?? "0";
				$members[$key]['kta'] = $data['kta'] ?? "0";
				$members[$key]['region'] = '0';
				$members[$key]['country'] = '0';
				$members[$key]['birthday'] = date('Y-m-d');
				$members[$key]['sponsor'] = $bill_to_input;
				$members[$key]['status'] = $statusParticipant;
				if (!$this->Member_m->validate($members[$key]) || !$this->handlingProof('proof', $members[$key]['id'], $need_verify)) {
					$error['statusData'] = false;
					$members[$key]['validation_error'] = array_merge(
						$this->Member_m->getErrors(),
						[
							'proof' => (isset($this->upload) ? $this->upload->display_errors("", "") : null),
						]
					);
					$count += 1;
				}
			}

			$error = [];
			if ($count > 0 || $validationError || $isRequired > 0 || count($eventAdded) == 0) {
				$status = false;
			} else {
				$status = true;

				// NOTE Insert atau Update Transaction
				$transaction = $this->Transaction_m->findOne(['id' => $id_invoice, 'checkout' => 0]);
				if (!$transaction) {
					$id = $id_invoice;
					$transaction = new Transaction_m();
					$transaction->id = $id;
					$transaction->checkout = 0;
					$transaction->status_payment = Transaction_m::STATUS_WAITING;
					$transaction->member_id = $bill_to;
					$transaction->email_group = $email_group;
					$transaction->save();
					$transaction->id = $id;
				}

				$tr = $this->Transaction_m->findOne($id_invoice);
				foreach ($members as $key => $data) {
					$data['username_account'] = $data['email'];
					$data['verified_by_admin'] = !$need_verify;
					$data['verified_email'] = 0;
					$data['region'] = 0;
					// $data['country'] = 0;
					$data['isGroup'] = true;

					$token = uniqid();
					$this->Member_m->getDB()->trans_start();

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
					// NOTE Insert Member
					$dataMember = $this->Member_m->findOne(['email' => $data['email']]);
					if ($dataMember) {
						$dataMember = $dataMember->toArray();
						$dataMember['fullname'] = $data['fullname'];
						$dataMember['phone'] = $data['phone'] ?? "0";
						$dataMember['kta'] = $data['kta'] ?? "";
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

						$this->Notification_m->sendEmailConfirmation($data, $token);
						$this->Notification_m->setType(Notification_m::TYPE_WA)->sendEmailConfirmation($data, $token);
					}

					/* -------------------------------------------------------------------------- */
					/*                              NOTE Transactions                             */
					/* -------------------------------------------------------------------------- */
					$this->transactions($data, $eventAdded, $transaction);

					$this->Member_m->getDB()->trans_complete();
					$error['statusData'] = $this->Member_m->getDB()->trans_status();
					$error['message'] = $this->Member_m->getDB()->error();

					if ($error['statusData']) {
						if ($data) {
							$this->Notification_m->sendRegisteredByOther($data, $tr, $participantsCategory);
							$this->Notification_m->setType(Notification_m::TYPE_WA)->sendRegisteredByOther($data, $tr, $participantsCategory);
						}
					}
				}
				$invoiceDataPdf = $tr->exportInvoice()->output();
				$this->Notification_m->sendMessageWithAttachment($tr->email_group, 'Your Group Registration Billing', "<p>Dear Participant.</p><p>You have succesfully group registered and we have created an invoice to pay off based on your event participation. Thank you and we are happy to meet you in this event(s).</p><p>Herewith we attached your invoice to pay off. Please complete your transaction as soon as possible. Our system will automatically settle after we receive your payment. If you dont receive any further notification after completing your transferred payment for more than 1x24 hours, please contact the committee.</p><p>Thank you.</p><p>Registration Committee</p>", [
					'invoice.pdf' => $invoiceDataPdf,
				]);
				$error['transactions'] = $this->getTransactions($transaction);
			}
			$this->output->set_content_type("application/json")
				->set_output(json_encode(array_merge(
					$error,
					[
						'status' => $status,
						'data' => [
							'bill_to' => $bill_to_input,
							'id_invoice' => $id_invoice,
							'email_group' => $email_group,
							'members' => $members,
							'validation_error' => array_merge($model['validation_error'], [
								'eventAdded' => (count($eventAdded) == 0)  ? 'Choose at least 1 event' : '',
								'requiredEvent' => $isRequired > 0 ? 'Please select required event' : '',
							]),
						]
					]
				)));
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

			if ($id_invoice) {
				$transaction = $this->Transaction_m->findOne($id_invoice);
				if ($transaction->status_payment == Transaction_m::STATUS_WAITING) {
					$error['transactions'] = $this->getTransactions($transaction);
					$bill_to = $transaction->member_id;
					$bill_to_input = str_replace("REGISTER-GROUP : ", "", $transaction->member_id);
					$listMember = $this->Transaction_detail_m->find()->where("transaction_id", $id_invoice)
						->join("members", "members.id = transaction_details.member_id")
						->join("kategory_members km", "km.id = members.status")
						->select("members.*,km.kategory as status_text")
						->get()->result();
					$members = [];
					$status_selected = "";
					$status_text = "";
					foreach ($listMember as $key => $dataMember) {
						$members[$key]['id_invoice'] = $id_invoice;
						$members[$key]['bill_to'] = $bill_to;
						$members[$key]['bill_to_input'] = $bill_to_input;
						$members[$key]['id'] = $dataMember->id;
						$members[$key]['phone'] = $dataMember->phone;
						$members[$key]['region'] = $dataMember->region;
						$members[$key]['country'] = $dataMember->country;
						$members[$key]['birthday'] = $dataMember->birthday;
						$members[$key]['sponsor'] = $bill_to_input;
						$members[$key]['nik'] = $dataMember->nik;
						$members[$key]['fullname'] = $dataMember->fullname;
						$members[$key]['email'] = $dataMember->email;
						$members[$key]['univ'] = $dataMember->univ;
						$members[$key]['p2kb_member_id'] = $dataMember->p2kb_member_id;
						$members[$key]['status'] = $dataMember->status;
						$members[$key]['validation_error'] = ['nik' => null];
						$members[$key]['checking'] = false;
						$status_selected = $dataMember->status;
						$status_text = $dataMember->status_text;
					}
					$data['continueTransaction'] = (array_merge(
						$error,
						[
							'status' => ['status_selected' => $status_selected, 'status_text' => $status_text],
							'data' => [
								'bill_to' => $bill_to_input,
								'id_invoice' => $id_invoice,
								'email_group' => $transaction->email_group,
								'members' => $members,
							]
						]
					));
				}
			}
			$this->layout->render('member/' . $this->theme . '/register_group', $data);
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
		$events = $this->Event_m->eventAvailableNow();
		return $events;
	}

	/**
	 * transactions
	 *
	 * Mengisi data transaksi
	 *
	 * @return void
	 */
	private function transactions($data, $eventAdded, $transaction)
	{
		$this->load->model(["Transaction_m", "Transaction_detail_m", "Event_m"]);
		foreach ($eventAdded as $key => $event) {
			$event = (array)$event;
			$response = ['status' => true];

			$detail = $this->Transaction_detail_m->findOne(['transaction_id' => $transaction->id, 'member_id' => $data['id'], 'event_pricing_id' => $event['id']]);
			if (!$detail) {
				$detail = new Transaction_detail_m();
			}


			if ($this->Event_m->validateFollowing($event['id'], $event['member_status'])) {

				// NOTE Harga sesuai dengan database
				$price = $this->Event_pricing_m->findWithProductName(['id' => $event['id'], 'condition' => $event['member_status']]);
				if ($price->price != 0) {
					$event['price'] = $price->price;
				} else {
					$kurs_usd = json_decode(Settings_m::getSetting('kurs_usd'), true);
					$event['price'] = ($price->price_in_usd * $kurs_usd['value']);
				}

				$detail->event_pricing_id = $event['id'];
				$detail->transaction_id = $transaction->id;
				$detail->price = $event['price'];
				$detail->price_usd = $price->price_in_usd;
				$detail->member_id = $data['id'];
				$detail->product_name = $price->product_name;
				$detail->save();
			} else {
				$response['status'] = false;
				$response['message'] = "You are prohibited from following !";
			}
		}
		$feeAlready = false;
		$fee = $this->Transaction_detail_m->findOne(['transaction_id' => $transaction->id, 'member_id' => $data['id'],  'event_pricing_id' => 0]);
		if (!$fee) {
			$fee = new Transaction_detail_m();
		} else {
			$feeAlready = true;
		}
		if ($this->Transaction_detail_m->sumPriceDetail($transaction->id) > 0 && $feeAlready == false) {
			$check = $data['isGroup'] ? $this->Transaction_detail_m->findOne(['transaction_id' => $transaction->id, 'member_id' => $data['bill_to'], 'event_pricing_id' => 0]) : false;
			if (!$check) {
				$fee->event_pricing_id = 0; //$event['id'];
				$fee->transaction_id = $transaction->id;
				$fee->price = Transaction_m::ADMIN_FEE_START + rand(100, 500); //"6000";//$event['price'];
				$fee->member_id = $data['isGroup'] ? $data['bill_to'] : $data['id'];
				$fee->product_name = "Admin Fee";
				$fee->save();
			}
		}
		$this->Transaction_m->setDiscount($transaction->id);
		$this->Transaction_m->getDB()->trans_complete();
	}

	public function clear_session()
	{
		$this->session->set_userdata('transaksiFinish', '1');
		$this->session->unset_userdata("tempMemberId");
		$this->session->unset_userdata("tempStatusMember");
		$this->session->unset_userdata("tempStatusId");
		redirect(base_url('member/register'));
	}

	/**
	 * checkout
	 *
	 * description
	 *
	 * @return void
	 */
	public function checkout($isGroup = false)
	{
		$post = $this->input->post();
		$find =  ['id' => $post['id_invoice']];
		$this->load->model("Transaction_m");
		$this->session->set_userdata('transaksiFinish', '1');
		$this->session->unset_userdata("tempMemberId");
		$this->session->unset_userdata("tempStatusMember");
		$this->session->unset_userdata("tempStatusId");
		if ($post['paymentMethod'] == "espay") {
			$response['status'] = true;
			$response['message'] = "Transaksi selesai";
		} else if ($this->config->item("use_midtrans")) {
			$transaction = $this->Transaction_m->findOne($find);
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
						'phone' => $data['phone'],
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
			$transaction = $this->Transaction_m->findOne($find);
			$transaction->checkout = 1;
			$transaction->status_payment = Transaction_m::STATUS_PENDING;
			$transaction->channel = "MANUAL TRANSFER";
			$transaction->save();
			$response['status'] = true;
			$response['manual'] = $manual_payment;
			$this->load->model(["Member_m", "Notification_m"]);

			if ($isGroup) {
				foreach ($data['members'] as $key => $value) {
					$member = $this->Member_m->findOne(['id' => $value['id']]);
					$this->Notification_m->sendInvoice($member, $transaction);
				}
			} else {
				$member = $this->Member_m->findOne(['id' => $transaction->member_id]);
				$this->Notification_m->sendInvoice($member, $transaction);
			}
		}

		$this->output->set_content_type("application/json")
			->set_output(json_encode(['data' => $post, 'response' => $response]));
	}

	/**
	 * getTransactions
	 *
	 * Mengambil data transaksi
	 *
	 * @return void
	 */
	public function getTransactions($transaction)
	{
		$this->load->model(["Transaction_m"]);
		$transactions = $this->Transaction_m->getTransactionGroup($transaction->id);
		// $transactions = $this->Transaction_m->getTransactionGroup('INV-20211228-00011');
		$response = ['status' => true, 'cart' => null, 'transaction' => null];
		foreach ($transactions as $trans) {
			if ($trans->checkout == 0) {
				$response['current_invoice'] = $trans->id;
				$response['cart'][] = $trans;
			} else {
				$detail = [];
				foreach ($trans->details as $row) {
					$detail[] = $trans;
				}
				$response['transaction'][] = array_merge($trans->toArray(), ['detail' => $detail]);
			}
		}
		return $response;
	}

	public function redirect_client($name)
	{
		redirect(base_url("member/area#/$name"));
	}

	/**
	 * check_invoice
	 *
	 * Check Invoice Group
	 *
	 * @return void
	 */
	public function check_invoice($id_invoice = '')
	{
		if ($this->input->post()) {
			$this->load->library('form_validation');
			$this->form_validation->set_rules('invoice', 'Invoice', 'required');
			if ($this->form_validation->run()) {
				$this->load->model(['Transaction_m']);
				$post = $this->input->post();
				$transaction = $this->Transaction_m->findOne($post['invoice']);
				if ($transaction) {
					$transaction = $transaction->toArray();
					$transaction['description'] = Transaction_m::$transaction_status[$transaction['status_payment']];

					if ($transaction['status_payment'] == Transaction_m::STATUS_FINISH) {
						$transaction['status_payment'] = 'Completed';
					} else {
						$transaction['status_payment'] = ucwords(str_replace('_', ' ', $transaction['status_payment']));
					}

					$data['status'] = true;
					$data['transaction'] = $transaction;
					$data['message'] = '';
				} else {
					$data['status'] = false;
					$data['transaction'] = null;
					$data['message'] = 'No Transaction data';
				}
			} else {
				$data['status'] = false;
				$data['transaction'] = null;
				$data['message'] = '';
				$data['validation_error']['invoice'] = form_error('invoice');
			}

			$this->output->set_content_type("application/json")
				->set_output(json_encode($data));
		} else {
			$data = [
				'id_invoice' => $id_invoice,
				'hasSession' => $this->session->has_userdata("user_session"),
			];
			$this->layout->render('member/' . $this->theme . '/check_invoice', $data);
		}
	}

	public function upload_proof()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$post = $this->input->post();
		$transaction = json_decode($post['transaction'], true);
		$id = $transaction['id'];
		$message = $post["message"];

		$config['upload_path']          = APPPATH . 'uploads/proof/';
		$config['allowed_types']        = 'jpg|png|jpeg|pdf';
		$config['max_size']             = 2048;
		$config['overwrite']             = true;
		$config['file_name']        = $id;

		$this->load->library('upload', $config);
		if ($this->upload->do_upload('file_proof')) {
			$data = $this->upload->data();

			$this->load->model(["Transaction_m", "Notification_m", "Member_m"]);
			$tran = $this->Transaction_m->findOne($id);
			$tran->client_message = $message;
			$tran->payment_proof =  $data['file_name'];
			if ($tran->status_payment == Transaction_m::STATUS_PENDING) {
				$tran->status_payment = Transaction_m::STATUS_NEED_VERIFY;
				$data['status_payment'] =  Transaction_m::STATUS_NEED_VERIFY;
			} else {
				$data['status_payment'] = $tran->status_payment;
			}
			$mem = $this->Member_m->findOne($tran->member_id);
			$response['status'] = $tran->save();
			$response['data'] = $data;
			if ($response['status'] && Settings_m::getSetting("email_receive") != "") {
				$fullname = $mem ? $mem->fullname : $transaction['member_id'];
				$email_message = "$fullname has upload a transfer proof with invoice id <b>$tran->id</b>";
				$file[$data['file_name']] = file_get_contents(APPPATH . 'uploads/proof/' . $data['file_name']);
				$emails = explode(",", Settings_m::getSetting("email_receive"));
				foreach ($emails as $email) {
					$this->Notification_m->sendMessageWithAttachment($email, 'Notification Upload Transfer Proof', $email_message, $file);
				}
			}
		} else {
			$response['status'] = false;
			$response['message'] = $this->upload->display_errors("", "");
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function info_member_perdossi($nik = null)
	{
		$this->load->library('Api_perdossi');
		$response = $this->api_perdossi->getMemberByNIK($nik);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}
}
