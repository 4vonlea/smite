<?php
/**
 * Class MY_Controller
 * @property CI_Session $session
 * @property Layout $layout
 * @property CI_Config $config
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $output
 */
class MY_Controller extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('untung_helper');
    }

    protected function user_session_expired(){
        if($this->session->has_userdata('user_session')){
            $sess = $this->session->userdata("sess_expired");
            if($sess && time() > $sess){
                $this->session->sess_destroy();
                return true;
            }

            if($this->session->has_userdata('rememberme')){
                $this->session->set_userdata('sess_expired', time()+60*60*24*7);
            }else{
                $this->session->set_userdata('sess_expired', time()+3600);
            }
            return false;
        }
        return true;
    }
}


class Admin_Controller extends MY_Controller{
    public function __construct(){
        parent::__construct();

        if($this->user_session_expired())
            redirect(base_url("admin/login"));

        if($this->session->user_session['role'] == '0'){
            redirect(base_url("member/area"));
        }

        $this->layout->setLayout("layouts/argon");
        $this->layout->setBaseView("admin/");
        $this->load->model('Settings_m');
    }

}