<?php


class Notification extends Admin_Controller
{
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
		$this->load->model(["Event_m", "Member_m"]);
		$this->load->helper('form_helper');
		$eventList = $this->Event_m->find()->select("id,name as label")->get()->result_array();
		$eventList[] = ['id' => 'Paper', 'label' => 'Paper Participant'];
		$memberList = $this->Member_m->find()->select("id,CONCAT(fullname,' (',email,')') as label")->get()->result_array();
		$this->layout->render("notification", [
			'event' => $eventList,
			'memberList' => $memberList,
			'send_to_person' => $send_to_person,
		]);
	}

	public function send_cert($preparing = null)
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(['Event_m', 'Settings_m']);
		if ($preparing) {
			$id = $this->input->post("id");
			if (Settings_m::getSetting("config_cert_$id") != "" && file_exists(APPPATH . "uploads/cert_template/$id.txt")) {
				$result = $this->Event_m->getParticipant()->where('t.id', $id)->get();
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => true, 'data' => $result->result_array()]));
			} else {
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => "Template of certificate is not found !"]));
			}
		} else {
			$this->load->model("Gmail_api");
			$member = $this->input->post();
			$event = [
				'id' => $member['event_id'],
				'name' => $member['event_name']
			];
			$member['status_member'] = "Peserta";
			$cert = $this->Event_m->exportCertificate($member, $event['id'])->output();
			$status = $this->Gmail_api->sendMessageWithAttachment($member['email'], "Certificate of Event", "Thank you for your participation <br/> Below is your certificate of '" . $event['name'] . "'", $cert, "CERTIFICATE.pdf");
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => true, 'log' => $status]));
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
			$this->load->model("Gmail_api");
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
			$status = $this->Gmail_api->sendMessageWithAttachment($member['email'], "Material of Event", "Thank you for your participation <br/> Below is your material of " . $event['name'], $material_file);
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => true, 'log' => $status]));
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
		$this->load->model("Member_m");
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
		} elseif ($data['target'] == 'member') {
			$res = $this->Member_m->findOne($data['to']);
			$to['email'][] = $res->email;
			$to['wa'][] = $res->phone;
		} elseif ($data['target'] == "pooling") {
			$to = $this->input->post("to");
		}
		if ($type == "member" || $type == "pooling") {
			if (in_array("email", $data['via'])) {
				$this->load->model("Gmail_api");
				foreach ($to['email'] as $receiver) {
					$this->Gmail_api->sendMessage($receiver, $data['subject'], $data['text']);
				}
			}

			if (in_array("wa", $data['via'])) {
				$this->load->model("Whatsapp_api");
				foreach ($to['wa'] as $receiver) {
					$this->Whatsapp_api->sendMessage($receiver, $data['subject'], $data['text']);
				}
			}
		} elseif (isset($res)) {
			foreach ($res->result() as $row) {
				$to[] = ['to' => ['email' => [$row->email], 'wa' => [$row->phone]], 'subject' => $data['subject'], 'text' => $data['text'], 'via' => $data['via'],'target'=>'pooling'];
			}
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => true, 'type' => $type, 'data' => $to]));
	}

}
