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
		$member = $this->Member_m->findOne(['id' => $tr->member_id]);
		if ($type == "invoice") {
			$filename = $member->fullname . "-Invoice.pdf";
			$file = $tr->exportInvoice();
		} elseif ($type == "proof") {
			$filename = $member->fullname . "-Bukti_Registrasi.pdf";
			$file = $tr->exportPaymentProof();
		}
		$message = "<p>Dear Participant</p>
					<p>Thank you for participating in the event. Please download your 'Registration Proof' here.</p>
					<p>Best regards.<br/>
					Committee of " . Settings_m::getSetting('site_title') . "</p>";
		$result = $this->Notification_m->sendMessageWithAttachment($member->email, 'Resend : Registration Proof', $message, [$filename => $file->output()]);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $result]));
	}

	public function download($type, $id)
	{
		$this->load->model(['Transaction_m', 'Member_m']);
		$tr = $this->Transaction_m->findOne(['id' => $id]);
		$member = $this->Member_m->findOne(['id' => $tr->member_id]);
		if ($type == "invoice")
			$tr->exportInvoice()->stream($member->fullname . "-Invoice.pdf", array("Attachment" => false));
		elseif ($type == "proof")
			$tr->exportPaymentProof()->stream($member->fullname . "-Registration_proof.pdf", array("Attachment" => false));
		else
			show_404();
	}

	public function grid()
	{
		$this->load->model('Transaction_m');

		$grid = $this->Transaction_m->gridData($this->input->get());
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
			$trans->status_payment = $data['status_payment'];
			if ($trans->status_payment != Transaction_m::STATUS_WAITING) {
				$trans->checkout = 1;
			} else {
				$trans->checkout = 0;
			}
			$trans->getDb()->trans_start();
			$trans->save();
			if (isset($data['details'])) {
				foreach ($data['details'] as $dt) {
					if (isset($dt['id'])) {
						$detail = $this->Transaction_detail_m->findOne(['id' => $dt['id']]);
						if ($dt['isDeleted'] == 1) {
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

		$this->load->model("Transaction_m");
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		if ($detail) {
			$response['model'] = $detail->toArray();
			$response['model']['member'] = $detail->member ? $detail->member->toArray() : [];
			$group = $detail->member ? false : true;
			$response['model']['details'] = [];
			foreach ($detail->details as $row) {
				$temp =  $row->toArray();
				$member = $temp['product_name'] == 'Unique Additional Price + Admin Fee' ? [] : $row->member->toArray();

				$temp['isDeleted'] = 0;
				$response['model']['details'][] = array_merge(['member' => $member], $temp);
			}
		}
		$member_id = $group ? current($response['model']['details'])['member_id'] : $response['model']['member']['id'];
		$response['listEvent'] = $this->Transaction_m->getNotFollowedEvent($member_id);

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
		$status = $detail->save();
		if ($status) {
			$member = $this->Member_m->findOne(['id' => $detail->member_id]);
			$attc = [
				$member->fullname . '-invoice.pdf' => $detail->exportInvoice()->output(),
				$member->fullname . '-registration_proof.pdf' => $detail->exportPaymentProof()->output()
			];
			$details = $detail->detailsWithEvent();
			foreach ($details as $row) {
				if ($row->event_name) {
					$event = [
						'name' => $row->event_name,
						'held_on' => $row->held_on,
						'held_in' => $row->held_in,
						'theme' => $row->theme
					];
					if (env('send_card_member', '1') == '1' && false) {
						try {
							$attc[$member->fullname . "_" . $row->event_name . ".pdf"] = $member->getCard($row->event_id)->output();
						} catch (ErrorException $exception) {
							log_message("error", $exception->getMessage());
						}
					}
				}
			}
			$this->Notification_m->sendMessageWithAttachment($member->email, 'Invoice, Payment Proof', "Thank you for registering and fulfilling your payment, below is your invoice and official Payment Proof", $attc);
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
}
