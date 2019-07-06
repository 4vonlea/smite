<?php


class Setting extends Admin_Controller
{
    public function index(){
        $this->layout->render('setting');
    }

    public function token_auth(){
        $this->load->model('Gmail_api');
        $code = $this->input->get('code');
        $client = $this->Gmail_api->getClient();
        try {
            $token = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($token);
            $this->Gmail_api->saveToken($token);

            $gmail = new Google_Service_Gmail($this->Gmail_api->getClient());
            $this->Gmail_api->saveEmailAdmin($gmail->users->getProfile("me")->emailAddress);

            $this->session->set_flashdata("flash",['type'=>true,'message'=>'Email successfully binded']);
            redirect(base_url("admin/setting"));
        }catch (Exception $ex){
            echo "Token Auth Invalid/expired";
        }
    }

    public function request_auth(){
        $this->load->model('Gmail_api');
        $client = $this->Gmail_api->getClient();
        if ($client->isAccessTokenExpired()) {
            $authUrl = $client->createAuthUrl();
            redirect($authUrl);
        }
    }

    public function save($setting_name){
        Settings_m::saveSetting($setting_name,$this->input->post('value'));
    }

}