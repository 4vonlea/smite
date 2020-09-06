<?php
/**
 * Class Account
 * @property User_account_m $User_account_m
 */

class Click_report extends Admin_Controller
{
    public function index()
	{
		$this->load->model('Sponsor_link_m');

		$this->layout->render("click_report",[
            'field'=>$this->Sponsor_link_m->getFieldGrid()
        ]);
    }
    
    public function grid()
	{
		$this->load->model('Sponsor_link_m');
		$grid = $this->Sponsor_link_m->getReportMemberClick($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

}