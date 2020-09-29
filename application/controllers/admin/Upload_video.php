<?php


class Upload_video extends Admin_Controller
{

	public function index()
	{
		$this->load->model('Upload_video_m');
		$this->load->helper("form");
		$this->layout->render('upload_video');
	}

	public function save()
	{
		$this->load->model('Upload_video_m');
		$id = $this->input->post('id');
		$data = $this->input->post('data');

		if($id){
			$model = $this->Upload_video_m->findOne($id);
		}else{
			$model = new Upload_video_m();
		}
		$statusUpload = true;
		$uploadError = "";
		if(isset($_FILES['logo'])){
			$statusUpload = $this->handlingFile("filename",strtolower($data['filename']));
			$uploadData =  $this->upload->data();
			$uploadError =  $this->upload->display_errors("","");
			$data['filename'] = $uploadData['file_name'];
		}

		if ($this->Upload_video_m->validate($data) && $statusUpload) {
			$model->setAttributes($data);
			$return['status'] = $model->save();
			$return['message'] = "Data has been saved succesfully";
		} else {
			$return['status'] = false;
			$return['validation'] = $this->Sponsor_link_m->getErrors();
			$return['validation']['filename'] = $uploadError;
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));
	}


	public function grid()
	{
		$this->load->model('Upload_video_m');

		$grid = $this->Upload_video_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	/**
	 * @param $name
	 * @return boolean
	 */
	protected function handlingUpload($name)
	{
		$config['upload_path'] = 'themes/uploads/video/';
		$config['allowed_types'] = 'jpg|jpeg|png|mp4|mkv';
		$config['max_size'] = 102400;
		$this->load->library('upload', $config);
		return $this->upload->do_upload($name);
	}

	public function delete()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Upload_video_m"]);
		$id = $this->input->post("id");
		$message = "";
		$status =$this->Upload_video_m->find()->where(['id'=>$id])->delete();
		if($status == false)
			$message = "Failed to delete member, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

}
