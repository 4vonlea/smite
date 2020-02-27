<?php


class News extends Admin_Controller
{

	public function index()
	{
		$this->layout->render("news");
	}

	public function grid()
	{
		$this->load->model("News_m");
		$grid = $this->News_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function delete()
	{

		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$message = "";
		$this->load->model("News_m");
		$post = $this->input->post();

		$status = $this->News_m->find()->where('id',$post['id'])->delete();
		if ($status == false)
			$message = "Failed to delete member, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}
}
