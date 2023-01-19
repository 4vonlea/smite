<?php
class Notification_m extends MY_Model
{
    public const TYPE_WA = "wa";
    public const TYPE_EMAIL = "email";
    public const TYPE_GMAIL = "gmail";
    public const TYPE_MAILER = "mailer";

    protected $primaryKey = "name";
    protected $table = "settings";

    const SENDER = 'site_title';

    public const SETTING_GMAIL_TOKEN = "gmail_api";
    public const SETTING_GMAIL_ADMIN = "email_admin";
    public const SETTING_WA_TOKEN = "wa_api_token";
    public const SETTING_MAILER = "mailer_setting";

    public const CERT_TYPE_EVENT = 1;
    public const CERT_TYPE_PAPER = 2;

    protected $type = "email";

    protected $multitype = ["email"];

    const LIST_INSTANCE = [
        self::TYPE_EMAIL, self::TYPE_WA
    ];

    public function getValue($name, $isJson = false)
    {
        $token = $this->findOne(['name' => $name]);
        if($isJson){
            return json_decode($token->value,true) ?? [];
        }
        return ($token ? ($isJson ?  json_decode($token->value, true) : $token->value) : ($isJson ? [] : '{}'));
    }

    public function setValue($name, $value)
    {
        return $this->replace([
            'name' => $name,
            'value' => $value,
        ]);
    }


    protected function getClass()
    {
        $sender = $this->getValue("site_title");
        switch ($this->getType()) {
            case self::TYPE_GMAIL:
                $token = $this->getValue(Notification_m::SETTING_GMAIL_TOKEN);
                $email = $this->getValue(Notification_m::SETTING_GMAIL_ADMIN);
                $this->load->library("Gmail_api", [
                    'sender' => $sender,
                    'token' => $token,
                    'email' => $email
                ]);
                $class = $this->gmail_api;
                return $class;
                break;
            case self::TYPE_MAILER:
                $setting = $this->getValue(Notification_m::SETTING_MAILER);
                $setting = json_decode($setting, true);
                $this->load->library("Mailer", array_merge($setting, ['sender' => $sender]));
                $class = $this->mailer;
                return $class;
                break;
            case self::TYPE_WA:
                $this->load->library("Wappin", [
                    'clientId' => $this->config->item("wappin_client_id"),
                    'projectId' => $this->config->item("wappin_project_id"),
                    'secretKey' => $this->config->item("wappin_secret_key"),
                ]);
                $class = $this->wappin;
                return $class;
                break;
            default:
                return null;
        }
    }

    public function setDefaultMailer($value)
    {
        return $this->setValue("DEFAULT_MAILER", $value);
    }

    public function getDefaultMailer()
    {
        return $this->getValue("DEFAULT_MAILER");
    }

    public function getType()
    {
        return ($this->type == "email" ? $this->getDefaultMailer() : $this->type);
    }

    public function setType($type)
    {
        $this->type = $type;
        $this->multitype = [$type];
        return $this;
    }

    public function setMultitype($types){
        $this->multitype = $types;
        return $this;
    }
    /**
     * 
     */
    public function sendMessage($to, $subject, $message)
    {
        $message = $this->wrapMessage($message);
        $subject = Settings_m::getSetting('site_title') . " - " . $subject;
        foreach($this->multitype as $type){
            $this->type = $type;
            $class = $this->getClass();
            if ($class)
                return $class->sendMessage($to, $subject, $message);
        }
        return ['status' => false, 'code' => '0', 'message' => 'Failed to initialized class'];
    }
    /**
     * 
     */
    public function sendMessageWithAttachment($to, $subject, $message, $attachment, $fname = "")
    {
        $message = $this->wrapMessage($message);
        $class = $this->getClass();
        $subject = Settings_m::getSetting('site_title') . " - " . $subject;
        if ($class)
            return $class->sendMessageWithAttachment($to, $subject, $message, $attachment, $fname);
        return ['status' => false, 'code' => '0', 'message' => 'Failed to initialized class'];
    }
    
