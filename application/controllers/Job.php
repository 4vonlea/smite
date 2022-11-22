<?php
class Job extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!is_cli()) {
            die("Not From CLI");
        }
    }

    public function send_unpaid_invoice($member_id, $transaction_id, $sleep = 0)
    {
        if ($sleep > 0) {
            sleep($sleep);
        }
        exec("php index.php member payment check_payment $transaction_id");
        $this->load->model(["Transaction_m", "Member_m", "Notification_m"]);
        $tr = $this->Transaction_m->findOne(['id' => $transaction_id]);
        $member = $tr->member;
        if ($tr) {
            $this->Notification_m->sendInvoice($member, $tr);
            $this->Notification_m->setType(Notification_m::TYPE_WA)->sendInvoice($member, $tr);
        }
    }

    public function run_broadcast($id)
    {
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
            switch ($processData->type) {
                case Notification::TYPE_SENDING_NAME_TAG:
                    $this->load->model("Member_m");
                    $event_name = str_replace("Broadcast Nametag Event ", "", $processData->subject);
                    $this->Notification_m->setType($processData->channel);
                    while (!feof($sourceFile)) {
                        $rowRaw = fgets($sourceFile);
                        if ($rowRaw != false) {
                            $row = json_decode($rowRaw, true);
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
                            fwrite($resultFile, json_encode($row) . PHP_EOL);
                        }
                    }
                    break;
                case Notification::TYPE_SENDING_CERTIFICATE:
                    $this->load->model(["Notification_m", "Member_m", "Event_m", "Papers_m"]);
                    $event = json_decode($processData->message, true);
                    $this->Notification_m->setType($processData->channel);
                    while (!feof($sourceFile)) {
                        $rowRaw = fgets($sourceFile);
                        if ($rowRaw != false) {
                            $row = json_decode($rowRaw, true);
                            if (!is_numeric($event['id'])) {
                                $member = $row;
                                $cert = $this->Papers_m->exportCertificate($member)->output();
                                $row['feedback'] = $this->Notification_m->sendCertificate($member, Notification_m::CERT_TYPE_PAPER, "Manuscript", $cert);
                            } else {
                                $member = $this->Event_m->getParticipant()->where("m.id", $row['m_id'])->where("t.id", $event['id'])->get()->row_array();
                                $cert = $this->Event_m->exportCertificate($member, $event['id'])->output();
                                $row['feedback'] = $this->Notification_m->sendCertificate($member, Notification_m::CERT_TYPE_EVENT, $event['label'], $cert);
                                unset($cert);
                            }
                            fwrite($resultFile, json_encode($row) . PHP_EOL);
                        }
                    }
                    break;
                case Notification::TYPE_SENDING_CERTIFICATE_COM:
                    $this->load->model(["Notification_m", "Committee_attributes_m", "Event_m", "Papers_m"]);
                    $event = json_decode($processData->message, true);
                    $this->Notification_m->setType($processData->channel);
                    while (!feof($sourceFile)) {
                        $rowRaw = fgets($sourceFile);
                        if ($rowRaw != false) {
                            $row = json_decode($rowRaw, true);
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
                                if ($commiteMember->no_contact && $commiteMember->no_contact == "6282155708905") {
                                    $row['feedback']  = $this->Notification_m->setType(Notification_m::TYPE_WA)->sendCertificate($commiteMember, Notification_m::CERT_TYPE_EVENT, $com->event->name, $cert);
                                } else {
                                    $row['feedback'] = "Phone number not found";
                                }
                            }
                            $row['phone'] = $commiteMember->no_contact;
                            $row['email'] = $commiteMember->email;
                            $row['fullname'] = $commiteMember->fullname;

                            fwrite($resultFile, json_encode($row) . PHP_EOL);
                        }
                    }
                    break;
                case Notification::TYPE_SENDING_MESSAGE:
                    break;
                case Notification::TYPE_SENDING_MATERIAL:
                    break;
            }
            fclose($resultFile);
            fclose($sourceFile);
            $this->db->update("broadcast", ['status' => 'Finish'], ['id' => $id]);
        }
    }

    public function test($params, $params2)
    {
        sleep(5);
        file_put_contents("./tes.json", $params . " " . $params2);
    }
}
