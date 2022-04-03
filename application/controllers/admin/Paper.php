<?php


class Paper extends Admin_Controller
{
	protected $accessRule = [
		'index' => 'view',
		'grid' => 'view',
		'save' => 'insert',
		'file' => 'view',
	];
	public function index()
	{
		$this->load->helper("form");
		$this->load->model(["Papers_m", "User_account_m", "Category_paper_m","Category_member_m"]);
		$adminPaper = [];
		foreach ($this->User_account_m->findAll(['role' => User_account_m::ROLE_ADMIN_PAPER]) as $row) {
			$t = $row->toArray();
			unset($t['password'], $t['role'], $t['token_reset']);
			$adminPaper[] = $t;
		}
		$categoryPaper = $this->Category_paper_m->find()->select('*')->get()->result_array();
		$this->layout->render('paper', [
			'admin_paper' => $adminPaper,
			'categoryPaper' => $categoryPaper,
			'categoryMember' => $this->Category_member_m->find()->get()->result_array(),
		]);
	}


	public function grid($idCategory = '')
	{
		$this->load->model(['User_account_m', 'Papers_m']);
		// if($this->session->user_session['role'] == User_account_m::ROLE_ADMIN_PAPER) {
		// 	$gridConfig = $this->Papers_m->gridConfig(['filter' => ['reviewer' => $this->session->user_session['username']]]);
		// }else{


		$filterCategoryPaper = $idCategory ? [
			'filter' => [
				'category' => $idCategory,
			]
		] : [];

		if($this->input->get("filterStatus")){
			$filterCategoryPaper['filter']['kategory_members.id'] = $this->input->get("filterStatus");
		}

		$gridConfig = $this->Papers_m->gridConfig($filterCategoryPaper);
		// }
		$grid = $this->Papers_m->gridData($this->input->get(), $gridConfig);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function get_feedback($id)
	{
		$this->load->model(['Reviewer_feedback_m']);
		$feedback = $this->Reviewer_feedback_m->find()
			->select("result,paper_id,reviewer_feedback.status,reviewer_feedback.created_at,COALESCE(fullname,name) as name")
			->join("user_accounts", "username = member_id")
			->join("members m", "m.username_account = username", "left")
			->where("paper_id", $id)->get()->result_array();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($feedback));
	}

