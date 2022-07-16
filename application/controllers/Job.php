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

    public function send_unpaid_invoice($member_id,$transaction_id,$sleep = 0){
        if($sleep > 0){
            sleep($sleep);
        }
        exec("php index.php member payment check_payment $transaction_id");
        $this->load->model(["Transaction_m","Member_m","Notification_m"]);
        $tr = $this->Transaction_m->findOne(['id'=>$transaction_id]);
        $member = $tr->member;
        if($tr){
            if($member){
                $fullname = $member->fullname;
    			$email = $member->email;
            }else{
                $fullname = str_replace("REGISTER-GROUP :","",$tr->member_id);
    			$email = $tr->email_group;
            }
            $attc = [
                $fullname.'-invoice.pdf' => $tr->exportInvoice()->output(),
            ];
            $message = $this->load->view("template/email/send_unpaid_invoice",['fullname'=>$fullname],true);
            $this->Notification_m->sendMessageWithAttachment($email, 'Unpaid Invoice (MA)',$message, $attc);
        }
    }
    public function test($params,$params2){
        sleep(5);
        file_put_contents("./tes.json",$params." ".$params2);
    }
}