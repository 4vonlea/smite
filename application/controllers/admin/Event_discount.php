<?php


class Event_discount extends Admin_Controller
{

	protected $accessRule = [
		'index' => 'view',
		'save' => 'insert',
		'delete' => 'delete',
		'grid' => 'view',
	];

	public function index()
	{
		$this->load->model("Event_discount_m");

		$this->layout->render("event_discount",[
			'listPricingCategory'=>$this->Event_discount_m->listPricingCategory(),
			'listEvents'=>$this->Event_discount_m->listEvent(),
		]);
	}

	public function grid()
	{
		$this->load->model("Event_discount_m");
		$grid = $this->Event_discount_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function save()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		
		$this->load->model("Event_discount_m");
		$status = true;
		$data = $this->input->post();
		$validation = [];
		$this->load->library("form_validation");
		$this->form_validation->set_rules($this->Event_discount_m->rules($data['event_combination']['ruleCategory'] ?? []));
		if($this->form_validation->run()){
			$id = $this->input->post("id");
			if(isset($data['event_combination']['ruleCategory'])){
				foreach($data['event_combination']['ruleCategory'] as $row){
					$data['event_combination'][$row['key']] = $row['val'];
				}
				unset($data['event_combination']['ruleCategory']);
			}
			$data['event_combination'] = json_encode($data['event_combination']);
			if($id){
				$status = $this->Event_discount_m->update($data,$id);
			}else{
				$status = $this->Event_discount_m->insert($data);
				$data['id'] = $this->Event_discount_m->getLastInsertID();
			}
		}else{
			$status = false;
			$validation = $this->form_validation->error_array();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'data' => $data, 'validation' => $validation]));
	}


	public function delete()
	{

		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$message = "";
		$this->load->model("Event_discount_m");
		$id = $this->input->post('id');
		$status = $this->Event_discount_m->delete($id);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function detail()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$this->load->model("Event_discount_m");
			$data = $this->Event_discount_m->findOne($id);
			$this->output
				->set_content_type("application/json")
				->_display(json_encode($data));
		} else {
			$this->output->set_status_header(403);
		}
	}
}
