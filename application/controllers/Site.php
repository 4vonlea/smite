<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Layout $layout
 */
class Site extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->layout->setLayout("layouts/porto");
        $this->load->model('Event_m', 'EventM');
    }

    public function index()
    {
        $category      = $this->EventM->listcategory();
        $data['query'] = $category['data'];
        $this->layout->render('site/home', $data);
    }

    public function certificate()
    {
        $this->layout->render('site/certificate');
    }

    public function simposium()
    {
        $category      = $this->EventM->listcategory();
        $data['query'] = $category['data'];
        $this->layout->render('site/simposium', $data);
    }

    public function schedules()
    {
        $this->layout->render('site/schedules');
    }

    public function download()
    {
        $this->layout->render('site/download');
    }

    public function login()
    {
        if (!$this->user_session_expired()) {
            redirect(base_url("member/area"));
        }

        $this->load->library('form_validation');
        $error = "";
        if ($this->input->post('login')) {
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run()) {
                $username   = $this->input->post('username');
                $password   = $this->input->post('password');
                $rememberme = $this->input->post('rememberme');
                $this->load->model("User_account_m");
                if (User_account_m::verify($username, $password)) {
                    $this->load->library('session');
                    $user = $this->User_account_m->findWithBiodata($username);
                    unset($user['password']);
                    if ($rememberme) {
                        $user['rememberme'] = true;
                        $this->session->set_userdata("rememberme", true);
                        $this->session->set_userdata('sess_expired', time() + 60 * 60 * 24 * 7);
                    } else {
                        $this->session->set_userdata('sess_expired', time() + 3600);

                    }
                    $this->session->set_userdata('user_session', $user);
                    redirect(base_url("member/area"));
                } else {
                    $error = "Email/Password invalid !";
                }
            } else {
                $error = "Username and Password required !";

            }
        }
        $this->layout->render('site/login', ['error' => $error]);
    }

    public function register()
    {
        $this->layout->render('site/register');
    }

    public function forget()
    {
        $this->layout->render('site/forget');
    }

    public function committee()
    {
        $this->layout->render('site/committee');
    }

    public function contact()
    {
        $this->layout->render('site/contact');
    }

    public function paper()
    {
        $this->layout->render('site/paper');
    }


	public function barcode(){
		include APPPATH."third_party/phpqrcode/qrlib.php";
		echo QRcode::png("c35e1642-8a29-4d14-82f7-e562b5203214",false,"L",5,5);
	}
}
