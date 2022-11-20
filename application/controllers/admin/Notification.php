<?php


class Notification extends Admin_Controller
{
	const TYPE_SENDING_NAME_TAG = "nametag";
	const TYPE_SENDING_CERTIFICATE = "certificate";
	const TYPE_SENDING_CERTIFICATE_COM = "certificate_committe";
	const TYPE_SENDING_MESSAGE = "message";
	const TYPE_SENDING_MATERIAL = "material";

	protected $accessRule = [
		'index' => 'view',
		'send_cert' => 'insert',
		'send_material' => 'insert',
		'get_file_material' => 'view',
		'remove_material' => 'delete',
		'get_material' => 'view',
		'material_upload' => 'insert',
		'send_message' => 'insert',
	];

	public function index($send_to_person = null)
	{
		$this->load->model(["Event_m", "Member_m", "Category_member_m", "Transaction_m", "Category_paper_m"]);
		$this->load->helper('form_helper');
		$eventList = $this->Event_m->find()->select("id,name as label")->get()->result_array();
		$statusList = $this->Category_member_m->find()->select("id,kategory as label")->get()->result_array();
		foreach ($this->Category_paper_m->findAll() as $row) {
			$eventList[] = ['id' => 'Paper;' . $row->id, 'label' => 'Paper Participant (' . $row->name . ')'];
		}
		$memberList = $this->Member_m->find()->select("id,CONCAT(fullname,' (',email,')') as label")->get()->result_array();

		$transactionStatus = [
			$this->Transaction_m::STATUS_FINISH => str_replace("_", " ", ucwords($this->Transaction_m::STATUS_FINISH)),
			$this->Transaction_m::STATUS_WAITING => str_replace("_", " ", ucwords($this->Transaction_m::STATUS_WAITING)),
			$this->Transaction_m::STATUS_PENDING => str_replace("_", " ", ucwords($this->Transaction_m::STATUS_PENDING)),
			$this->Transaction_m::STATUS_UNFINISH => str_replace("_", " ", ucwords($this->Transaction_m::STATUS_UNFINISH)),
			$this->Transaction_m::STATUS_EXPIRE => str_replace("_", " ", ucwords($this->Transaction_m::STATUS_EXPIRE)),
			$this->Transaction_m::STATUS_DENY => str_replace("_", " ", ucwords($this->Transaction_m::STATUS_DENY)),
			$this->Transaction_m::STATUS_NEED_VERIFY => str_replace("_", " ", ucwords($this->Transaction_m::STATUS_NEED_VERIFY)),
		];

		$data = [
			'event' => $eventList,
			'memberList' => $memberList,
			'statusList' => $statusList,
			'send_to_person' => $send_to_person,
			'transactionStatus' => $transactionStatus,
		];

		$this->layout->render("notification", $data);
	}