    public function sendExpiredTransaction($member,$transaction_id){
        $member = is_array($member) ? (object) $member : $member;
        $response = null;
        $subject = "Transaction Expired $transaction_id";
        $message = $this->load->view("template/email/expired_transaction",['nama'=>$member->fullname],true);
        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        $response = $this->sendMessage($to, $subject, $message);
        return $response;
    }

    public function sendInvoice($member, $transaksi)
    {
        $member = is_array($member) ? (object) $member : $member;
        $response = null;
        $subject = "Unpaid Invoice (MA)";
        $attc = [
            $member->fullname . '-invoice.pdf' => $transaksi->exportInvoice()->output(),
        ];
        $message = $this->load->view("template/email/send_unpaid_invoice", ['fullname' => $member->fullname], true);
        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        $response = $this->sendMessageWithAttachment($to, $subject, $message, $attc);
        return $response;
    }

    public function sendPaymentProof($member, $transaksi)
    {
        $member = is_array($member) ? (object) $member : $member;
        $response = null;
        $subject = "Registration Proof";
        if ($transaksi->member) {
            $email = $member->email;
        } else {
            $email = $transaksi->email_group;
        }
        $attc = [
            "Registration Proof.pdf" => $transaksi->exportPaymentProof()->output(),
        ];
        $to = $this->getType() == self::TYPE_WA ? $member->phone : $email;
        $message = $this->load->view("template/email/send_payment_proof", [], true);
        $response = $this->sendMessageWithAttachment($to, $subject, $message, $attc);
        return $response;
    }

    public function sendInfoPaper($template, $member, $status, $type, $paper)
    {
        $member = is_array($member) ? (object) $member : $member;
        $response = null;
        $subject = "Review Result " . $paper->getIdPaper();
        $this->load->model("Papers_m");
        $parameter = $paper->toArray();
        $parameter['fullname'] = $member->fullname;
        $parameter['paperId'] = $paper->getIdPaper();
        $parameter['institution'] = $member->institution->univ_nama;
        $parameter['statusAbstract'] = Papers_m::$status[$parameter['status']] ?? "-";
        $parameter['statusFullpaper'] = Papers_m::$status[$parameter['status_fullpaper']] ?? "-";
        $parameter['statusPresentation'] = Papers_m::$status[$parameter['status_presentasi']] ?? "-";
        $parameter['modePresentation'] = $parameter['type_presence'];
        $parameter['feedbackAbstract'] = $parameter['message'];
        $parameter['feedbackFullpaper'] = $parameter['feedback_fullpaper'];
        $parameter['feedbackPresentation'] = $parameter['feedback_presentasi'];
        $parameter['siteTitle'] = Settings_m::getSetting('site_title');
        $message = $this->load->view("template/email/" . $template, $parameter, true);

        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        if ($type == "fullpaper" && ($status == Papers_m::ACCEPTED || $status == Papers_m::REJECTED)) {
            $this->sendMessageWithAttachment($to, $subject, $message, ['Abstract Announcement.pdf' => $paper->exportNotifPdf()->output()]);
        } else {
            $this->sendMessage($to, $subject, $message);
        }
        return $response;
    }

    public function sendCertificate($member, $type, $name, $certFile)
    {
        $member = is_array($member) ? (object) $member : $member;
        $response = null;
        $typeName = [
            self::CERT_TYPE_EVENT => 'Event',
            self::CERT_TYPE_PAPER => 'Manuscript',
        ];
        $templateList = [
            self::CERT_TYPE_EVENT => 'send_certificate_event',
            self::CERT_TYPE_PAPER => 'send_certificate_paper',
        ];

        $subject = "Certificate of " . $typeName[$type] ?? "";
        $template = $templateList[$type] ?? null;
        $message = $this->load->view("template/email/$template", [
            'event_name' => $name
        ], true);

        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        if ($template) {
            if($this->getType() == self::TYPE_WA){
                $bodyParams = [
                    '1'=>$member->fullname,
                    '2'=>$name,
                    '3'=>$name,
                    '4'=>'Mae',
                    '5'=>'+6287733667120'
                ];
                $response = $this->getClass()->sendTemplateMessageWithMedia($to,"new_send_certificate_event", $bodyParams, $certFile, "Certificate.pdf");
                $this->getClass()->sendTemplateMessage($to,"additional_footer_send_certificate",null,[]);
            }else{
                $response = $this->sendMessageWithAttachment($to, $subject, $message, $certFile, "certificate.pdf");
            }
        }
        return $response;
    }

