<?php


use Dompdf\Dompdf;

class Setting extends Admin_Controller
{
	protected $accessRule = [
		"index" => "view",
		"preview_cert" => "view",
		"get_cert" => "view",
		"save_cert" => "update",
		"preview_nametag" => "view",
		"get_nametag" => "view",
		"save_nametag" => "update",
		"unbind_email" => "update",
		"save_manual" => "update",
		"save_token_wa" => "update",
		"token_auth" => "update",
		"request_auth" => "update",
		"upload_logo" => "update",
		"save" => "update",
	];

	public function index()
	{
		$this->load->helper("form");
		$this->load->model(['Notification_m', "Event_m"]);
		$gmail_token = $this->Notification_m->getValue(Notification_m::SETTING_GMAIL_TOKEN, true);
		$this->load->library("Gmail_api",['token'=>$gmail_token]);
		$manual = Settings_m::getSetting(Settings_m::MANUAL_PAYMENT);
		
		$this->layout->render('setting', [
			'wa_token' => $this->Notification_m->getValue(Notification_m::SETTING_WA_TOKEN),
			'email_binded' => $gmail_token == null || $this->gmail_api->getClient()->isAccessTokenExpired() ? 0 : 1,// (is_array($gmail_token) && count($gmail_token) > 0) ? 1 : 0,
			"event" => $this->Event_m->findAll(),
			'manual' => ($manual == "" || $manual == "null" ? "[]" : $manual)
		]);
	}

	public function preview_cert($id)
	{
		$this->load->model(["Event_m","Papers_m"]);
		$configuration = json_decode(Settings_m::getSetting("config_cert_$id"), true);
		$data['id'] = "-";
		if($configuration){
			if(isset($configuration['property']) && is_array($configuration['property'])){
				foreach ($configuration['property'] as $field) {
					$data[$field['name']] = "Preview $field[name]";
				}
			}
		}
		if($id == "Paper"){
			$data['id_paper'] = "SAMPLE";
			$this->Papers_m->exportCertificate($data, $id)->stream('preview_cert.pdf', array('Attachment' => 0));
		}else{
			$this->Event_m->exportCertificate($data, $id)->stream('preview_cert.pdf', array('Attachment' => 0));
		}
	}