	protected function save_reviewer()
	{
		$data = $this->input->post();
		$this->form_validation->set_rules("message", "Message", "required");
		if ($this->form_validation->run()) {
			$this->load->model('Reviewer_feedback_m');
			$response['status'] = $this->Reviewer_feedback_m->insert([
				'result' => $data['message'],
				'status' => $data['status'],
				'paper_id' => $data['t_id'],
				'member_id' => $this->session->user_session['username']
			]);
		} else {
			$response['status'] = false;
			$response['message'] = $this->form_validation->error_string();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function save_setting_date()
	{
		$post = $this->input->post();
		$response = [];
		$response['status'] = Settings_m::saveSetting($post);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	protected function saveFeedbackFile($dataFile, $filename, $type)
	{
		list(, $dataFile) = explode(',', $dataFile);
		$dataFile = base64_decode($dataFile);
		$split = explode(".", $filename);
		$ext = $split[count($split) - 1];
		$filename = $type . "_" . date("Ymdhis") . "." . $ext;
		file_put_contents(APPPATH . "uploads/papers/$filename", $dataFile);
		return $filename;
	}

	public function save()
	{
		$this->load->library('form_validation');
		$this->load->model('Papers_m');
		$data = $this->input->post();
		$response = [];
		// if($this->session->user_session['role'] == User_account_m::ROLE_ADMIN_PAPER){
		// 	$this->save_reviewer();
		// }else{
		$this->form_validation->set_rules("status", "Status", "required");
		$model = $this->Papers_m->findOne($data['t_id']);
		if (isset($data['status']) && $data['status'] == 0 && $model->reviewer != "")
			$this->form_validation->set_rules("message", "Message", "required");

		if ($this->form_validation->run()) {
			if (isset($_POST['feedback_file']) && isset($_POST['filename_feedback'])) {
				$filename = $this->saveFeedbackFile($_POST['feedback_file'], $_POST['filename_feedback'], 'feedback');
				$model->feedback = $filename;
			}
			if (isset($_POST['feedback_file_fullpaper']) && isset($_POST['filename_feedback_fullpaper'])) {
				$filename = $this->saveFeedbackFile($_POST['feedback_file_fullpaper'], $_POST['filename_feedback_fullpaper'], 'fullpaper_feedback');
				$model->feedback_file_fullpaper = $filename;
			}
			if (isset($_POST['feedback_file_presentasi']) && isset($_POST['filename_feedback_presentasi'])) {
				$filename = $this->saveFeedbackFile($_POST['feedback_file_presentasi'], $_POST['filename_feedback_presentasi'], 'presentation_feedback');
				$model->feedback_file_presentasi = $filename;
			}

			$model->status = $data['status'];
			$model->score = $data['score'];
			if ($data['status_fullpaper'] != "")
				$model->status_fullpaper = $data['status_fullpaper'];
			else
				$model->status_fullpaper = "-1"; //$data['status_fullpaper'];
			if ($data['status_presentasi'] != "") {
				$model->status_presentasi = $data['status_presentasi'];
			} else {
				$model->status_presentasi = "-1"; //$data['status_presentasi'];
			}
			$model->type_presence = $data['type_presence'];
			$model->feedback_fullpaper = $data['feedback_fullpaper'];
			$model->feedback_presentasi = $data['feedback_presentasi'];

			$model->reviewer = (isset($data['reviewer']) ? $data['reviewer'] : "");
			if (isset($data['message']))
				$model->message = $data['message'];
			$response['status'] = $model->save();
			if ($response['status'] == false) {
				$response['message'] = $model->errorsString();
			} else {
				$this->load->model("Notification_m");
				if ($data['status'] == Papers_m::RETURN_TO_AUTHOR || $data['status_fullpaper'] == Papers_m::RETURN_TO_AUTHOR  || $data['status_presentasi'] == Papers_m::RETURN_TO_AUTHOR) {
					$member = $model->member;
					$paperid = $this->Papers_m->getIdPaper($model->id);
					$message = "<p>Dear $member->fullname</p>
						<p>ID Paper : $paperid</p>
						<p>Please login to your account to view more detailed comments/feedback files. Please make revisions before the deadline. Thank you<p>";
					$this->Notification_m->sendMessage($member->email, "Result Of Paper Review", $message);
				} elseif ($data['status'] == Papers_m::ACCEPTED || $data['status_fullpaper'] == Papers_m::ACCEPTED || $data['status'] == Papers_m::REJECTED) {
					$member = $model->member;
					$paperid = $this->Papers_m->getIdPaper($model->id);
					$message = "<p>Dear $member->fullname</p>
						<p>ID Paper : $paperid</p>
						<p>Thank you for submitting your abstract to " . Settings_m::getSetting('site_title') . ". Please download your abstract result annoucement here.</p>
						<p>Best regards.<br/>
						Committee of " . Settings_m::getSetting('site_title') . "</p>";
					$this->Notification_m->sendMessageWithAttachment($member->email, "Result Of Paper Review", $message, ['Abstract Announcement.pdf' => $model->exportNotifPdf()->output()]);
				}
			}
		} else {
			$response['status'] = false;
			$response['message'] = $this->form_validation->error_string();
		}

		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
		// }
	}

	public function delete()
	{
		$this->load->model('Papers_m');
		$status = $this->Papers_m->delete($this->input->post('id'));
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function file($name, $id, $type = "Abstract")
	{
		$filepath = APPPATH . "uploads/papers/" . $name;
		$this->load->model("Papers_m");
		$paper = $this->Papers_m->findOne($id);

		if ($type == 'Voice Recording') {
			$type = 'Voice';
		} else if (in_array($type, ['Moderated Poster', 'Viewed Poster', 'Oral'])) {
			$type = "Present";
		}

		if (file_exists($filepath)) {
			list(, $ext) = explode(".", $name);

			$dataTitle = explode(" ", $paper->title);
			$title = count($dataTitle) > 3 ? "{$dataTitle[0]} {$dataTitle[1]} {$dataTitle[2]}" : implode(" ", $dataTitle);

			$member = $paper->member;

			// $filename = $type . '-' . $paper->getIdPaper() . '-' . $member->fullname . '.' . $ext;
			$filename = "{$paper->getIdPaper()}-{$type}-{$member->fullname}-{$title}.{$ext}";
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		} else {
			show_404('File not found on server !');
		}
	}

	public function download_all_files($type)
	{
		set_time_limit(0);
		ini_set('memory_limit', '2048M');
		$zip = new ZipArchive();
		$filename = "./themes/uploads/" . $type . "_all.zip";

		if (file_exists($filename)) {
			unlink($filename);
		}
		$zip->open($filename, ZipArchive::CREATE);

		$this->load->model("Papers_m");
		$participant = $this->Papers_m->setAlias("t")->find()
			->join("members m", "m.id = t.member_id")
			->join("settings st", 'st.name = "format_id_paper"', 'left')
			->select("m.fullname,t.*,CONCAT(st.value,LPAD(t.id,3,0)) as id_paper")->get()->result();
		foreach ($participant as $row) {
			$fileToAdd = "";
			if ($type == 'abstract') {
				$fileToAdd = $row->filename;
			} elseif ($type == 'presentation') {
				$fileToAdd = $row->poster;
			} elseif ($type == 'fullpaper') {
				$fileToAdd = $row->fullpaper;
			}
			$temp = explode(".", $fileToAdd);
			$ext = "";
			if (count($temp) > 0)
				$ext = $temp[count($temp) - 1];
			if (file_exists(APPPATH . "uploads/papers/" . $fileToAdd) && $fileToAdd != "") {
				$zip->addFromString($row->id_paper . "_" . $type . "_" . $row->fullname . "." . $ext, file_get_contents(APPPATH . "uploads/papers/" . $fileToAdd));
			}
		}
		redirect(base_url('themes/uploads/' . $type . "_all.zip"));
	}

	public function previewNotif($id)
	{
		$this->load->model("Papers_m");
		$paper = $this->Papers_m->findOne($id);
		$paper->exportNotifPdf()->stream("dompdf_out.pdf", array("Attachment" => false));
	}

	/* -------------------------------------------------------------------------- */
	/*                             NOTE Category Paper                            */
	/* -------------------------------------------------------------------------- */

	public function addCategoryPaper()
	{
		$this->load->model('Category_paper_m');
		$data = $this->input->post('value');
		$return = [];
		foreach ($data as $i => $row) {
			$model = null;
			if (isset($row['id']))
				$model = Category_paper_m::findOne($row['id']);
			if ($model == null)
				$model = new Category_paper_m();
			$model->setAttributes($row);
			$model->save();
			$return[] = $model->toArray();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));
	}

	public function removeCategoryPaper()
	{
		$this->load->model('Category_paper_m');
		$status = $this->Category_paper_m->delete($this->input->post('id'));
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}
}
