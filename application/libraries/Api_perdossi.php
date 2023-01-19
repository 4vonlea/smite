<?php

class Api_perdossi
{
    const TOKEN_FILE = "token_perdossi.json";
    private $ci;

    public function __construct($params = array()){
		$this->ci =& get_instance();
	}

    
    public function getMemberByNIK($nik){
        $token = $this->getToken();
        if($nik == null || trim($nik) == "")
            return ['status'=>false,'message'=>'NIK Tidak Boleh Kosong'];

        if($token !== false)
            return $this->sendRequest('memberbynik',['nik'=>$nik],$token['token']);
        return ['status'=>false,'message'=>'Token not found or expired'];
    }

    public function listAktivitas(){
        $token = $this->getToken();
        if($token !== false)
            return $this->sendRequest('aktivitas',[],$token['token']);
        return ['status'=>false,'message'=>'Token not found or expired'];
    }

    public function jenisAktivitas($aktivitasCode){
        $token = $this->getToken();
        if($token !== false)
            return $this->sendRequest('jenisaktivitas',['aktivitas_code'=>$aktivitasCode],$token['token']);
        return ['status'=>false,'message'=>'Token not found or expired'];
    }

    public function skp($roleCode){
        $token = $this->getToken();
        if($token !== false)
            return $this->sendRequest('getskp',['role_code'=>$roleCode],$token['token']);
        return ['status'=>false,'message'=>'Token not found or expired'];
    }

    public function insertKegiatan($data){
        $token = $this->getToken();
        if($token !== false){
            $response = $this->sendRequest('insertkegiatan',$data,$token['token']);
            return $response;
        }
        return ['status'=>false,'message'=>'Token not found or expired'];
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


    
    public function sendRequest($path,$data,$token = null)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://p2kb.perdosni.org/api/'.$path,
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
        $responseArray = json_decode($response, true) ;
        if($responseArray && !isset($responseArray['status']))
            $responseArray['status'] = true; 
        elseif($responseArray){
            $responseArray['statusResponse'] = $responseArray['status'];
            $responseArray['status'] = true;
        }

        return  $responseArray ?? ['status'=>false,'message'=>'Gagal menghubungi server P2KB'];
    }
}
