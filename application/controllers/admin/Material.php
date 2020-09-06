<?php


class Material extends Admin_Controller
{

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
		$return = [];
		foreach ($data as $i => $row) {
			$model = null;
			if (isset($row['id']))
				$model = Ref_upload_m::findOne($row['id']);
			if ($model == null)
				$model = new Ref_upload_m();
			$model->setAttributes($row);
			$model->save();
			$return[] = $model->toArray();
		}
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

}