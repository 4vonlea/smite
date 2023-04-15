<?php

/**
 * @property Notification_m $Notification_m
 */
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
		$this->load->model(["Papers_m", "User_account_m", "Category_paper_m", "Category_member_m"]);
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

		if ($this->input->get("filterStatus")) {
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

	public function preview_loa($id)
	{
		$this->load->model('Papers_m');
		$model = $this->Papers_m->findOne($id);
		$model->exportNotifPdf()->stream("dompdf_out.pdf", array("Attachment" => false));
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
		$this->form_validation->set_rules("status", "Status", "required");
		$model = $this->Papers_m->findOne($data['t_id']);
		$oldData = $model->toArray();
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
			$response['status'] = $model->save(false);
			if ($response['status'] == false) {
				$response['message'] = $model->errorsString();
			} else {
				$this->load->model("Notification_m");
				$statusList = [
					Papers_m::REJECTED => 'rejected',
					Papers_m::ACCEPTED => 'accepted',
					Papers_m::RETURN_TO_AUTHOR => 'return',
					Papers_m::UNDER_REVIEW => 'under_review',
				];
				$member = $model->member;
				foreach (array_diff_assoc(
					['abstract' => $data['status'], 'fullpaper' => $data['status_fullpaper'], 'presentation' => $data['status_presentasi']],
					['abstract' => $oldData['status'], 'fullpaper' => $oldData['status_fullpaper'], 'presentation' => $oldData['status_presentasi']],
				) as $type => $status) {
					if (isset($statusList[$status])) {
						$template = $type . "_" . $statusList[$status];
						$this->Notification_m->sendInfoPaper($template, $member, $status, $type, $model);
						$this->Notification_m->setType(Notification_m::TYPE_WA)->sendInfoPaper($template, $member, $status, $type, $model);
					}
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

		if ($type == 'Voice') {
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
			} elseif ($type == "voice") {
				$fileToAdd = $row->voice;
			}
			$temp = explode(".", $fileToAdd);
			$ext = "";
			if (count($temp) > 0)
				$ext = $temp[count($temp) - 1];
			if (file_exists(APPPATH . "uploads/papers/" . $fileToAdd) && $fileToAdd != "") {
				$dataTitle = explode(" ", $row->title);
				$title = implode(" ", array_slice($dataTitle, 0, 3));
				$zip->addFromString($row->id_paper . "_" . $type . "_" . $row->fullname . "_" . $title . "." . $ext, file_get_contents(APPPATH . "uploads/papers/" . $fileToAdd));
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

	public function champion()
	{
		$this->load->model(['Category_paper_m']);

		$this->layout->set_breadcrumb("Papers Champion");
		$categoryPaper = $this->Category_paper_m->find()->select('*')->get()->result_array();
		$this->layout->render('paper_champion', [
			'categoryPaper' => $categoryPaper
		]);
	}

	public function grid_champion()
	{
		$this->load->model(['Paper_champion_m']);
		$gridConfig = $this->Paper_champion_m->gridConfig();
		if ($this->input->get("category")) {
			$gridConfig['filter']['category_paper.id'] = $this->input->get("category");
		}
		$grid = $this->Paper_champion_m->gridData($this->input->get(), $gridConfig);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function search_paper()
	{
		$this->load->model(['Papers_m']);
		$data = $this->Papers_m->setAlias("t")->find()
			->select("title as value,t.id as paper_id,fullname")
			->join("members", "members.id = t.member_id")
			->like('fullname', $this->input->post('cari'))
			->or_like('title', $this->input->post('cari'))
			->get()->result_array();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode([
				'inputPhrase' => $this->input->post('cari'),
				'items' => $data
			]));
	}

	public function add_champion()
	{
		$this->load->model(['Paper_champion_m']);
		$id = $this->input->post("id");
		if ($id) {
			$result = $this->Paper_champion_m->update([
				'paper_id' => $this->input->post("paper_id"),
				'description' => $this->input->post("description"),
			], ['id' => $id]);
		} else {
			$result = $this->Paper_champion_m->insert([
				'paper_id' => $this->input->post("paper_id"),
				'description' => $this->input->post("description"),
			]);
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode([
				'status' => true,
				'data' => $result,
			]));
	}

	public function delete_champion()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$message = "";
		$this->load->model(["Paper_champion_m"]);
		$post = $this->input->post();

		$status = $this->Paper_champion_m->find()->where(['id' => $post['id']])->delete();
		if ($status == false)
			$message = "Failed to delete member, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function preview_cert($id)
	{
		$this->load->model("Papers_m");
		$data = $this->Papers_m->find()->where(['papers.id' => $id])
			->join("members", "members.id = member_id")
			->join("settings st", 'st.name = "format_id_paper"', 'left')
			->select("CONCAT(st.value,LPAD(papers.id,3,0)) as id_paper,fullname,type_presence,email,title,'Peserta' as status")
			->get()->row_array();
		$this->Papers_m->exportCertificate($data)->stream('preview_cert.pdf', array('Attachment' => 0));
	}

	public function send_certificate()
	{
		if ($this->input->post()) {
			$this->load->model(["Notification_m", "Papers_m"]);
			$id = $this->input->post("id");
			if (file_exists(APPPATH . "uploads/cert_template/Paper.txt")) {
				$data = $this->Papers_m->find()->where(['papers.id' => $id])
					->join("members", "members.id = member_id")
					->join("settings st", 'st.name = "format_id_paper"', 'left')
					->select("CONCAT(st.value,LPAD(papers.id,3,0)) as id_paper,fullname,type_presence,email,phone,title,'Participant' as status")
					->get()->row_array();

				$cert = $this->Papers_m->exportCertificate($data)->output();
				$status = $this->Notification_m->sendCertificate($data, Notification_m::CERT_TYPE_PAPER, "Certificate of Manuscript", $cert);

				$status = $this->Notification_m->setType(Notification_m::TYPE_WA)
					->sendCertificate($data, Notification_m::CERT_TYPE_PAPER, "Certificate of Manuscript", $cert);

				$this->output
					->set_content_type("application/json")
					->_display(json_encode([
						'status' => $status['status'],
						'data' => $status,
					]));
			} else {
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => 'Template Certificate is not found ! please set on Setting']));
			}
		}
	}

	public function preview_cert_champion($id)
	{
		$this->load->model(["Paper_champion_m", "Papers_m"]);
		$data = $this->Paper_champion_m->champion($id);
		$this->Papers_m->exportCertificate($data)->stream('preview_cert.pdf', array('Attachment' => 0));
	}

	public function send_certificate_champion($id)
	{
		if ($this->input->post()) {
			$this->load->model(["Notification_m", "Papers_m", "Paper_champion_m"]);
			$id = $this->input->post("id");
			if (file_exists(APPPATH . "uploads/cert_template/Paper.txt")) {
				$data = $this->Paper_champion_m->champion($id);
				$cert = $this->Papers_m->exportCertificate($data)->output();
				$status = $this->Notification_m->sendCertificate($data, Notification_m::CERT_TYPE_PAPER, "Certificate of Manuscript", $cert);
				$this->output
					->set_content_type("application/json")
					->_display(json_encode([
						'status' => $status['status'],
						'data' => $status,
					]));
			} else {
				$this->output
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'message' => 'Template Certificate is not found ! please set on Setting']));
			}
		}
	}

	//Form Paper

	public function form_paper($id = null)
	{
		$this->layout->set_breadcrumb("Add Paper");
		if ($this->input->post()) {
			$config['upload_path']          = APPPATH . 'uploads/papers/';
			$config['allowed_types']        = 'pdf|doc|docx|ods';
			$config['max_size']             = 20480;
			$config['overwrite']             = true;
			$config['file_name']        = 'abstract' . date("Ymdhis"); //$this->session->user_session['id'];

			$this->load->library('upload', $config);
			$this->load->model(["Papers_m", "Notification_m"]);
			$upload = $this->upload->do_upload('file');
			$validation = $this->Papers_m->validate($this->input->post());
			if ($upload && $validation) {
				$paper = Papers_m::findOne(['id' => $this->input->post('id')]);
				$checkSameTitle = Papers_m::findOne(['member_id' => $this->input->post('member_id'), 'title' => $this->input->post('title', false)]);
				$isNew = false;
				$response['check'] = $checkSameTitle;
				if (!$paper || $paper->id == 0) {
					if ($checkSameTitle) {
						$paper = $checkSameTitle;
					} else {
						$isNew = true;
						$paper = new Papers_m();
					}
				}

				$this->load->model(["Category_paper_m"]);
				$category = $this->Category_paper_m->find()->where('name', $this->input->post('category'))->get()->row_array();

				$data = $this->upload->data();
				$paper->member_id = $this->input->post('member_id');
				$paper->filename = $data['file_name'];
				$paper->status = 1;
				$paper->title = $this->input->post('title', false);
				$paper->type = $this->input->post('type');
				$paper->introduction = $this->input->post('introduction', false);
				$paper->methods = $this->input->post('methods');
				$paper->category = $category['id'];
				if ($this->input->post("type_study_other")) {
					$paper->methods = $paper->methods . ": " . $this->input->post("type_study_other");
				}
				//            $paper->result = $this->input->post('result');
				//            $paper->aims = $this->input->post('aims');
				//            $paper->conclusion = $this->input->post('conclusion');
				$paper->type_presence = $this->input->post('type_presence');
				$paper->reviewer = "";
				$paper->message = "";
				$paper->co_author = json_encode($this->input->post('co_author'));
				$paper->created_at = date("Y-m-d H:i:s");
				$paper->save();
				$paper->updated_at = date("Y-m-d H:i:s");
				$response['status'] = true;
				$response['paper'] = $paper->toArray();
				$response['isNew'] = $isNew;
				if ($isNew) {
					$user = Member_m::findOne(['username_account' => $this->session->user_session['username']]);
					$email_message = $this->load->view("template/email/abstract_received", [
						'id' => $this->Papers_m->getIdPaper($paper->id),
						'title' => $this->input->post("title"),
						'name' => $user->fullname,
						'email' => $user->email,
					], true);
					$this->Notification_m->sendMessage($user->email, 'Abstract Received', $email_message);
				}
			} else {
				$response['status'] = false;
				$response['message'] = array_merge($this->Papers_m->getErrors(), ['file' => $this->upload->display_errors("", "")]);
			}

			$this->output->set_content_type("application/json")
				->_display(json_encode($response));
		} else {
			$this->layout->render('form_paper', [
				'id' => $id,
			]);
		}
	}

	public function get_paper()
	{
		if ($this->input->method() !== 'post')
			show_404("Page not found !");
		$this->load->model(["Papers_m", "Category_paper_m"]);
		$papers = Papers_m::findAll(['id' => $this->input->get("id")]);
		// $response['abstractType'] = Papers_m::$typeAbstract;
		$response['status'] = Papers_m::$status;
		// $response['typeStudy'] = Papers_m::$typeStudy;
		$response['typePresention'] = Papers_m::$typePresentation;
		$response['deadline'] = [
			'paper_deadline' => Settings_m::getSetting('paper_deadline'),
			'paper_cutoff' => Settings_m::getSetting('paper_cutoff'),
			'fullpaper_deadline' => Settings_m::getSetting('fullpaper_deadline'),
			'fullpaper_cutoff' => Settings_m::getSetting('fullpaper_cutoff'),
			'presentation_deadline' => Settings_m::getSetting('presentation_deadline'),
			'presentation_cutoff' => Settings_m::getSetting('presentation_cutoff'),
		];
		$response['declaration'] = Papers_m::$declaration;
		// NOTE Category Paper
		$categoryPaper = $this->Category_paper_m->find()->order_by("name")->get();
		$categoryPaper = $categoryPaper->result_array();
		$response['categoryPaper'] = Category_paper_m::asList($categoryPaper, "id", "name");
		$treePaper = [];
		foreach ($categoryPaper as $key => $value) {
			$treePaper[$value['name']] = json_decode($value['tree'], true);
		}
		$response['treePaper'] = $treePaper;

		$response['data'] = [];
		$formatId = Settings_m::getSetting("format_id_paper");
		foreach ($papers as $paper) {
			$temp = $paper->toArray();
			$temp['id_paper'] = $formatId . str_pad($temp['id'], 3, 0, STR_PAD_LEFT);
			$methods = explode(":", $temp['methods']);
			if (count($methods) > 1) {
				$temp['methods'] = $methods[0];
				$temp['type_study_other'] = trim($methods[1]);
			}
			$temp['co_author'] = json_decode($temp['co_author'], true);
			$category_paper = $paper->category_paper ? $paper->category_paper->toArray() : [];
			$response['data'][] = array_merge($temp, ['category_paper' => $category_paper]);
		}
		$response['memberList'] = $this->db->select("id,CONCAT(fullname,' (',email,')') as label")->get("members")->result_array();
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}
}
