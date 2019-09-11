<?php


class Paper extends Admin_Controller
{

	public function index()
	{
		$this->load->model(["Papers_m", "User_account_m"]);
		$adminPaper = [];
		foreach ($this->User_account_m->findAll(['role' => User_account_m::ROLE_ADMIN_PAPER]) as $row) {
			$t = $row->toArray();
			unset($t['password'],$t['role'],$t['token_reset']);
			$adminPaper[] = $t;
		}
		$this->layout->render('paper', [
			'admin_paper' => $adminPaper
		]);
	}


	public function grid()
	{
		$this->load->model('Papers_m');

		$grid = $this->Papers_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function save()
	{
		$this->load->library('form_validation');
		$this->load->model('Papers_m');
		$data = $this->input->post();
		$response = [];

		$this->form_validation->set_rules("status", "Status", "required");
		if (isset($data['status']) && $data['status'] == 0)
			$this->form_validation->set_rules("message", "Message", "required");
		if ($this->form_validation->run()) {
			$model = $this->Papers_m->findOne($data['t_id']);
			$model->status = $data['status'];
			$model->reviewer = (isset($data['reviewer']) ? $data['reviewer'] : "");
			$model->message = (isset($data['message']) ? $data['message'] : "");
			$response['status'] = $model->save();
		} else {
			$response['status'] = false;
			$response['message'] = $this->form_validation->error_string();
		}

		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function file($name)
	{
		$filepath = APPPATH . "uploads/papers/" . $name;
		if (file_exists($filepath)) {
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="' . $name . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		}
	}
}
