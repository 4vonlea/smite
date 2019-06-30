<?php


class Gmail_api extends MY_Model
{
    protected $primaryKey = "name";
    protected $table = "settings";
    protected $client;
    const NAME_SETTINGS = "gmail_api";
    const EMAIL_ADMIN_SETTINGS = "email_admin";

    /**
     * @return array|null
     */
    public function getToken(){
        $token = $this->findOne(['name'=>self::NAME_SETTINGS]);
        return ($token ? json_decode($token->value,true):null);
    }

    public function saveToken($token){
        $this->replace([
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
        $client = new Google_Client();
        $client->setScopes([
            Google_Service_Gmail::GMAIL_SEND,
            Google_Service_Gmail::MAIL_GOOGLE_COM
        ]);
        $client->setAuthConfig(APPPATH.'config/google.client.oauth.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setRedirectUri(base_url()."admin/setting/token_auth");
        $token = $this->getToken();
        if($token){
            $client->setAccessToken($token);
            if($client->isAccessTokenExpired()){
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            }
        }
        return $client;
    }

    public function sendMessage(){
        $gmail = new Google_Service_Gmail($this->client);
        $gmail->users_messages->send();
        $gmail = new Google_Service_Gmail_Message();
    }
}