<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Layout $layout
 */
class Certificate extends MY_Controller
{

    private $theme;
    public function __construct()
    {
        parent::__construct();
        $this->theme = $this->config->item("theme");
        $this->layout->setTheme($this->theme);
        $this->layout->setLayout("layouts/$this->theme");
        $this->load->model('Event_m');
    }

    public function claim($hashedTransactionDetailsId = null){
        if($hashedTransactionDetailsId){
	    	$member = $this->Event_m->getParticipant()->where("sha1(td.id)",$hashedTransactionDetailsId)->get()->row_array();
            $this->Event_m->exportCertificate($member, $member['event_id'])->stream('certificate.pdf', array('Attachment' => 0));
        }else{
            $this->layout->render('site/' . $this->theme . '/claim_certificate', [
                'hasSession' => !$this->user_session_expired(),
            ]);
        }
    }

    public function get_transaction(){
        $id = $this->input->post("invoice_id");
		$this->load->model("Transaction_m");
		$data = $this->Transaction_m->getFollowedEvent($id);
		$status = count($data) > 0;
		$message = $status ? "Success in retrieving data" : "Nama atau Transaksi tidak ditemukan/belum selesai";
        foreach($data as $i=>$row){
            $data[$i]['id'] = sha1($row['id']);
        }
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$status,'data'=>$data,'message'=>$message]));
    }

}