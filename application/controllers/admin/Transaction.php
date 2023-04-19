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
		'new' => 'insert',
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
		$member = $this->Member_m->findOne(['id' => $tr->member_id]) ?? (object) ['fullname' => str_replace("REGISTER-GROUP : ", "", $tr->member_id), 'email' => $tr->email_group];
		if ($type == "invoice") {
			$result[] = $this->Notification_m->sendPaymentProof($member, $tr);
			$this->Notification_m->setType(Notification_m::TYPE_WA);
			$result[] = $this->Notification_m->sendInvoice($member, $tr);
		} elseif ($type == "proof") {
			$result[] = $this->Notification_m->sendPaymentProof($member, $tr);
			$this->Notification_m->setType(Notification_m::TYPE_WA);
			$result[] = $this->Notification_m->sendPaymentProof($member, $tr);
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
		if ($onlyHotel) {
			$gridConfig['filter'] = ['is_booking_hotel >' => '0'];
		}

		$grid = $this->Transaction_m->gridData($this->input->get(), $gridConfig);
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
			$this->db->insert("log_payment", [
				'invoice' => $data['id'],
				'action' => "save_modify",
				'request' => json_encode(array_intersect_key($data, array_flip(['id', 'status_payment', 'checkout', 'client_message']))),
				'response' => "[]",
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
				foreach ($data['details'] as $ind => $dt) {
					if (isset($dt['id'])) {
						$detail = $this->Transaction_detail_m->findOne(['id' => $dt['id']]);
						if ($dt['isDeleted'] == 1) {
							if ($detail)
								$detail->delete();
						} else {
							unset($dt['isDeleted']);
							$dt['product_name'] = preg_replace('/\[.*\]/', "", $dt['product_name']);
							$detail->setAttributes($dt);
							$detail->save();
						}
					} else {
						unset($dt['isDeleted']);
						$dt['product_name'] = preg_replace('/\[.*\]/', "", $dt['product_name']);
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

		$this->load->model(["Transaction_m", "Event_discount_m"]);
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		if ($detail) {
			$response['model'] = $detail->toArray();
			$response['model']['member'] = $detail->member ? $detail->member->toArray() : ['fullname' => 'Waiting Checkout', 'address' => "Not set"];
			$response['model']['member']['city_name'] = $detail->member && $detail->member->city_name ? $detail->member->city_name->nama : "";
			$group = $detail->member ? false : true;
			$tempDetails = [];
			foreach ($detail->details as $row) {
				$temp =  $row->toArray();
				$member = in_array($temp['event_pricing_id'], ['0', '-2']) ? [] : ($row->member ? $row->member->toArray() : []);
				$temp['isDeleted'] = 0;
				$tempDetails[] = array_merge(['member' => ['fullname' => $member['fullname'] ?? "", 'email' => $member['email'] ?? "",]], $temp);
			}
			usort($tempDetails, function ($a, $b) {
				return $b['product_name'] < $a['product_name'];
			});
			$response['model']['details'] = $tempDetails;
		}
		$current = count($response['model']['details']) > 0 ? current($response['model']['details']) : ['member_id' => '-'];
		$member_id = $group ? $current['member_id'] : $response['model']['member']['id'];
		$response['listEvent'] = array_merge($this->Transaction_m->getNotFollowedEvent($member_id), $this->Event_discount_m->getLikeEvent());

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
		$emailStatus = [];
		if ($member) {
			$emailStatus = $this->Notification_m->sendExpiredTransaction($member, $id);
			$status = $this->Notification_m->setType(Notification_m::TYPE_WA)
				->sendExpiredTransaction($member, $id);
		}
		$this->db->insert("log_payment", [
			'invoice' => $id,
			'action' => "set_expire",
			'request' => json_encode(array_intersect_key($this->input->post(), array_flip(['id', 'status_payment', 'checkout', 'client_message']))),
			'response' => "[]",
		]);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'email' => $emailStatus]));
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
			$this->db->insert("log_payment", [
				'invoice' => $id,
				'action' => "verify",
				'request' => json_encode(array_intersect_key($this->input->post(), array_flip(['id', 'status_payment', 'checkout', 'client_message']))),
				'response' => "[]",
			]);
			if ($member) {
				$this->Notification_m->sendPaymentProof($member, $detail);
				$this->Notification_m->setType(Notification_m::TYPE_WA)->sendPaymentProof($member, $detail);
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

	public function gl()
	{
		$this->load->helper("form");
		$this->load->model("Transaction_m");
		$this->layout->set_breadcrumb("Transaction Guarantee Letter");
		$this->layout->render('transaction_gl');
	}

	public function save_gl()
	{
		$this->load->model("Transaction_m");
		$id_invoice = $this->input->post("id");
		$data = $this->input->post('midtrans_data');
		$uploadStatus = true;
		$uploadStatusReceipt = true;
		$this->load->library("form_validation");
		$this->form_validation->set_rules('channel', 'Method Payment', 'required');
		if ($this->input->post("channel") == Transaction_m::CHANNEL_GL) {
			$this->form_validation->set_rules('midtrans_data[sponsorName]', 'Sponsor', 'required');
			$this->form_validation->set_rules('midtrans_data[payPlanDate]', 'Payment Plan Date', 'required');
		}

		$response = [
			'status' => true,
			'validation_error' => null,
		];
		$errorUpload = [];
		if (isset($_FILES['fileName']) && is_uploaded_file($_FILES['fileName']['tmp_name'])) {
			if (file_exists(APPPATH . 'uploads/guarantee_letter/') == false) {
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
			if (file_exists(APPPATH . 'uploads/guarantee_letter/') == false) {
				mkdir(APPPATH . 'uploads/guarantee_letter/');
			}
			$config['upload_path'] = APPPATH . 'uploads/guarantee_letter/';
			$config['allowed_types'] = 'jpg|png|jpeg|pdf';
			$config['max_size'] = 2048;
			$config['overwrite'] = true;
			$config['file_name'] = "RP-" . $id_invoice;
			if (isset($this->upload)) {
				$this->upload->initialize($config);
			} else {
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
		if ($uploadStatus && $uploadStatusReceipt && $this->form_validation->run()) {
			$data = [
				'channel' => $this->input->post('channel') ?? Transaction_m::CHANNEL_GL,
				'message_payment' => $this->input->post('message_payment') ?? "-",
				'midtrans_data' => json_encode($data),
			];
			if ($this->input->post("status_payment")) {
				$data['checkout'] = $this->input->post("status_payment") == Transaction_m::STATUS_WAITING ? "0" : "1";
				$data['status_payment'] = $this->input->post("status_payment");
			}
			$response['status'] = $this->Transaction_m->update($data, $id_invoice);
		} else {
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

	public function file_gl($name, $is_receipt = false)
	{
		$filepath = APPPATH . "uploads/guarantee_letter/" . $name;
		if (file_exists($filepath)) {
			$filename = $is_receipt ? "Receipt Payment-" . $name : "Guarantee Letter-" . $name;
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


	public function grid_gl()
	{
		$this->load->model('Transaction_m');

		$grid = $this->Transaction_m->gridDataGl($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function new()
	{
		$this->load->model("Transaction_m");
		$this->load->helper("form");
		$this->layout->render('new_transaction');
	}

	public function get_events()
	{
		ini_set('memory_limit', '2048M');
		$this->load->model(["Event_m", "Room_m", "Category_member_m"]);
		$memberId = $this->input->post("member_id");
		$status = $this->Category_member_m->findOne($this->input->post("status"));
		$events = $this->Event_m->eventVueModel($memberId, "all", ['show !=' => '3'], true);
		$booking = $this->Room_m->bookedRoom($memberId);
		$rangeBooking = $this->Room_m->rangeBooking();
		$this->output->set_content_type("application/json")
			->_display(json_encode(['status' => true, 'events' => $events, 'booking' => $booking, 'rangeBooking' => $rangeBooking]));
	}

	public function add_cart()
	{
		if ($this->input->method() !== 'post')
			show_404("Page not found !");

		$this->load->model(["Transaction_m", "Transaction_detail_m", "Event_m", "Event_pricing_m", "Member_m"]);

		$response = ['status' => true];

		$data = $this->input->post();
		$memberId = $this->input->post("memberId");
		$member = $this->Member_m->findOne($memberId);
		$memberStatus = $member->status_member;
		$transactionPost = $this->input->post("transaction");

		$this->Transaction_m->getDB()->trans_start();

		$transaction = null;
		if (isset($transactionPost['id'])) {
			$idTransaction = $transactionPost['id'];
			$transaction = $this->Transaction_m->findOne($transactionPost['id']);
		}
		if (!$transaction) {
			$idTransaction = $this->Transaction_m->generateInvoiceID();
			$transaction = new Transaction_m();
			$transaction->id = $idTransaction;
			$transaction->checkout = 0;
			$transaction->status_payment = Transaction_m::STATUS_WAITING;
			$transaction->member_id = $memberId;
			$transaction->midtrans_data = "{}";
			$transaction->save();
		}

		$detail = new Transaction_detail_m();

		// NOTE Check Required Events
		$valid = true;
		$message = null;

		if (!isset($data['is_hotel'])) {
			$findEvent = $this->Event_m->findOne(['id' => $data['event_id']]);
			if ($findEvent && $findEvent->event_required && $findEvent->event_required != "0") {
				$cek = $this->Event_m->getRequiredEvent($findEvent->event_required, $memberId);
				// NOTE Data Required Event
				$dataEvent = $this->Event_m->findOne(['id' => $findEvent->event_required]);
				if ($cek) {
					if ($cek->status_payment == Transaction_m::STATUS_FINISH) {
						$valid = true;
					} else if (in_array($cek->status_payment, [Transaction_m::STATUS_PENDING])) {
						$valid = false;
						$message = "Not Available, please complete the payment !";
					}
				} else {
					$valid = false;
					$message = "You must follow event {$dataEvent->name} to patcipate this event !";
				}
			}

			if ($this->Event_m->validateFollowing($data['id'],"all") && $valid) {

				// NOTE Harga sesuai dengan database
				$price = $this->Event_pricing_m->findWithProductName(['id' => $data['id']]);
				if ($price->price != 0) {
					$data['price'] = $price->price;
				} else {
					$kurs_usd = json_decode(Settings_m::getSetting('kurs_usd'), true);
					$data['price'] = ($price->price_in_usd * $kurs_usd['value']);
				}

				$detail->event_pricing_id = $data['id'];
				$detail->transaction_id = $idTransaction;
				$detail->price = $data['price'];
				$detail->price_usd = $price->price_in_usd;
				$detail->member_id = $memberId;
				$detail->product_name = $price->product_name;
				$detail->save();
			} else {
				$response['status'] = false;
				$response['message'] = $message ?? "You are prohibited from following !";
			}
		} else {
			$result = $this->Transaction_detail_m->bookHotel($idTransaction, $memberId, $data);
			$data['price'] = 0;
			if ($result === true) {
				$data['price'] = 1;
				$response['status'] = true;
			} else {
				$response['status'] = false;
				$response['message'] = $result;
			}
		}
		$transactionArray = $transaction->toArray();
		$transactionArray['id'] = $idTransaction;
		$transactionArray['midtrans_data'] = json_decode($transactionArray['midtrans_data']);
		$response['transaction'] = $transactionArray;
		$response['transaction_details'] = $this->Transaction_detail_m->find()->where(['transaction_id' => $idTransaction])->get()->result_array();
		$this->Transaction_m->setDiscount($idTransaction);
		$this->Transaction_m->getDB()->trans_complete();

		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function delete_item()
	{
		if ($this->input->method() !== 'post')
			show_404("Page not found !");
		$id = $this->input->post('id');
		$this->load->model(["Transaction_detail_m", "Transaction_m"]);
		$this->Transaction_detail_m->delete($id);
		$count = $this->Transaction_detail_m->find()->select("SUM(price) as c")
			->where('transaction_id', $this->input->post("transaction_id"))
			->where('event_pricing_id != ', "0")
			->get()->row_array();
		if ($count['c'] == 0) {
			$this->Transaction_detail_m->delete(['event_pricing_id' => 0, 'transaction_id' => $this->input->post("transaction_id")]);
		}
		$this->Transaction_m->setDiscount($this->input->post("transaction_id"));
		$this->output->set_content_type("application/json")
			->_display('{"status":true}');
	}

	public function copy()
	{
		$response = [
			'status' => false,
			'message' => 'Transactions cannot be copied, because its status is not expired'
		];
		$id = $this->input->post("id");
		$this->load->model(["Transaction_detail_m", "Transaction_m"]);
		$transaction = $this->Transaction_m->findOne($id);
		if ($transaction && $transaction->status_payment == Transaction_m::STATUS_EXPIRE) {
			$newTransaction = $transaction->toArray();
			$newTransaction['id'] = $this->Transaction_m->generateInvoiceId();
			$newTransaction['checkout'] = 0;
			$newTransaction['status_payment'] = Transaction_m::STATUS_WAITING;
			$newTransaction['message_payment'] = "";
			$newTransaction['midtrans_data'] = "";
			unset($newTransaction['paymentGatewayInfo']);
			$this->Transaction_m->getDB()->trans_start();
			$this->Transaction_m->insert($newTransaction);
			foreach ($transaction->details as $detail) {
				$newDetail = $detail->toArray();
				$newDetail['transaction_id'] = $newTransaction['id'];
				unset($newDetail['id']);
				$this->Transaction_detail_m->insert($newDetail);
			}
			$this->Transaction_m->getDB()->trans_complete();
			$status = $this->Transaction_m->getDB()->trans_status();
			if ($status) {
				$response['status'] = true;
				$response['message'] = "Transaction successfully copied.<br/> New invoice id is <b>'{$newTransaction['id']}'</b>";
			} else {
				$response['message'] = "Failed to copy transaction";
			}
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}
}
