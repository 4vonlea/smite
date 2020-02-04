<?php

class Committee extends Admin_Controller
{

	public function index()
	{
		$this->load->model("Event_m");
		$events = $this->Event_m->find()->get()->result_array();
		$this->layout->render("committee", ['events' => $events]);
	}

	public function save()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model(['Committee_m', 'Committee_attributes_m']);

		$post = $this->input->post();

		$this->Committee_m->getDB()->trans_start();
		if (isset($post['id'])) {
			$id = $post['id'];
			$event = $this->Committee_m->findOne($post['id']);
		} else
			$event = new Committee_m();

		$event->name = $post['name'];
		$event->save();

		if (!isset($post['id']))
			$id = $event->getLastInsertID();

		foreach ($post['attributes'] as $row) {
			if (isset($row['id'])) {
				$attr = $this->Committee_attributes_m->findOne($row['id']);
			} else
				$attr = new Committee_attributes_m();
			$attr->event_id = $row['event'];
			$attr->status = $row['status'];
			$attr->committee_id = $id;
			$attr->save();
		}

		$this->Committee_m->getDB()->trans_complete();
		$status = $this->Committee_m->getDB()->trans_status();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status]));
	}

	public function delete()
	{

		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$message = "";
		$this->load->model(["Committee_m", "Committee_attributes_m"]);
		$post = $this->input->post();

		$this->Committee_m->getDB()->trans_start();
		$this->Committee_m->find()->where(['id' => $post['id']])->delete();
		$this->Committee_attributes_m->find()->where(['committee_id' => $post['id']])->delete();
		$this->Committee_m->getDB()->trans_complete();
		$status = $this->Committee_m->getDB()->trans_status();
		if ($status == false)
			$message = "Failed to delete member, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}

	public function nametag($id){
		$this->load->model('Committee_attributes_m');
		$com = $this->Committee_attributes_m->findOne($id);
		try {
			$com->getCard()->stream($com->committee->name . "-nametag.pdf");
		}catch (ErrorException $ex){
			show_error($ex->getMessage());
		}
	}

	public function delete_attribute(){
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$post = $this->input->post();
		$this->load->model("Committee_attributes_m");
		$status = $this->Committee_attributes_m->delete(['id' => $post['id']]);
		$message = "";
		if ($status == false)
			$message = "Failed to delete member, error on server !";
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));

	}

	public function grid()
	{
		$this->load->model('Committee_m');

		$grid = $this->Committee_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}
}
