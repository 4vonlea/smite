<?php


use Dompdf\Dompdf;

class Dashboard extends Admin_Controller
{

    public function index(){
        $this->layout->render("dashboard");
    }

    public function test(){
		$this->load->model("Transaction_m");
		$tr = $this->Transaction_m->findOne("SANDBOX-G988409790-516");
		$member = $tr->member;

		$html = $this->load->view("template/invoice",[
			'transaction'=>$tr,
		],true);
		$dompdf = new Dompdf();
		$dompdf->loadHtml($html);
		$dompdf->render();
		$file = $dompdf->stream();

	}

    public function data(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model("Dashboard_m");
		$report = $this->Dashboard_m->getData();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>true,'report'=>$report]));
	}

	public function download_member($tipe){
		$this->load->model("Dashboard_m");
    	$data =  $this->Dashboard_m->getDataMember();
    	$this->export($tipe,"Members Registered on Site",$data);
	}

	public function download_participant($event_id,$tipe){
		$this->load->model("Dashboard_m");
		$data =  $this->Dashboard_m->getParticipant($event_id);
		$this->export($tipe,"Participant of Events",$data);

	}

	public function export($tipe = null,$title = null,$data = null){
    	if($this->input->post('tipe'))
    		$tipe = $this->input->post('tipe');
		if($this->input->post('data'))
			$data = $this->input->post('data');
		if($this->input->post('title'))
			$title = $this->input->post('title');

		$this->load->library('Exporter');
		$exporter = new Exporter();
		$exporter->setData($data);
		$exporter->setTitle($title);
		if($tipe == 'excel'){
			$exporter->asExcel();
		}elseif($tipe == "pdf"){
			$exporter->asPDF();
		}elseif($tipe == "csv"){
			$exporter->asCSV();
		}
	}
}