	public function send_cert($preparing = null)
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(['Event_m', 'Settings_m', 'Papers_m']);
		if ($preparing) {
			$id = $this->input->post("id");
			$expl = explode(";", $id);
			$id = $expl[0] ?? $id;
			if (Settings_m::getSetting("config_cert_$id") != "" && file_exists(APPPATH . "uploads/cert_template/$id.txt")) {
				if ($id == "Paper") {
					$result = $this->Papers_m->certificateReciver("Participant", $expl[1] ?? null);
				} else {
					$result = $this->Event_m->getParticipant()->where('t.id', $id)->get()->result_array();
				}
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => true, 'data' => $result]));
			} else {
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => "Template of certificate is not found !"]));
			}
		} else {
			$this->load->model(["Notification_m", "Member_m", "Event_m", "Papers_m"]);
			$status = [];
			if ($this->input->post("isPaper")) {
				$member = $this->input->post();
				$cert = $this->Papers_m->exportCertificate($member)->output();
				$message = $this->load->view("template/email/send_certificate_paper", [
					'event_name' => 'Manuscript'
				], true);
				$status = $this->Notification_m->sendMessageWithAttachment($member['email'], "Certificate of Manuscript", $message, $cert, "CERTIFICATE.pdf");
			} else {
				$status = [];
				$post = $this->input->post();
				$member = $this->Event_m->getParticipant()->where("m.id", $post["m_id"])->where("t.id", $post['event_id'])->get()->row_array();
				// $member = $this->Member_m->findOne($post['m_id']);
				// if($member->email == "muhammad.zaien17@gmail.com"){
				$cert = $this->Event_m->exportCertificate($member,  $post['event_id'])->output();
				$status['wa'] = $this->Notification_m->setType(Notification_m::TYPE_WA)->sendCertificate($member, Notification_m::CERT_TYPE_EVENT, $post['event_name'], $cert);
				$status['email'] = $this->Notification_m->sendCertificate($member, Notification_m::CERT_TYPE_EVENT, $post['event_name'], $cert);
				$status['status'] = $status['email']['status'];
				// }else{
				// 	$status['status'] = true;
				// }
			}

			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => $status['status'], 'log' => $status]));
		}
	}

	public function send_cert_com($preparing = null)
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(['Event_m', 'Committee_attributes_m', 'Notification_m']);
		if ($preparing) {
			$id = $this->input->post("id");
			if (Settings_m::getSetting("config_cert_$id") != "" && file_exists(APPPATH . "uploads/cert_template/$id.txt")) {
				$result = $this->Committee_attributes_m->find()
					->join('committee', 'committee.id = committee_id')
					->join("events", "events.id = event_id")
					->select('email,committee_attribute.id,events.id as event_id,events.name as event_name')
					->where('event_id', $id)->get();
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => true, 'data' => $result->result_array()]));
			} else {
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => "Template of certificate is not found !"]));
			}
		} else {
			$com = $this->Committee_attributes_m->findOne($this->input->post("id"));
			$commiteMember = $com->committee;
			$commiteMember->phone = $commiteMember->no_contact;
			$commiteMember->fullname = $commiteMember->name;
			$cert = $com->exportCertificate()->output();
			if ($commiteMember->email) {
				$status = $this->Notification_m->sendCertificate($commiteMember, Notification_m::CERT_TYPE_EVENT, $com->event->name, $cert);
			}
			if ($commiteMember->no_contact) {
				$status = $this->Notification_m->setType(Notification_m::TYPE_WA)->sendCertificate($commiteMember, Notification_m::CERT_TYPE_EVENT, $com->event->name, $cert);
			}
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => $status['status'], 'log' => $status]));
		}
	}

	public function send_material($preparing = null)
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(['Event_m']);
		if ($preparing) {
			$id = $this->input->post("id");
			$event = $this->Event_m->findOne(['id' => $id]);
			$material = json_decode($event->material, true);
			if (!$material)
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => "File material is not exist !"]));
			else {
				$result = $this->Event_m->getParticipant()->where('t.id', $id)->get();
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => true, 'data' => $result->result_array()]));
			}
		} else {
			$this->load->model("Notification_m");
			$member = $this->input->post();
			$event = [
				'id' => $member['event_id'],
				'name' => $member['event_name']
			];
			$event = $this->Event_m->findOne(['id' => $event['id']]);
			$material = json_decode($event->material, true);
			foreach ($material as $name => $file) {
				$material_file[$name] = file_get_contents(APPPATH . "uploads/material/" . $file['name']);
			}
			$status = $this->Notification_m->sendMessageWithAttachment($member['email'], "Material of Event", "Thank you for your participation <br/> Below is your material of " . $event['name'], $material_file);
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => $status['status'], 'log' => $status]));
		}
	}

	public function get_file_material($name)
	{
		$filepath = APPPATH . "uploads/material/" . $name;
		if (file_exists($filepath)) {
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="' . $name . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		}
	}

	public function remove_material()
	{
		$post = $this->input->post();
		$this->load->model("Event_m");
		$event = $this->Event_m->findOne(['id' => $post['id']]);
		$material = json_decode($event->material, true);
		if (!$material)
			$material = [];
		unset($material[$post['name']]);
		$event->material = json_encode($material);
		if ($event->save()) {
			unlink(APPPATH . "uploads/material/" . $post['name']);
			$this->output->set_content_type("application/json")
				->_display(json_encode(['files' => $material]));
		} else
			$this->output->set_status_header(500);
	}

	public function get_material()
	{
		$event_id = $this->input->post("event_id");
		$this->load->model("Event_m");
		$event = $this->Event_m->findOne(['id' => $event_id]);
		$material = json_decode($event->material, true);
		if (!$material)
			$material = [];
		$this->output->set_content_type("application/json")
			->_display(json_encode(['files' => $material]));
	}

	public function material_upload()
	{
		$this->load->library('upload', [
			'allowed_types' => '*',
			'upload_path' => APPPATH . 'uploads/material/'
		]);
		$event_id = $this->input->post("event_id");
		if ($event_id == "") {
			$this->output->set_status_header(500)
				->set_content_type("application/json")
				->_display(json_encode(['message' => "Failed to upload : Event not selected"]));
			exit;
		}
		$upload = $this->upload->do_upload('file');
		if ($upload) {
			$this->load->model("Event_m");
			$data = $this->upload->data();
			$event = $this->Event_m->findOne(['id' => $event_id]);
			$material = json_decode($event->material, true);
			if (!$material)
				$material = [];

			$file_response = [
				'name' => $data['file_name'],
				'size' => $data['file_size'],
				'speed' => '0',
				'url' => base_url("admin/notification/get_file_material/$data[file_name]")
			];
			$material[$data['file_name']] = $file_response;
			$event->material = json_encode($material);
			$event->save();
			$this->output->set_content_type("application/json")
				->_display(json_encode(['message' => 'Success', 'data' => $file_response]));
		} else {
			$this->output->set_status_header(500)
				->set_content_type("application/json")
				->_display(json_encode(['message' => $this->upload->display_errors("", "")]));
		}
	}

	public function send_message()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$data = $this->input->post();

		$data['text'] = $this->input->post('text', false);
		$this->load->model(["Member_m", "Transaction_m"]);
		$to = [];
		$type = $this->input->post("target");
		if ($data['target'] == "all") {
			$res = $this->Member_m->find()->get();
		} elseif ($data['target'] == 'event_selected') {
			if ($data['to'] == 'Paper') {
				$this->load->model("Papers_m");
				$res = $this->Papers_m->getParticipant()->distinct("m.id")->get();
			} else {
				$this->load->model("Event_m");
				$res = $this->Event_m->getParticipant()->where("t.id", $data['to'])->get();
			}
		} elseif ($data['target'] == 'selected_status') {
			$res = $this->Member_m->find()->where("status", $data['to'])->get();
		} elseif ($data['target'] == 'member') {
			$res = $this->Member_m->findOne($data['to']);
			$to['email'][] = $res->email;
			$to['wa'][] = $res->phone;
		} elseif ($data['target'] == "pooling") {
			$to = $this->input->post("to");
		} elseif ($data['target'] == "selected_transaction_status") {
			$res = $this->Transaction_m->getMember(["status_payment" => $data['to']]);
		}

		$status = true;
		$responseEmail = [];
		if ($type == "member" || $type == "pooling") {
			$this->load->model("Notification_m");
			$responseEmail['status'] = true;
			if (in_array("email", $data['via'])) {
				$this->Notification_m->setType(Notification_m::TYPE_EMAIL);
				foreach ($to['email'] as $receiver) {
					$responseEmail = $this->Notification_m->sendMessage($receiver, $data['subject'], $data['text']);
				}
			}
			$responseWa['status'] = true;
			if (in_array("wa", $data['via'])) {
				$this->Notification_m->setType(Notification_m::TYPE_WA);
				foreach ($to['wa'] as $receiver) {
					$responseWa = $this->Notification_m->sendMessage($receiver, $data['subject'], $data['text']);
				}
			}
			$status = ($responseEmail['status'] && $responseWa['status']);
		} elseif (isset($res)) {
			foreach ($res->result() as $row) {
				$to[] = ['to' => ['email' => [$row->email], 'wa' => [$row->phone]], 'subject' => $data['subject'], 'text' => $data['text'], 'via' => $data['via'], 'target' => 'pooling'];
			}
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'type' => $type, 'data' => $to, 'detail' => $responseEmail]));
	}

	public function init_broadcast()
	{
		$this->load->library('Uuid');

		$type = $this->input->post("type");
		$subject = $this->input->post("subject");
		$message = $this->input->post("message");
		$channel = $this->input->post("channel");
		$attributes = [];
		$id = Uuid::v4();
		switch ($type) {
			case self::TYPE_SENDING_NAME_TAG:
				$this->load->model("Event_m");
				$event_id = $this->input->post("event_id");
				$participants = $this->Event_m->getParticipant()->where("t.id", $event_id)->get()->result();
				$ind = 1;
				foreach ($participants as $row) {
					$attributes[] = ['id' => $ind++, 'member_id' => $row->m_id, 'event_id' => $event_id];
				}
				break;
			case self::TYPE_SENDING_CERTIFICATE:
				$this->load->model("Event_m");
				$event_id = $this->input->post("event_id");
				if ($event_id == "Paper") {
					$this->load->model("Papers_m");
					$attributes = $this->Papers_m->certificateReciver("Participant", $expl[1] ?? null);
				} else {
					$attributes = $this->Event_m->getParticipant()->where('t.id', $event_id)->get()->result_array();
				}
				break;
			case self::TYPE_SENDING_CERTIFICATE_COM:
				break;
			case self::TYPE_SENDING_MESSAGE:
				break;
			case self::TYPE_SENDING_MATERIAL:
				break;
		}

		if (!file_exists(APPPATH . "cache/broadcast")) {
			mkdir(APPPATH . "cache/broadcast");
		}
		$filename = APPPATH . "cache/broadcast/".$id . ".json";
		if (!$fp = fopen($filename, 'a')) {
			$status = false;
			$responseMessage = "Cannot write a attribute file";
		} else {
			$status = $this->db->insert("broadcast", [
				'id' => $id,
				'type' => $type,
				'subject' => $subject,
				'message' => $message,
				'channel' => $channel,
				'attribute' => $filename,
				'status' => 'Ready',
			]);
			foreach ($attributes as $row) {
				fwrite($fp, json_encode($row).PHP_EOL);
			}
			fclose($fp);
		}
		run_job("job", "run_broadcast", [$id]);
		$responseMessage = "<p style='font-size:24px'>Broadcast berhasil dimulai</p> 
							ID  : $id <br/>	Jumlah penerima : " . count($attributes) . "<br/>
							Ikuti link berikut untuk monitoring <a href='" . base_url('admin/notification/history/' . $id) . "' target='_blank'>Klik Disini</a>";
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $responseMessage, 'countReceiver' => count($attributes), 'id' => $id]));
	}

	public function history($id = "")
	{
		$this->layout->render("broadcast", ['id' => $id]);
	}

	public function grid()
	{
		$this->load->model('Broadcast_m');

		$grid = $this->Broadcast_m->gridData($this->input->get(), ['select' => ['t_id' => 't.id', 'id', 'created_at', 'subject', 'status', 'channel']]);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function retry($id)
	{
		$status = run_job("job", "run_broadcast", [$id]);
		$this->db->update("broadcast", ['status' => 'Ready'], ['id' => $id]);

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function detail_history($id)
	{
		$this->load->model('Broadcast_m');
		$row = $this->Broadcast_m->findOne($id);
		$data = $row->toArray();
		$data['attribute'] = [];

		if(file_exists(APPPATH . "cache/broadcast/".$id."-result.json")){
			$resultFile = fopen(APPPATH . "cache/broadcast/".$id."-result.json", 'r');
			while (!feof($resultFile)) {
				$rowRaw = fgets($resultFile); 
				if($rowRaw != false)
					$data['attribute'][] = json_decode($rowRaw);
			}
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($data));
	}
}
