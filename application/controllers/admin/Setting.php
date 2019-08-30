<?php


class Setting extends Admin_Controller
{
    public function index()
    {
		$this->load->model(['Gmail_api',"Whatsapp_api"]);
		$gmail_token = $this->Gmail_api->getToken();
		$this->layout->render('setting',[
			'wa_token'=>$this->Whatsapp_api->getToken(),
			'email_binded'=>(isset($gmail_token) && count() > 0)
		]);
    }

    public function unbind_email(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Gmail_api");
		$this->output
			->set_content_type("application/json")
			->_display(json_encode([
				'status'=>$this->Gmail_api->saveToken([]),
				'email'=>$this->Gmail_api->saveEmailAdmin(""),
			]));

	}

	public function save_token_wa(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Whatsapp_api");
		$token = $this->input->post("token");

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$this->Whatsapp_api->setToken($token)]));

	}

    public function token_auth()
    {
        $this->load->model('Gmail_api');
        $code = $this->input->get('code');
        $client = $this->Gmail_api->getClient();
        try {
            $token = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($token);
            $this->Gmail_api->saveToken($token);

            $gmail = new Google_Service_Gmail($this->Gmail_api->getClient());
            $this->Gmail_api->saveEmailAdmin($gmail->users->getProfile("me")->emailAddress);

            $this->session->set_flashdata("flash", ['type' => true, 'message' => 'Email successfully binded']);
            redirect(base_url("admin/setting"));
        } catch (Exception $ex) {
            echo "Token Auth Invalid/expired";
        }
    }

    public function request_auth()
    {
        $this->load->model('Gmail_api');
        $client = $this->Gmail_api->getClient();
        if ($client->isAccessTokenExpired()) {
            $authUrl = $client->createAuthUrl();
            redirect($authUrl);
        }
    }

    public function upload_logo()
    {
        $config['upload_path'] = './themes/uploads';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_width'] = 1024;
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
        if(!$setting_name && is_array($this->input->post('settings'))){
            Settings_m::saveSetting($this->input->post('settings'));
        }elseif($setting_name) {
            Settings_m::saveSetting($setting_name, $this->input->post('value'));
        }
    }

}
