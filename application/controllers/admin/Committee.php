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
		$this->load->model(['Committee_m','Committee_attribute_m']);

		$post = $this->input->post();

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
			if (isset($post['id'])) {
				$event = $this->Committee_m->findOne($post['id']);
			} else
				$event = new Committee_m();
		}
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
