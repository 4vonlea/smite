<?php


class Paper extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
		'grid'=>'view',
		'save'=>'insert',
		'file'=>'view',
	];
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
		$this->load->model(['User_account_m','Papers_m']);
		// if($this->session->user_session['role'] == User_account_m::ROLE_ADMIN_PAPER) {
		// 	$gridConfig = $this->Papers_m->gridConfig(['filter' => ['reviewer' => $this->session->user_session['username']]]);
		// }else{
			$gridConfig = $this->Papers_m->gridConfig();
		// }
		$grid = $this->Papers_m->gridData($this->input->get(),$gridConfig);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function get_feedback($id){
		$this->load->model(['Reviewer_feedback_m']);
		$feedback = $this->Reviewer_feedback_m->find()
			->select("result,paper_id,reviewer_feedback.status,reviewer_feedback.created_at,COALESCE(fullname,name) as name")
			->join("user_accounts","username = member_id")
			->join("members m","m.username_account = username","left")
			->where("paper_id",$id)->get()->result_array();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($feedback));
	}

	protected function save_reviewer(){
		$data = $this->input->post();
		$this->form_validation->set_rules("message", "Message", "required");
		if ($this->form_validation->run()) {
			$this->load->model('Reviewer_feedback_m');
			$response['status'] = $this->Reviewer_feedback_m->insert([
				'result'=>$data['message'],
				'status'=>$data['status'],
				'paper_id'=>$data['t_id'],
				'member_id'=>$this->session->user_session['username']
			]);
		} else {
			$response['status'] = false;
			$response['message'] = $this->form_validation->error_string();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function save()
	{
		$this->load->library('form_validation');
		$this->load->model('Papers_m');
		$data = $this->input->post();
		$response = [];
		if($this->session->user_session['role'] == User_account_m::ROLE_ADMIN_PAPER){
			$this->save_reviewer();
		}else{
			$this->form_validation->set_rules("status", "Status", "required");
			$model = $this->Papers_m->findOne($data['t_id']);
			if (isset($data['status']) && $data['status'] == 0 && $model->reviewer != "")
				$this->form_validation->set_rules("message", "Message", "required");

			if ($this->form_validation->run()) {
				if(isset($_POST['feedback_file']) && isset($_POST['filename_feedback'])) {
					$dataFile = $_POST['feedback_file'];
					list(, $dataFile) = explode(',', $dataFile);
					$dataFile = base64_decode($dataFile);
					$split = explode(".", $this->input->post('filename_feedback'));
					$ext = $split[count($split)-1];
					$filename = "feedback_".date("Ymdhis").".".$ext;
					file_put_contents(APPPATH . "uploads/papers/$filename", $dataFile);
					$model->feedback = $filename;
				}
				$model->status = $data['status'];
				$model->type_presence = $data['type_presence'];
				$model->reviewer = (isset($data['reviewer']) ? $data['reviewer'] : "");
				if(isset($data['message']))
					$model->message = $data['message'];
				$response['status'] = $model->save();
				if($response['status'] == false){
					$response['message'] = $model->errorsString();
				}else{
					if($data['status'] == Papers_m::ACCEPTED || $data['status'] == Papers_m::REJECTED ){
						$this->load->model("Notification_m");
						$message = "<p>Dear Participant</p>
						<p>Thank you for submitting your abstract to ".Settings_m::getSetting('site_title').". Please download your abstract result annoucement here.</p>
						<p>Best regards.<br/>
						Committee of ".Settings_m::getSetting('site_title')."</p>";
						$member = $model->member;
						$this->Notification_m->sendMessageWithAttachment($member->email,"Result Of Paper Review",$message,['Abstract Announcement.pdf'=>$model->exportNotifPdf()->output()]);
					}

				}
			} else {
				$response['status'] = false;
				$response['message'] = $this->form_validation->error_string();
			}

			$this->output
				->set_content_type("application/json")
				->_display(json_encode($response));
		}
	}

	public function file($name,$id,$type = "Abstract")
	{
		$filepath = APPPATH . "uploads/papers/" . $name;
		$this->load->model("Papers_m");
		$paper = $this->Papers_m->findOne($id);
		if (file_exists($filepath)) {
			list(,$ext) = explode(".",$name);
			$member = $paper->member;
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="'.$type.'-'.$paper->getIdPaper().'-'. $member->fullname . '.'.$ext.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		}else{
			show_404('File not found on server !');
		}
	}
}
