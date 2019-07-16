<?php
/**
 * Class Area
 * @property Participant_m $Participant_m
 * @property User_account_m $User_account_m
 */

class Area extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        if($this->user_session_expired())
            redirect(base_url("site/login"));

        $this->layout->setLayout("layouts/porto");
        $this->layout->setBaseView('member/area/');
        $this->load->model(['Participant_m','User_account_m']);

    }

    public function index(){
        $user = Participant_m::findOne(['username_account'=>$this->session->user_session['username']]);
        $this->layout->render('index',['user'=>$user]);
    }

    public function upload_image(){
        $user = Participant_m::findOne(['username_account'=>$this->session->user_session['username']]);

        $config['upload_path']          = 'themes/uploads/profile/';
        $config['allowed_types']        = 'jpg|png|pdf';
        $config['max_size']             = 2048;
        $config['overwrite']             = true;
        $config['file_name']        = $user->id;

        $this->load->library('upload', $config);
        if($this->upload->do_upload('file')){
            $data = $this->upload->data();
            $user->image = $data['file_name'];
            $user->save();
            $response['status'] = true;
            $response['link'] = base_url("themes/uploads/profile/$data[file_name]");
        }else{
            $response['status'] = false;
            $response['message'] = $this->upload->display_errors("","");
        }

        $this->output->set_content_type("application/json")
            ->_display(json_encode($response));
    }

    public function page($name){
        $this->layout->renderAsJavascript($name.'.js');

    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('site');
    }
}