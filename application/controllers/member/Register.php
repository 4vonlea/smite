<?php
/**
 * Class Register
 * @property Participant_m $Participant_m
 */

class Register extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->layout->setLayout("layouts/porto");
    }

    public function index(){
        if($this->input->post()){
            $this->load->model(['Participant_m','User_account_m','Gmail_api']);

            $data = $this->input->post();
            if($this->Participant_m->validate($data) && $this->handlingProof('proof')){
                $this->load->library('Uuid');

                $data['id'] = Uuid::v4();
                $data['username_account'] = $data['email'];
                $data['verified_by_admin'] = 0;
                $data['verified_email'] = 0;
                $data['region'] = 0;
                $data['country'] = 0;

                $token = uniqid();
                $this->Participant_m->getDB()->trans_start();
                $this->Participant_m->insert(array_intersect_key($data,array_flip($this->Participant_m->fillable)),false);
                $this->User_account_m->insert([
                    'username'=>$data['email'],
                    'password'=>password_hash($data['password'],PASSWORD_DEFAULT),
                    'role'=>0,
                    'token_reset'=>"verifyemail_".$token
                ],false);
                $this->Participant_m->getDB()->trans_complete();
                $error['status'] = $this->Participant_m->getDB()->trans_status();
                $error['message'] = $this->Participant_m->getDB()->error();
                if($error['status']){
                    $email_message = $this->load->view('template_email/email_confirmation',['token'=>$token,'name'=>$data['fullname']],true);
                    $this->Gmail_api->sendMessage($data['email'],'Email Confirmation',$email_message);
                }
            }else{
                $error['status'] = false;
                $error['validation_error'] = array_merge($this->Participant_m->getErrors(),['proof'=>(isset($this->upload)?$this->upload->display_errors("",""):null)]);
            }
            $this->output->set_content_type("application/json")
                ->set_output(json_encode($error));

        }else{

            $this->load->helper("form");
            $this->load->model('Category_member_m');
            $participantsCategory = Category_member_m::asList(Category_member_m::findAll(), 'id', 'kategory','Please Select your status');
            $this->layout->render('member/register',['participantsCategory'=>$participantsCategory]);

        }
    }

    public function confirm_email(){
        $title = "Token Invalid/Expired";
        $message = "Token Confirmation is invalid or has been used. Check your token/link on your email";

        if($this->input->get('token')){
            $token = $this->input->get('token');
            $this->load->model(['User_account_m','Participant_m']);
            $result =  $this->User_account_m->findOne(['token_reset'=>'verifyemail_'.$token]);
            if($result) {
                $this->Participant_m->update(['verified_email'=>'1'],['username_account'=>$result->username],false);
                $result->token_reset = "";
                $result->save();
                $title = "Email Confirmed";
                $message = "Your email has been confirmed,  Follow this link to login " . anchor(base_url('site/login'), 'Click Here');
            }
        }
        $this->layout->render('member/notif',['message'=>$message,'title'=>$title]);
    }

    /**
     * @param $name
     * @return boolean
     */
    protected function handlingProof($name){
        $config['upload_path']          = './application/uploads/proof/';
        $config['allowed_types']        = 'jpg|png|pdf';
        $config['max_size']             = 2048;
        $config['file_name']        = date('YmdHis');

        $this->load->library('upload', $config);
        return $this->upload->do_upload($name);

    }
}