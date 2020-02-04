<?php


use Dompdf\Dompdf;

class Setting extends Admin_Controller
{
    public function index()
    {
    	$this->load->helper("form");
		$this->load->model(['Gmail_api',"Whatsapp_api","Event_m"]);
		$gmail_token = $this->Gmail_api->getToken();
		$manual = Settings_m::getSetting(Settings_m::MANUAL_PAYMENT);
		$this->layout->render('setting',[
			'wa_token'=>$this->Whatsapp_api->getToken(),
			'email_binded'=>(is_array($gmail_token) && count($gmail_token) > 0) ? 1: 0,
			"event"=>$this->Event_m->findAll(),
			'manual'=>($manual==""?"[]":$manual)
		]);
    }

    public function preview_cert($id){
    	$this->load->model("Event_m");
		$propery = json_decode(Settings_m::getSetting("config_cert_$id"),true);
		foreach($propery as $field){
			$data[$field['name']] = "Preview $field[name]";
		}
		$this->Event_m->exportCertificate($data,$id)->stream('preview_cert.pdf',array('Attachment'=>0));
	}

	public function get_cert(){
    	$id = $this->input->post('id');
    	$config = Settings_m::getSetting("config_cert_$id");
		if(file_exists(APPPATH."uploads/cert_template/$id.txt")) {
			$return['fileName'] = "Select Image as Template";
			$return['body'] = ['width' => '100%'];
			$return['property'] = json_decode($config, true);
			if($return['property'] == null)
				$return['property'] = [];
			$return['image'] = file_get_contents(APPPATH . "uploads/cert_template/$id.txt");
			$return['base64Image'] = $return['image'];
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status'=>true,'data'=>$return]));
		}else{
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status'=>false]));

		}
	}
    public function save_cert(){
//		$data = $_POST['base64Image'];
//		list($type, $data) = explode(';', $data);
//		list(, $data)      = explode(',', $data);
//		$data = base64_decode($data);
//		list(,$ext) = explode(".",$this->input->post('fileName'));
//		file_put_contents(APPPATH."uploads/cert_template/$_POST[event].".$ext, $data);
		file_put_contents(APPPATH."uploads/cert_template/$_POST[event].txt", $_POST['base64Image']);
//		Settings_m::saveSetting("config_cert_img_$_POST[event]",  $_POST['base64Image']);

		$property =  $this->input->post('property');
		if(!$property)
			$property = [];
		Settings_m::saveSetting("config_cert_$_POST[event]", $property);

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>true]));

	}


	public function preview_nametag($id){
		$this->load->model("Member_m");
		$propery = json_decode(Settings_m::getSetting("config_nametag_$id"),true);
		$data = ['qr'=>'','fullname'=>'','status_member'=>''];
		foreach($propery as $field){
			$data[$field['name']] = "Preview $field[name]";
		}
		$this->Member_m->getCard($id,$data)->stream('preview_nametag.pdf',array('Attachment'=>0));
	}

	public function get_nametag(){
		$id = $this->input->post('id');
		$config = Settings_m::getSetting("config_nametag_$id");
		if(file_exists(APPPATH."uploads/nametag_template/$id.txt")) {
			$return['fileName'] = "Select Image as Template";
			$return['body'] = ['width' => '100%'];
			$return['property'] = json_decode($config, true);
			if($return['property'] == null)
				$return['property'] = [];
			$return['image'] = file_get_contents(APPPATH . "uploads/nametag_template/$id.txt");
			$return['base64Image'] = $return['image'];
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status'=>true,'data'=>$return]));
		}else{
			$this->output
				->set_content_type("application/json")
				->_display(json_encode(['status'=>false]));

		}
	}
	public function save_nametag(){
		file_put_contents(APPPATH."uploads/nametag_template/$_POST[event].txt", $_POST['base64Image']);
		$property =  $this->input->post('property');
		if(!$property)
			$property = [];
		Settings_m::saveSetting("config_nametag_$_POST[event]",$property);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>true]));

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

	public function save_manual(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("Whatsapp_api");
		$email = $this->input->post("emailReceive");
		$banks = $this->input->post("banks");
		Settings_m::saveSetting(Settings_m::MANUAL_PAYMENT,json_encode($banks));
		Settings_m::saveSetting("email_receive",$email);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>true]));

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
            file_put_contents(APPPATH."cache/log.json",json_encode($token));


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

    public function change_password(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model("User_account_m");
		$username = $this->session->user_session['username'];
		$data = $this->input->post();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('old_password', 'Old Password', [
			'required',
			['check_old_password',function($value) use ($username){
				return User_account_m::verify($username,$value);
			}]
		]);
		$this->form_validation->set_message("check_old_password","Old Password Is Wrong !");
		$this->form_validation->set_rules('new_password', 'New Password', 'required');
		$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

		$user = $this->User_account_m->findOne(['username'=>$username]);
		if($this->form_validation->run()){
			$user->password = password_hash($data['new_password'],PASSWORD_DEFAULT);
			$user->save();
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status'=>true]));
		}else{
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status'=>false,'validation'=>$this->form_validation->error_string()]));
		}
	}

}
