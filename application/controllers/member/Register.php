<?php
/**
 * Class Register
 * @property Member_m $Member_m
 */

class Register extends MY_Controller
{
	private $theme;

	public function __construct()
	{
		parent::__construct();
		$this->theme = $this->config->item("theme");
		$this->layout->setLayout("layouts/$this->theme");
		$this->load->model(['Sponsor_link_m']);

	}

	public function index()
	{
		$this->load->model('Category_member_m');
		$this->load->model('Univ_m');

		$status = $this->Category_member_m->find()->select("id,kategory,need_verify")->where('is_hide','0')->get()->result_array();
		$univ = $this->Univ_m->find()->select("univ_id, univ_nama")->order_by('univ_id')->get()->result_array();
		if ($this->input->post()) {
			$this->load->model(['Member_m', 'User_account_m', 'Notification_m']);
			$this->load->library('Uuid');

			$data = $this->input->post();
			$data['id'] = Uuid::v4();
			$univ = Univ_m::withKey($univ, "univ_id");
			$status = Category_member_m::withKey($status, "id");
			$need_verify = (isset($status[$data['status']]) && $status[$data['status']]['need_verify'] == "1");
			if ($this->Member_m->validate($data) && $this->handlingProof('proof', $data['id'], $need_verify)) {
				$data['username_account'] = $data['email'];
				$data['verified_by_admin'] = !$need_verify;
				$data['verified_email'] = 0;
				$data['region'] = 0;
				$data['country'] = 0;

				$token = uniqid();
				$this->Member_m->getDB()->trans_start();
				if($data['univ'] == Univ_m::UNIV_OTHER){
					$this->Univ_m->insert(['univ_nama'=>strtoupper($data['other_institution'])]);
					$data['univ'] = $this->Univ_m->last_insert_id;
				}
				$this->Member_m->insert(array_intersect_key($data, array_flip($this->Member_m->fillable)), false);
				$this->User_account_m->insert([
					'username' => $data['email'],
					'password' => password_hash($data['password'], PASSWORD_DEFAULT),
					'role' => 0,
					'token_reset' => "verifyemail_" . $token
				], false);
				$this->Member_m->getDB()->trans_complete();
				$error['status'] = $this->Member_m->getDB()->trans_status();
				$error['message'] = $this->Member_m->getDB()->error();
				if ($error['status']) {
					$email_message = $this->load->view('template/email_confirmation', ['token' => $token, 'name' => $data['fullname']], true);
					$this->Notification_m->sendMessage($data['email'], 'Email Confirmation', $email_message);
				}
			} else {
				$error['status'] = false;
				$error['validation_error'] = array_merge($this->Member_m->getErrors(), ['proof' => (isset($this->upload) ? $this->upload->display_errors("", "") : null)]);
			}
			$this->output->set_content_type("application/json")
				->set_output(json_encode($error));
		} else {
			$this->load->helper("form");
			$participantsCategory = Category_member_m::asList($status, 'id', 'kategory', 'Please Select your status');
			$participantsUniv = Univ_m::asList($univ, 'univ_id', 'univ_nama', 'Please Select your institution');
			$this->layout->render('member/' . $this->theme . '/register', ['participantsCategory' => $participantsCategory, 'statusList' => $status, 'participantsUniv' => $participantsUniv, 'univlist' => $univ]);
		}
	}

	public function confirm_email()
	{
		$title = "Token Invalid/Expired";
		$message = "Invalid link !";

		if ($this->input->get('token')) {
			$token = $this->input->get('token');
			$this->load->model(['User_account_m', 'Member_m']);
			$result = $this->User_account_m->findOne(['token_reset' => 'verifyemail_' . $token]);
			if ($result) {
				$this->Member_m->update(['verified_email' => '1'], ['username_account' => $result->username], false);
				$result->token_reset = "";
				$result->save();
				$title = "Email Confirmed";
				$message = "Your email has been confirmed,  Follow this link to login " . anchor(base_url('site/login'), 'Click Here');
			} else {
				$message = "This link has been used to verify email";
			}
		}
		$this->layout->render('member/' . $this->theme . '/notif', ['message' => $message, 'title' => $title]);
	}

	/**
	 * @param $name
	 * @return boolean
	 */
	protected function handlingProof($name, $filename, $need_upload)
	{
		if ($need_upload == false)
			return true;

		$config['upload_path'] = './application/uploads/proof/';
		$config['allowed_types'] = Member_m::$proofExtension;
		$config['max_size'] = 2048;
		$config['file_name'] = $filename;

		$this->load->library('upload', $config);
		return $this->upload->do_upload($name);

	}
}