	public function get_cert()
	{
		$id = $this->input->post('id');
		$config = Settings_m::getSetting("config_cert_$id");
		if (file_exists("./application/uploads/cert_template/$id.txt")) {
			$return['fileName'] = "Select Image as Template";
			$return['body'] = ['width' => '100%'];

			$configuration = json_decode($config, true);
			$return['property'] = $configuration['property'] ?? [];
			if(!isset($configuration['anotherPage'])){
				$return['anotherPage'] = [];
				if(file_exists("./application/uploads/cert_template/second_page_$id.txt")){
					$return['anotherPage'][] = [
						'filename'=>"Second Page.jpg",
						'image'=>file_get_contents("./application/uploads/cert_template/second_page_$id.txt")
					];
				}
			}else{
				$return['anotherPage'] = $configuration['anotherPage'];
				foreach($return['anotherPage'] as $ind=>$row){
					if(file_exists($row['image'])){
						$return['anotherPage'][$ind]['image'] = file_get_contents($row['image']);
					}else{
						$return['anotherPage'][$ind]['image'] = null;
					}
				}
			}
			$return['image'] = file_get_contents("./application/uploads/cert_template/$id.txt");
			$return['base64Image'] = $return['image'];
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => true, 'data' => $return]));
		} else {
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => false]));
		}
	}
	public function save_cert()
	{
		file_put_contents("./application/uploads/cert_template/$_POST[event].txt", $_POST['base64Image']);
		$configuration['property'] =  $this->input->post('property');
		if (!$configuration)
			$configuration = [];

		if($this->input->post("anotherPage")){
			foreach($this->input->post("anotherPage",false) as $ind=>$row){
				if(isset($row['image']) && $row['image'] != null){
					$imagePath = "./application/uploads/cert_template/another_page_{$ind}_{$_POST['event']}.txt";
					$configuration['anotherPage'][] = [
						'filename'=>"Page ".($ind+2).".jpg",
						'image'=>$imagePath,
					];
					file_put_contents($imagePath, $row['image']);
				}
			}
		}
		Settings_m::saveSetting("config_cert_$_POST[event]", $configuration);

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => true]));
	}

	public function remove_second_image(){
		$id = $this->input->post("id");
		$status = false;
		if(file_exists("./application/uploads/cert_template/second_page_$id.txt")){
			$status = unlink("./application/uploads/cert_template/second_page_$id.txt");
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function preview_nametag($id)
	{
		$this->load->model("Member_m");
		$propery = json_decode(Settings_m::getSetting("config_nametag_$id"), true);
		$data = ['qr' => '', 'fullname' => '', 'status_member' => ''];
		foreach ($propery as $field) {
			$data[$field['name']] = "Preview $field[name]";
		}
		$this->Member_m->getCard($id, $data)->stream('preview_nametag.pdf', array('Attachment' => 0));
	}

	public function get_nametag()
	{
		$id = $this->input->post('id');
		$config = Settings_m::getSetting("config_nametag_$id");
		if (file_exists("./application/uploads/nametag_template/$id.txt")) {
			$return['fileName'] = "Select Image as Template";
			$return['body'] = ['width' => '100%'];
			$return['property'] = json_decode($config, true);
			if ($return['property'] == null)
				$return['property'] = [];
			$return['image'] = file_get_contents("./application/uploads/nametag_template/$id.txt");
			$return['base64Image'] = $return['image'];
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => true, 'data' => $return]));
		} else {
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status' => false]));
		}
	}
	public function save_nametag()
	{
		file_put_contents("./application/uploads/nametag_template/$_POST[event].txt", $_POST['base64Image']);
		$property =  $this->input->post('property');
		if (!$property)
			$property = [];
		Settings_m::saveSetting("config_nametag_$_POST[event]", $property);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => true]));
	}

	public function unbind_email()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Notification_m");
		$this->output
			->set_content_type("application/json")
			->_display(json_encode([
				'status' => $this->Notification_m->setValue(Notification_m::SETTING_GMAIL_TOKEN, ""),
				'email' => $this->Notification_m->setValue(Notification_m::SETTING_GMAIL_ADMIN, ""),
			]));
	}

	/**
	 * currency
	 *
	 * description
	 *
	 * @return void
	 */
	public function currency($cur = 'IDR')
	{
		$url = 'https://v6.exchangerate-api.com/v6/d5df9d9fb4d6a9589f669530/latest/USD';

		$curl_handle = curl_init();
		curl_setopt($curl_handle, CURLOPT_URL, $url);
		curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
		$query = curl_exec($curl_handle);
		curl_close($curl_handle);

		$query = json_decode($query, true);

		if ($query['result'] == 'success') {
			$response = [
				'code' => 200,
				'data' => [
					$cur => round($query['conversion_rates'][$cur]),
				],
				'message' => '',
			];
		} else {
			$response = [
				'code' => 400,
				'data' => null,
				'message' => 'Server ggal merespon',
			];
		}

		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function save_manual()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$espay = ($this->input->post("espay") ? $this->input->post("espay") : []);
		$enablePayment = ($this->input->post("enablePayment") ? $this->input->post("enablePayment") : []);

		$email = $this->input->post("manualPayment[emailReceive]");
		$banks = $this->input->post("manualPayment[banks]");

		$kurs_usd = ($this->input->post("kurs_usd") ? $this->input->post("kurs_usd") : 0);
		$kurs_usd['using_api'] = $kurs_usd['using_api'] == 'true' ? 1 : 0;

		$message = [];

		Settings_m::saveSetting(Settings_m::MANUAL_PAYMENT, json_encode($banks));
		Settings_m::saveSetting("email_receive", $email);
		Settings_m::saveSetting("kurs_usd", $kurs_usd);

		if (isset($espay) && in_array("espay;Online Payment", $enablePayment)) {
			$this->load->library("form_validation");
			$this->form_validation->set_rules("apiLink", "Api Link", "required")
				->set_rules("jsKitUrl", "JS KIT URL", "required")
				->set_rules("merchantCode", "Merchant Code", "required")
				->set_rules("apiKey", "Api Key", "required")
				->set_rules("signature", "Signature", "required");
			$this->form_validation->set_data($espay);
			if ($this->form_validation->run()) {
				Settings_m::saveSetting(Settings_m::ESPAY, json_encode($espay));
			} else {
				$message[] = $this->form_validation->error_string();
			}
		}
		if (count($message) == 0)
			Settings_m::saveSetting(Settings_m::ENABLE_PAYMENT, json_encode($enablePayment));

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => count($message) == 0, 'message' => implode("", $message)]));
	}
	public function save_mailer()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model("Notification_m");
		$type = $this->input->post("type");
		$mailer = $this->input->post("mailer");
		$status = $this->Notification_m->setValue(Notification_m::SETTING_MAILER, json_encode($mailer)) &&
			$this->Notification_m->setDefaultMailer($type);

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function save_token_wa()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Notification_m");
		$token = $this->input->post("token");

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $this->Notification_m->setValue(Notification_m::SETTING_WA_TOKEN, $token)]));
	}

	public function token_auth()
	{
		$this->load->model("Notification_m");
		$this->load->library('Gmail_api');
		$code = $this->input->get('code');
		$client = $this->gmail_api->getClient();
		try {
			$token = $client->fetchAccessTokenWithAuthCode($code);
			$client->setAccessToken($token);
			$this->Notification_m->setValue(Notification_m::SETTING_GMAIL_TOKEN, json_encode($token));
			file_put_contents(APPPATH . "cache/log.json", json_encode($token));


			$gmail = new Google_Service_Gmail($this->gmail_api->getClient());
			$this->Notification_m->setValue(Notification_m::SETTING_GMAIL_ADMIN, ($gmail->users->getProfile("me")->emailAddress));

			$this->session->set_flashdata("flash", ['type' => true, 'message' => 'Email successfully binded']);
			redirect(base_url("admin/setting"));
		} catch (Exception $ex) {
			echo "Token Auth Invalid/expired";
		}
	}

	public function request_auth()
	{
		$this->load->library('Gmail_api');
		$client = $this->gmail_api->getClient();
		if ($client->isAccessTokenExpired()) {
			$authUrl = $client->createAuthUrl();
			redirect($authUrl);
		}
	}

	public function upload_logo()
	{
		$config['upload_path'] = './themes/uploads';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['max_width'] = 1100;
		$config['overwrite'] = true;
		$config['file_name'] = 'logo.png';
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('file')) {
			$error = array('error' => $this->upload->display_errors());
			$this->output->set_status_header(500)
				->set_content_type("application/json")
				->_display(json_encode($error));
		}
	}

	public function save($setting_name = null)
	{
		if (!$setting_name && is_array($this->input->post('settings'))) {
			Settings_m::saveSetting($this->input->post('settings'));
		} elseif ($setting_name) {
			Settings_m::saveSetting($setting_name, $this->input->post('value'));
		}
	}

	public function change_password()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("User_account_m");
		$username = $this->session->user_session['username'];
		$data = $this->input->post();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('old_password', 'Old Password', [
			'required',
			['check_old_password', function ($value) use ($username) {
				return User_account_m::verify($username, $value);
			}]
		]);
		$this->form_validation->set_message("check_old_password", "Old Password Is Wrong !");
		$this->form_validation->set_rules('new_password', 'New Password', 'required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

		$user = $this->User_account_m->findOne(['username' => $username]);
		if ($this->form_validation->run()) {
			$user->password = password_hash($data['new_password'], PASSWORD_DEFAULT);
			$user->save();
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status' => true]));
		} else {
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status' => false, 'validation' => $this->form_validation->error_string()]));
		}
	}
}
