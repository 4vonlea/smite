<?php
class Job extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if(!is_cli()){
            die("Not From CLI");
        }
    }

    public function send_unpaid_invoice($member_id,$transaction_id){
        $this->load->model(["Transaction_m","Member_m","Notification_m"]);
        $tr = $this->Transaction_m->findOne(['id'=>$transaction_id]);
        $member = $this->Member_m->findOne(['id'=>$member_id]);
        file_put_contents("./tes.json",$member_id.$transaction_id);
        if($member){
            $attc = [
                $member->fullname.'-invoice.pdf' => $tr->exportInvoice()->output(),
            ];
            $message = $this->load->view("template/email/send_unpaid_invoice",$member->toArray(),true);
            $this->Notification_m->sendMessageWithAttachment($member->email, 'Unpaid Invoice (MA)',$message, $attc);
        }
    }
    public function test($params,$params2){
        sleep(5);
        file_put_contents("./tes.json",$params." ".$params2);
    }
}