<?php


class Login extends MY_Controller
{
    public function index(){
		$this->load->model("User_account_m");
		if(!$this->user_session_expired()) {
			$user = $this->session->user_session;
			if($user['role'] == User_account_m::ROLE_ADMIN_PAPER)
				redirect(base_url("admin/paper"));
			elseif($user['role'] == User_account_m::ROLE_OPERATOR)
				redirect(base_url("admin/dashboard"));
			else
				redirect(base_url("admin/dashboard"));
		}


        $this->load->library('form_validation');
        $error = "";
        if($this->input->post('login')){
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if($this->form_validation->run()) {
                $username = $this->input->post('username');
                $password = $this->input->post('password');
                $rememberme = $this->input->post('rememberme');
                if (User_account_m::verify($username, $password)) {
                    $this->load->library('session');
                    $user = $this->User_account_m->find()->where('username',$username)->get()->row_array();
                    if ($rememberme) {
                        $user['rememberme'] = true;
                        $this->session->set_userdata("rememberme",true);
                        $this->session->set_userdata('sess_expired', time()+60*60*24*7);
                    }else{
                        $this->session->set_userdata('sess_expired', time()+3600);

                    }
                    $this->session->set_userdata('user_session',$user);
                    if($user['role'] == User_account_m::ROLE_ADMIN_PAPER)
						redirect(base_url("admin/paper"));
					elseif($user['role'] == User_account_m::ROLE_OPERATOR)
						redirect(base_url("admin/dashboard"));
					else
						redirect(base_url("admin/dashboard"));
                } else {
                    $error = "Email/Password invalid !";
                }
            }else{
                $error = "Username and Password required !";

            }
        }
        $this->load->view("admin/login",['error'=>$error]);
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect(base_url('admin/login'));
    }

}