    public function sendRegisteredByOther($member,$transaction,$participantsCategory)
    {
        $member = is_array($member) ? (object) $member : $member;
        $subject = "Registration Success";
        $data['participantsCategory'] = $participantsCategory;
        $message = $this->load->view('template/email/success_register_onsite',[
            'fullname'=>$member->fullname,
            'email'=>$member->email,
            'password'=>$member->password,
            'participantsCategory'=>$participantsCategory,
            'status'=>$member->status
        ], true);
        $attc = [
            $member->fullname . '-invoice.pdf' => $transaction->exportInvoice()->output(),
        ];
        if ($transaction->status_payment == Transaction_m::STATUS_FINISH) {
            $attc[$member->fullname. '-bukti_registrasi.pdf'] = $transaction->exportPaymentProof()->output();
        }
        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        $response = $this->sendMessageWithAttachment($to, $subject, $message, $attc);
        return $response;
    }
 
    public function sendEmailConfirmation($member, $token)
    {
        $member = is_array($member) ? (object) $member : $member;
        $subject = "Account Confirmation";
        $message = $this->load->view('template/email/email_confirmation', [
            'token' => $token,
            'name' => $member->fullname,
        ], true);

        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        $response = $this->sendMessage($to, $subject, $message);
        return $response;
    }

    public function sendNametag($member, $nametagFile,$event_name)
    {
        $member = is_array($member) ? (object) $member : $member;
        $response = null;
        $subject = "Nametag";
        $attc = [
            $event_name.'-nametag.pdf' => $nametagFile,
        ];
        $message = $this->load->view("template/email/send_nametag", ['event_name' => $event_name], true);
        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        $response = $this->sendMessageWithAttachment($to, $subject, $message, $attc);
        return $response;
    }

    public function sendForgetPassword($member, $newPassword)
    {
        $member = is_array($member) ? (object) $member : $member;
        $subject = Settings_m::getSetting('site_title') . " - Reset password account";
        $response = null;
        $message = $this->load->view('template/email/success_forget_password', ['password' => $newPassword], true);
        $to = $this->getType() == self::TYPE_WA ? $member->phone : $member->email;
        $response = $this->sendMessage($to, $subject, $message);
        return $response;
    }

    protected function wrapMessage($message)
    {
        if ($this->getType() == self::TYPE_WA)
            return $message;

        $urlImageFooter = "";
        $urlImageHeader = "";
        if ($this->getType() == self::TYPE_GMAIL) {
            $urlImageFooter = base_url('themes/img/email_footer.jpg'); // base_url('themes/img/email_footer.jpg');//file_get_contents(APPPATH."../themes/img/email_footer_64.txt");
            $urlImageHeader = base_url('themes/img/email_header.jpg'); //file_get_contents(APPPATH."../themes/img/email_header_64.txt");
        } elseif ($this->getType() == self::TYPE_MAILER) {
            $urlImageFooter =  "cid:img_email_footer"; //base_url('themes/img/email_footer.jpg');
            $urlImageHeader = "cid:img_email_header"; //base_url('themes/img/email_header.jpg');
        }

        $wrapperMessage = "<body style='background-color:#f6f6f6';><strong>#Email ini tidak dipantau, mohon jangan dibalas#</strong><br/><br/><hr/>";
        $wrapperMessage .= "<div style='padding:10px;width:45vw;font-size:12pt;background-color:white;margin-right:auto;margin-left:auto;'><br/><img width='100%' src='" . $urlImageHeader . "'/>";
        $wrapperMessage .= $message;
        $wrapperMessage .= "<img width='100%' src='" . $urlImageFooter . "'/><br/></div>";
        $wrapperMessage .= "</body>";
        return $wrapperMessage;
    }
}
