<?php


class Material extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
		'grid'=>'view',
		'remove_list'=>'delete',
		'add_list'=>'insert',
	];

	public function index()
	{
		$this->load->model(['Ref_upload_m','Category_member_m']);
		$this->load->helper("form");
        $statustoUpload = Settings_m::getSetting("status_to_upload");
        $statustoUpload = ($statustoUpload == "" || $statustoUpload == "null" ? "[]":$statustoUpload);

		$statusList = $this->Category_member_m->find()->get()->result_array();
		$uploadList = $this->Ref_upload_m->find()->select('*')->get()->result_array();
		$this->layout->render('material', ['uploadList' => $uploadList,'statusList'=>$statusList,'selectedStatus'=>$statustoUpload]);
	}

	
	public function add_list()
	{
		$this->load->model('Ref_upload_m');
		$data = $this->input->post('value');
		if (isset($data['id']))
			$model = Ref_upload_m::findOne($data['id']);
		else
			$model = new Ref_upload_m();
		$model->setAttributes($data);
		$model->save();
		$return = $this->Ref_upload_m->find()->select('*')->get()->result_array();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));
	}

	public function remove_list()
	{
		$this->load->model('Ref_upload_m');
		$status = $this->Ref_upload_m->delete($this->input->post('id'));
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function change_selected(){
		$value = $this->input->post("selected_status");
		Settings_m::saveSetting("status_to_upload",$value);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => true]));
	}

	public function file($name,$type)
	{
		$type = urldecode($type);
		$filepath = APPPATH . "uploads/material/" . $name;
		if (file_exists($filepath)) {
			list(,$ext) = explode(".",$name);
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="'.$type.'.'.$ext.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		}else{
			show_404('File not found on server !',false);
		}
	}

	public function grid()
	{
		$this->load->model('Ref_upload_m');

		$grid = $this->Ref_upload_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function upload_material(){
		$response = [];
		$this->load->library('upload');
		$this->load->model("Member_upload_material_m");
		$type = $this->input->post("type");
		if(isset($_FILES['filename']) && $type == Member_upload_material_m::TYPE_FILE ){
			$config = [
				'upload_path'=>APPPATH.'uploads/material/',
				'allowed_types'=>'doc|docx|jpg|jpeg|png|bmp|ppt|pdf|mp4',
				'max_size'=>20480,
				'overwrite'=>false,
			];
			$this->upload->initialize($config);
			$status = $this->upload->do_upload('filename');
			$data = $this->upload->data();
			$error = $this->upload->display_errors("","");
		}else{
			$status = true;
			$data = ['file_name'=>$this->input->post("filename")];
		}
		if($status){
			$id = $this->input->post("id");
			if($id && $id != "" && $id != 'null')
				$model = $this->Member_upload_material_m->findOne($id);
			else
				$model = new Member_upload_material_m();

			$model->member_id = $this->input->post("member_id");
			$model->ref_upload_id = $this->input->post("ref_upload_id");
			$model->type = $this->input->post("type");
			$model->filename = $data['file_name'];
			$response['data']['fullpaper'] = $model->toArray();
			$response['status'] = $model->save(false);
		}else{
			$response['status'] = false;
			$response['message'] = $error;
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}

}