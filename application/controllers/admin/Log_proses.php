<?php


class Log_proses extends Admin_Controller
{

	protected $accessRule = [
		'index'=>'view',
	];

	public function index()
	{
		$this->layout->render("log_proses");
	}

	public function grid()
	{
		$this->load->model("Log_proses_m");
		$grid = $this->Log_proses_m->gridData($this->input->get(),[
            'select'=>['controller','request','username','"100px" as height','date']
        ]);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

}
