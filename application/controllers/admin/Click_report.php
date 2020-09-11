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

	public function download_detail()
	{
		$this->load->model('Sponsor_link_m');
		$data = $this->Sponsor_link_m->setAlias("t")->find()
		->select("COALESCE(m.fullname,lc.username) as name_click,t.name as sponsor_name,category,link,lc.created_at as click_at")
		->join('link_click lc','t.id = lc.link_id')
		->join("members m","lc.username = m.username_account","left")
		->get()->result_array();

		$this->load->library('Exporter');
		$exporter = new Exporter();
		$exporter->setData($data);
		$exporter->setTitle("Detail Click Link");
		$exporter->asExcel();
	}
	
	public function download()
	{
		$this->load->model(['Sponsor_link_m','Member_m']);
		$field = $this->Sponsor_link_m->getFieldGrid();
		$cMember = $this->Member_m->setAlias("t")->find()->select("count(*) as c")->get()->row()->c;
		$cMember = (int) $cMember + 100;
		$grid = $this->Sponsor_link_m->getReportMemberClick(['sort'=>'','per_page'=>$cMember,'page'=>1,'fields'=>[]]);
		$this->load->library('Exporter');
		
		$data = [];
		foreach($grid['data'] as $row){
			$temp = [];
			foreach($field as $f){
				$temp[$f['title']] = $row[$f['name']];
			}
			$data[] = $temp;
		}
		$exporter = new Exporter();
		$exporter->setData($data);
		$exporter->setTitle("Link Click Report");
		$exporter->asExcel();
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