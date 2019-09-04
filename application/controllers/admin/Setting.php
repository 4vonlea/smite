<?php


use Dompdf\Dompdf;

class Setting extends Admin_Controller
{
    public function index()
    {
    	$this->load->helper("form");
		$this->load->model(['Gmail_api',"Whatsapp_api","Event_m"]);
		$gmail_token = $this->Gmail_api->getToken();
		$this->layout->render('setting',[
			'wa_token'=>$this->Whatsapp_api->getToken(),
			'email_binded'=>(is_array($gmail_token) && count($gmail_token) > 0) ? 1: 0,
			"event"=>$this->Event_m->findAll(),
		]);
    }

    public function preview_cert($id){
		$domInvoice = new Dompdf();
		$propery = json_decode(Settings_m::getSetting("config_cert_$id"),true);
		foreach($propery as $field){
			$data[$field['name']] = "Preview $field[name]";
		}
		$html = $this->load->view("template/certificate",[
			'image'=>file_get_contents(APPPATH."uploads/cert_template/$id.txt"),
			'property'=>$propery,
			'data'=>$data
		],true);
		$domInvoice->setPaper("a4","landscape");
		$domInvoice->loadHtml($html);
		$domInvoice->render();
		$domInvoice->stream('preview_cert.pdf',array('Attachment'=>0));
	}

	public function get_cert(){
    	$id = $this->input->post('id');
    	$config = Settings_m::getSetting("config_cert_$id");
		if(file_exists(APPPATH."uploads/cert_template/$id.txt")) {
			$return['fileName'] = "Select Image as Template";
			$return['body'] = ['width' => '100%'];
			$return['property'] = json_decode($config, true);
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
		Settings_m::saveSetting("config_cert_$_POST[event]", $this->input->post('property'));

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
            file_put_contents("log.json",json_encode($token));


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
