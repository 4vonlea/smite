<?php
/**
 * Class Account
 * @property User_account_m $User_account_m
 */

class Account extends Admin_Controller
{
	public function index()
	{
		$this->load->model('User_account_m');
		$this->layout->render("account");
	}

	public function grid()
	{
		$this->load->model('User_account_m');

		$grid = $this->User_account_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function delete()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$this->load->model('User_account_m');
			$ex = $this->User_account_m->delete(['username' => $id]);
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status' => $ex, 'msg' => 'User account deleted successfully !']));

		} else {
			$this->output->set_status_header(403);
		}
	}

	public function reset()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$password = rand(10000, 99999);
			$this->load->model('User_account_m');
			$ex = $this->User_account_m->update(['password' => password_hash($password, PASSWORD_DEFAULT)], ['username' => $id],false);
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status' => $ex, 'msg' => 'New Password is "' . $password . '"']));

		} else {
			$this->output->set_status_header(403);
		}
	}

	public function save()
	{
		if ($this->input->is_ajax_request()) {
			$this->load->model('User_account_m');
			$data = $this->input->post();
			if ($this->User_account_m->validate($data)) {
				$insert = [
					'username' => $data['username'],
					'name' => $data['name'],
					'password' => password_hash($data['password'], PASSWORD_DEFAULT),
					'role' => $data['role'],
					'token_reset' => "",
				];
				$ex = $this->User_account_m->insert($insert, false);
				$this->output->set_content_type("application/json")
					->_display(json_encode(['status' => $ex, 'msg' => 'Data saved successfully !']));
			} else {
				$error = $this->User_account_m->getErrors();
				$this->output->set_status_header(400)
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'validation' => $error]));
			}
		}
	}
}
