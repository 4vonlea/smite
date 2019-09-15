<?php


class Transaction extends Admin_Controller
{

    public function index(){
		$this->load->model("Transaction_m");
		$this->layout->render('transaction');
    }

    public function download($type,$id){
		$this->load->model('Transaction_m');
		$this->load->model("Gmail_api");
		$tr = $this->Transaction_m->findOne(['id'=>$id]);
		if($type == "invoice")
			$tr->exportInvoice()->stream();
		elseif($type == "proof")
			$tr->exportPaymentProof()->stream();
		else
			show_404();
	}
	public function grid()
	{
		$this->load->model('Transaction_m');

		$grid = $this->Transaction_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function detail(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model("Transaction_m");
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		if($detail){
			$response = $detail->toArray();
			$response['member'] = $detail->member->toArray();
			foreach($detail->details as $row){
				$response['details'][] = $row->toArray();
			}
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));

	}

	public function verify(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model("Transaction_m");
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		$detail->status_payment = Transaction_m::STATUS_FINISH;
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$detail->save()]));

	}

}
