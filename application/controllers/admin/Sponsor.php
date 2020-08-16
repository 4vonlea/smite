<?php


class Sponsor extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
	];

	public function index()
	{
		$this->load->model('Sponsor_link_m');
		$this->load->helper("form");
		$this->layout->render('sponsor_link', [
			'categoryList'=>$this->Sponsor_link_m->listCategory(),
		]);
	}

	public function save()
	{
		$this->load->model('Sponsor_link_m');
		$id = $this->input->post('id');
		$data = $this->input->post('data');

		if($id){
			$model = $this->Sponsor_link_m->findOne($id);
		}else{
			$model = new Sponsor_link_m();
		}
		if($data['category'] == "OTHER"){
			$data['category'] = $this->input->post('new_category');
		}
		$statusUpload = true;
		$imageError = "";
		if(isset($_FILES['logo'])){
			$statusUpload = $this->handlingImage("logo",strtolower($data['name']));
			$imageData =  $this->upload->data();
			$imageError =  $this->upload->display_errors();
			$data['logo'] = $imageData['file_name'];
		}

		if ($this->Sponsor_link_m->validate($data) && $statusUpload) {
			$model->setAttributes($data);
			$return['status'] = $model->save();
			$return['message'] = "Data has been saved succesfully";
		} else {
			$return['status'] = false;
			$return['validation'] = $this->Sponsor_link_m->getErrors();
			$return['validation']['logo'] = $imageError;
		}
	
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));
	}


	public function grid()
	{
		$this->load->model('Sponsor_link_m');

		$grid = $this->Sponsor_link_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	/**
	 * @param $name
	 * @return boolean
	 */
	protected function handlingImage($name, $filename)
	{

		$config['upload_path'] = 'themes/uploads/sponsor/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size'] = 2048;
		$this->load->library('upload', $config);
		return $this->upload->do_upload($name);

	}

	public function delete()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Sponsor_link_m"]);
		$id = $this->input->post("id");
		$message = "";
		$status =$this->Sponsor_link_m->find()->where(['id'=>$id])->delete();
		if($status == false)
			$message = "Failed to delete member, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

}
