<?php


class Gmail_api extends MY_Model implements iNotification
{
    protected $primaryKey = "name";
    protected $table = "settings";
    protected $client = null;

    const SENDER = 'site_title';
    const NAME_SETTINGS = "gmail_api";
    const EMAIL_ADMIN_SETTINGS = "email_admin";

    /**
     * @return array|null
     */
    public function getToken(){
        $token = $this->findOne(['name'=>self::NAME_SETTINGS]);
        return ($token ? json_decode($token->value,true):null);
    }

    /**
     * @return string
     */
    public function getEmail(){
        $email = $this->findOne(['name'=>self::EMAIL_ADMIN_SETTINGS]);
        return ($email ? $email->value:'');

    }

    /**
     * @return string
     */
    public function getSender(){
        $email = $this->findOne(['name'=>self::SENDER]);
        return ($email ? $email->value:'');

    }

	/**
	 * @param $token
	 * @return bool
	 */
    public function saveToken($token){
        return $this->replace([
            'name'=>self::NAME_SETTINGS,
            'value'=>json_encode($token)
        ]);
    }

    public function saveEmailAdmin($email){
        $this->replace([
            'name'=>self::EMAIL_ADMIN_SETTINGS,
            'value'=>$email
        ]);
    }

    /**
     * @return Google_Client
     * @throws Google_Exception
     */
    public function getClient(){
        if($this->client == null) {
            $this->client = new Google_Client();
            $this->client->setScopes([
                Google_Service_Gmail::GMAIL_SEND,
                Google_Service_Gmail::MAIL_GOOGLE_COM
            ]);
            $this->client->setAuthConfig(APPPATH . 'config/google.client.oauth.json');
            $this->client->setAccessType('offline');
            $this->client->setPrompt('select_account consent');
            $this->client->setRedirectUri(base_url() . "admin/setting/token_auth");
            $token = $this->getToken();
            if ($token) {
                $this->client->setAccessToken($token);
                if ($this->client->isAccessTokenExpired()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                }
            }
        }
        return $this->client;
    }

    public function sendMessage($to,$subject,$message){
        $from = $this->getEmail();
        $sender = $this->getSender();

        $service = new Google_Service_Gmail($this->getClient());
        $strSubject = $subject;
        $strRawMessage = "From:  $sender<".$from.">\r\n";
        $strRawMessage .= "To:  <".$to.">\r\n";
        $strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
        $strRawMessage .= "MIME-Version: 1.0\r\n";
        $strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
        $strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
        $strRawMessage .= $message."\r\n";
        // The message needs to be encoded in Base64URL
        $mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
        $msg = new Google_Service_Gmail_Message();
        $msg->setRaw($mime);
        $status = $service->users_messages->send("me", $msg);
    }

	public function sendMessageWithAttachment($to,$subject,$message,$attachment){
		$from = $this->getEmail();
		$sender = $this->getSender();
		$boundary = uniqid(rand(), true);
		$finfo = new finfo(FILEINFO_MIME);
		$mimeType = $finfo->buffer($attachment);
		$ext = "";
		if (strpos($mimeType, 'application/pdf') !== false) {
			$ext = "pdf";
		}
		$service = new Google_Service_Gmail($this->getClient());
		$strSubject = $subject;
		$strRawMessage = "From:  $sender<".$from.">\r\n";
		$strRawMessage .= "To:  <".$to.">\r\n";
		$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
		$strRawMessage .= "MIME-Version: 1.0\r\n";
		$strRawMessage .= 'Content-type: Multipart/Mixed; boundary="' . $boundary . '"' . "\r\n";
		$strRawMessage .= "\r\n--{$boundary}\r\n";
		$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
		$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
		$strRawMessage .= $message."\r\n";

		$strRawMessage .= "--{$boundary}\r\n";
		$strRawMessage .= 'Content-Type: '. $mimeType .'; name="official_receipt_payment.'.$ext.'";' . "\r\n";
		$strRawMessage .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";
		$strRawMessage .= base64_encode($attachment)."\r\n";

		// The message needs to be encoded in Base64URL
		$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
		$msg = new Google_Service_Gmail_Message();
		$msg->setRaw($mime);
		$status = $service->users_messages->send("me", $msg);
	}
}
