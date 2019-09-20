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
        $this->load->model('User_account_m', 'AccountM');
        $this->load->model('Gmail_api');
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
    	redirect("member/register");
//        $this->layout->render('site/register');
    }

    public function forget()
    {
        $this->layout->render('site/forget');
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
            $this->Gmail_api->sendMessage($username, 'Reset password EASDV account', $email_message);
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

    public function barcode($code, $tes = "")
    {
        include APPPATH . "third_party/phpqrcode/qrlib.php";
        if ($tes == 'show') {
            echo "<p>$code</p><img src='" . base_url('site/barcode/' . $code) . "';/>";
        } else {
            QRcode::png($code, false, "L", 10, 10);
        }
    }

    public function formatdate($date)
    {
        $str = explode('-', $date);

        $bulan = array(
            '01' => 'Jan',
            '02' => 'Feb',
            '03' => 'Mar',
            '04' => 'Apr',
            '05' => 'May',
            '06' => 'Jun',
            '07' => 'Jul',
            '08' => 'Aug',
            '09' => 'Sep',
            '10' => 'Oct',
            '11' => 'Nov',
            '12' => 'Dec',
        );
        return $str['2'] . " " . $bulan[$str[1]] . " " . $str[0];
    }
}
