<?php

class Api_perdossi
{
    const TOKEN_FILE = "token_perdossi.json";
    private $ci;

    public function __construct($params = array()){
		$this->ci =& get_instance();
	}

    public function getToken(){
        $lastToken = (file_exists(APPPATH."cache/".self::TOKEN_FILE)) ? json_decode(file_get_contents(APPPATH."cache/".self::TOKEN_FILE),true) : false;
        if($lastToken == false || time() > $lastToken['expired']){
            $data['user_name'] = $this->ci->config->item("perdossi_username");
            $data['password'] = $this->ci->config->item("perdossi_password");
            $response = $this->sendRequest('login',$data);
            if(isset($response['token'])){
                $lastToken['token'] = $response['token'];
                $lastToken['expired'] = time()+60*60*23;
                file_put_contents(APPPATH."cache/".self::TOKEN_FILE,json_encode($lastToken));
                return $lastToken;
            }
        }
        return $lastToken;
    }

    public function getMemberByNIK($nik){
        $token = $this->getToken();
        if($token !== false)
            return $this->sendRequest('memberbynik',['nik'=>$nik],$token['token']);
        return ['message'=>'Token not found or expired'];
    }


    
    public function sendRequest($path,$data,$token = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://dev-p2kb.perdosni.org/api/'.$path,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer '.$token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true) ?? ['status'=>false,'message'=>'Gagal menghubungi server P2KB'];
    }
}
