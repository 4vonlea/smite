<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer implements iNotification
{
    protected $mail;
    protected $from;
    protected $sender;
    public function __construct($params)
    {
        //Server settings
        $mail = new PHPMailer(true);
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $params['smtp_host'];                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $params['email'];                     // SMTP username
        $mail->Password   = $params['password'];                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = $params['smtp_port'];
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $this->mail = $mail;
        $this->sender = $params['sender'];
        $this->from = $params['email'];
        var_dump($params);
    }
    public function sendMessage($to, $subject, $message)
    {
        try {
            $this->mail->setFrom($this->from, $this->sender);
            $this->mail->addAddress($to);              
            $this->mail->isHTML(true);                                 
            $this->mail->Subject = $subject;
            $this->mail->Body    = $message;
            $response['status'] = $this->mail->send();
        } catch (Exception $e) {
            $response['status'] = false;
            $response['code'] = $e->getCode();
            $response['message'] = $e->getMessage();
        }
        return $response;
    }

    public function sendMessageWithAttachment($to, $subject, $message, $attachment, $fname = "")
    {
        try {
            $attc = [];
            if(!is_array($attachment)){
                $attc[$fname] = $attachment;
            }else{
                $attc = $attachment;
            }
            $this->mail->setFrom($this->from, $this->sender);
            $this->mail->addAddress($to);               // Name is optional
            // Attachments
            foreach($attc as $name=>$atc){
                $this->mail->addStringAttachment($atc,$name);         // Add attachments
            }

            // Content
            $this->mail->isHTML(true);                                  // Set email format to HTML
            $this->mail->Subject = $subject;
            $this->mail->Body    = $message;

            $response['status'] = $this->mail->send();
        } catch (Exception $e) {
            $response['status'] = false;
            $response['code'] = $e->getCode();
            $response['message'] = $e->getMessage();
        }
        return $response;
    }
}
