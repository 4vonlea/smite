<?php

/**
 * @property CI_Input $input
 */
class Job extends CI_Controller
{
    protected $rawBody;

    public function __construct()
    {
        parent::__construct();
        $signature = $this->input->get_request_header("X-Token");
        $this->rawBody = file_get_contents('php://input');
        $this->jsonBody = json_decode($rawBody, true);
        $checkSignature = $signature === sha1(env("SIGNATURE_KEY") . $this->rawBody);
        if (!is_cli() && $checkSignature == false) {
            die("Not From CLI Or Authorize Client");
        }
        ini_set('memory_limit', '-1');
        set_time_limit(-1);
    }

    public function send_unpaid_invoice()
    {
        $transaction_id = $this->jsonBody['params']['id'] ?? "-";
        $sleep = $this->jsonBody['params']['sleep'] ?? 5;
        if ($sleep > 0) {
            sleep($sleep);
        }
        file_get_contents(base_url("member/payment/check_payment/$transaction_id"));
        $this->load->model(["Transaction_m", "Member_m", "Notification_m"]);
        $tr = $this->Transaction_m->findOne(['id' => $transaction_id]);
        $member = $tr->member;
        if ($tr) {
            if ($member) {
                $this->Notification_m->sendInvoice($member, $tr);
                $this->Notification_m->setType(Notification_m::TYPE_WA)->sendInvoice($member, $tr);
            } else {
                $fullname = explode(":", $tr->member_id)[1] ?? "-";
                $group = ['email' => $tr->email_group, "phone" => "0", "fullname" => $fullname];
                $this->Notification_m->sendInvoice($group, $tr);
            }
        }
    }

    public function run_broadcast()
    {
        $id = $this->jsonBody['params']['id'] ?? "-";
        ini_set('memory_limit', '-1');
        $this->load->database('job');
        $processData = $this->db->get_where("broadcast", ['id' => $id])->row();
        if ($processData) {
            $this->load->model("Notification_m");
            $this->db->update("broadcast", ['status' => 'On Proses'], ['id' => $id]);
            if (!file_exists($processData->attribute)) {
                $this->db->update("broadcast", ['status' => 'No file'], ['id' => $id]);
                return false;
            }
            $sourceFile = fopen($processData->attribute, 'r');
            $resultFile = fopen(APPPATH . "cache/broadcast/" . $id . "-result.json", 'w');
            require_once APPPATH . "controllers/admin/Notification.php";
            $this->load->model(["Notification_m", "Committee_attributes_m", "Event_m", "Papers_m", "Member_m"]);
            while (!feof($sourceFile)) {
                $rowRaw = fgets($sourceFile);
                if ($rowRaw != false) {
                    $row = json_decode($rowRaw, true);
                    if (!isset($row['before'])) {
                        switch ($processData->type) {
                            case Notification::TYPE_SENDING_NAME_TAG:
                                $event_name = str_replace("Broadcast Nametag Event ", "", $processData->subject);
                                $this->Notification_m->setType($processData->channel);
                                $member = $this->Member_m->findOne($row['member_id']);
                                $row['email'] = $member->email;
                                $row['phone'] = $member->phone;
                                $row['fullname'] = $member->fullname;
                                try {
                                    $card = $member->getCard($row['event_id'])->output();
                                    $row['feedback'] = $this->Notification_m->sendNametag($member, $card, $event_name);
                                } catch (Exception $ex) {
                                    $row['feedback'] = $ex->getMessage();
                                }
                                break;
                            case Notification::TYPE_SENDING_CERTIFICATE:
                                $event = json_decode($processData->message, true);
                                $this->Notification_m->setType($processData->channel);
                                if (!is_numeric($event['id'])) {
                                    $member = $row;
                                    $cert = $this->Papers_m->exportCertificate($member)->output();
                                    $row['feedback'] = $this->Notification_m->sendCertificate($member, Notification_m::CERT_TYPE_PAPER, "Certificate of Manuscript", $cert);
                                } else {
                                    $member = $this->Event_m->getParticipant()->where("m.id", $row['m_id'])->where("t.id", $event['id'])->get()->row_array();
                                    $cert = $this->Event_m->exportCertificate($member, $event['id'])->output();
                                    $row['feedback'] = $this->Notification_m->sendCertificate($member, Notification_m::CERT_TYPE_EVENT, $event['label'], $cert);
                                    unset($cert);
                                }
                                break;
                            case Notification::TYPE_SENDING_CERTIFICATE_COM:
                                $event = json_decode($processData->message, true);
                                $this->Notification_m->setType($processData->channel);
                                $com = $this->Committee_attributes_m->findOne($row['id']);
                                $commiteMember = $com->committee;
                                $commiteMember->phone = $commiteMember->no_contact;
                                $commiteMember->fullname = $commiteMember->name;
                                $cert = $com->exportCertificate()->output();
                                if ($processData->channel == Notification_m::TYPE_EMAIL) {
                                    if ($commiteMember->email) {
                                        $row['feedback']  = $this->Notification_m->sendCertificate($commiteMember, Notification_m::CERT_TYPE_EVENT, $com->event->name, $cert);
                                    } else {
                                        $row['feedback'] = "Email not found";
                                    }
                                }
                                if ($processData->channel == Notification_m::TYPE_WA) {
                                    if ($commiteMember->no_contact) {
                                        $row['feedback']  = $this->Notification_m->setType(Notification_m::TYPE_WA)->sendCertificate($commiteMember, Notification_m::CERT_TYPE_EVENT, $com->event->name, $cert);
                                    } else {
                                        $row['feedback'] = "Phone number not found";
                                    }
                                }
                                $row['phone'] = $commiteMember->no_contact;
                                $row['email'] = $commiteMember->email;
                                $row['fullname'] = $commiteMember->fullname;
                                break;
                            case Notification::TYPE_SENDING_MESSAGE:
                                break;
                            case Notification::TYPE_SENDING_MATERIAL:
                                break;
                        }
                    }
                    fwrite($resultFile, json_encode($row) . PHP_EOL);
                }
            }
            fclose($resultFile);
            fclose($sourceFile);
            $this->db->update("broadcast", ['status' => 'Finish'], ['id' => $id]);
        }
    }

    public function test()
    {
        sleep(60);
        file_put_contents("./tes.json", $this->rawBody . date("Y-m-d H:i:s"));
    }
}
