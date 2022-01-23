<?php
class Notification_m extends MY_Model{
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
    
    protected $type = "email";

    const LIST_INSTANCE = [
        self::TYPE_EMAIL,self::TYPE_WA
    ];

    public function getValue($name,$isJson = false){
        $token = $this->findOne(['name'=>$name]);
		return ($token ? ($isJson ?  json_decode($token->value,true):$token->value):'{}');
	}

	public function setValue($name,$value){
		return $this->replace([
			'name'=>$name,
			'value'=>$value,
		]);
	}


    protected function getClass(){
        $sender = $this->getValue("site_title");
        switch ($this->getType()){
            case self::TYPE_GMAIL:
                $token = $this->getValue(Notification_m::SETTING_GMAIL_TOKEN);
                $email = $this->getValue(Notification_m::SETTING_GMAIL_ADMIN);
                $this->load->library("Gmail_api",[
                    'sender'=>$sender,
                    'token'=>$token,
                    'email'=>$email
                ]);
                $class = $this->gmail_api;
                return $class;
                break;
            case self::TYPE_MAILER:
                $setting = $this->getValue(Notification_m::SETTING_MAILER);
                $setting = json_decode($setting,true);
                $this->load->library("Mailer",array_merge($setting,['sender'=>$sender]));
                $class = $this->mailer;
                return $class;
                break;
            case self::TYPE_WA:
                $token = $this->getValue(Notification_m::SETTING_WA_TOKEN);
                $this->load->library("Whatsapp_api");
                $class = new Whatsapp_api();
                $class->setToken($token);
                return $class;
                break;
            default:
                return null;
        }
    }

    public function setDefaultMailer($value)
    {
        return $this->setValue("DEFAULT_MAILER",$value);
    }

    public function getDefaultMailer()
    {
        return $this->getValue("DEFAULT_MAILER");
    }

    public function getType(){
        return ($this->type == "email" ? $this->getDefaultMailer():$this->type);
    }

    public function setType($type){
        $this->type = $type;
    }
    /**
     * 
     */
    public function sendMessage($to,$subject,$message){
            $message = "#DO NOT REPLY THIS AUTOMATED MESSAGE#<br/>If reply, sent to admin@aomc-pinbanjarmasin2022.com <br/><br/><hr/>".$message;
            $message.="<br/>Best Regards,<br/>AOMC & PIN PERDOSSI 2022<br/>";
            $message.="<br/>Or Contact via WA:";
            $message.="<br/>Rida Sieseria, MD (085163683209) - Registration and information";
            $message.="<br/>Fachrurrazy, MD (08179400579) - Scientific Affair";
            $message.="<br/>Asnelia Devicaesaria, MD (085888885010) - Sponsorship Affair";    
        $class = $this->getClass();
        if($class)
            return $class->sendMessage($to,$subject,$message);
        return ['status'=>false,'code'=>'0','message'=>'Failed to initialized class'];
    }
    /**
     * 
     */
    public function sendMessageWithAttachment($to,$subject,$message,$attachment,$fname = ""){
        $message = "#DO NOT REPLY THIS AUTOMATED MESSAGE#<br/>If reply, sent to admin@aomc-pinbanjarmasin2022.com<br/><br/><hr/>".$message;
        $message.="<br/>Best Regards,<br/>AOMC & PIN PERDOSSI 2022<br/>";
        $message.="<br/>Or Contact via WA:";
        $message.="<br/>Rida Sieseria, MD (085163683209) - Registration and information";
        $message.="<br/>Fachrurrazy, MD (08179400579) - Scientific Affair";
        $message.="<br/>Asnelia Devicaesaria, MD (085888885010) - Sponsorship Affair";

        $class = $this->getClass();
        if($class)
            return $class->sendMessageWithAttachment($to,$subject,$message,$attachment,$fname);
        return ['status'=>false,'code'=>'0','message'=>'Failed to initialized class'];
    }
}