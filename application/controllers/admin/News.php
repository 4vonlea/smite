<?php


class News extends Admin_Controller
{

	protected $accessRule = [
		'index'=>'view',
		'save'=>'insert',
		'delete'=>'delete',
		'grid'=>'view',
	];

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

	public function save()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$this->load->model('News_m');

		$post = $this->input->post(null,false);

		if (isset($post['id'])) {
			$id = $post['id'];
			$event = $this->News_m->findOne($post['id']);
		} else
			$event = new News_m();

		$event->setAttributes($post);

		$event->author = $this->session->user_session['username'];
		$status = $event->save();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status,'data'=>$event->toArray()]));
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
