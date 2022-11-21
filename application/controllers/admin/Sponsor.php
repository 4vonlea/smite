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

	public function download(){
		$this->load->model('Sponsor_link_m');
		$data = $this->Sponsor_link_m->setAlias("t")->find()->select("name,category,link,count(lc.id) as click")
		->join('link_click lc','t.id = lc.link_id')
		->group_by('t.id')->get()->result_array();

		$this->load->library('Exporter');
		$exporter = new Exporter();
		$exporter->setData($data);
		$exporter->setTitle("Sponsor Link Click");
		$exporter->asExcel();
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
			$imageError =  $this->upload->display_errors("","");
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


	//Sponsor Stand Start Here

	public function stand()
	{
		$this->load->model('Sponsor_stand_m');
		$this->load->helper("form");
		$this->layout->render('sponsor_stand',[]);
	}

	public function grid_stand()
	{
		$this->load->model('Sponsor_stand_m');
		$grid = $this->Sponsor_stand_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function save_stand()
	{
		$this->load->model('Sponsor_stand_m');
		$id = $this->input->post('id');
		$sponsorName = $this->input->post('sponsor');

		$model = $this->Sponsor_stand_m->findOne($id);
		if(!$model){
			$this->load->library('Uuid');
			$model = new Sponsor_stand_m();
			$model->id = Uuid::v4();
		}
		$model->sponsor = $sponsorName;

		$return['status'] = $model->save();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));
	}

	public function report($id = null){
		$this->load->model(["Sponsor_stand_m"]);
		if($id)
			$data = $this->Sponsor_stand_m->getListPresence()->where("stand_sponsor.id",$id)->get()->result_array();
		else
			$data = $this->Sponsor_stand_m->getListPresence()->get()->result_array();

		if(count($data) > 0){
			$this->load->library('Exporter');
			$exporter = new Exporter();
			$exporter->setData($data);
			$exporter->setTitle("Report Stand Presence");
			$exporter->asExcel(['phone'=>'asPhone']);
		}else{
			show_404();
		}
	}

	public function delete_stand()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Sponsor_stand_m"]);
		$id = $this->input->post("id");
		$message = "";
		$status =$this->Sponsor_stand_m->find()->where(['id'=>$id])->delete();
		if($status == false)
			$message = "Failed to sponsor, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function qr_stand($id){
		$this->load->model(["Sponsor_stand_m"]);
		$status =$this->Sponsor_stand_m->getQrCard($id);

	}

}
