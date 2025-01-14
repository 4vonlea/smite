<?php

class Wappin implements iNotification
{

    private $clientId;
    private $projectId;
    private $secretKey;

    public function __construct($params = [])
    {
        $this->clientId = $params['clientId'] ?? "";
        $this->projectId = $params['projectId'] ?? "";
        $this->secretKey = $params['secretKey'] ?? "";
    }

    protected function htmlToWaText($html){
        $html = str_replace(["<br/>","<br />"],"\n",$html);
        $html = str_replace(["<strong>","</strong>"],"*",$html);
        $html = strip_tags($html);
        return $html;
    }

    protected function normalizeNumber($number){
        $number = str_replace(["-","+"],"\n",$number);
        if($number != "" && $number[0] == "0"){
            $number = "62".substr($number,1,strlen($number));
        }
        return trim($number);
    }

    protected function checkNumberRegistered($number,$subject,$message,$attachment,$fname){
        $ci =& get_instance();
        $row = $ci->db->where("phone_number",$number)->get("registered_wa")->row();
        $status = true;
        if($row == null || $row->status == 0){
            $status = false;
            $attachmentFile = [];
            $files = [];
            if (!is_array($attachment)) {
                $files[$fname] = $attachment;
            } else {
                $files = $attachment;
            }
            if (!file_exists(APPPATH . "cache/wappin"))
                mkdir(APPPATH . "cache/wappin");
            foreach($files as $name=>$data){
                if($data){
                    $filepath = APPPATH . "cache/wappin/".time().$name;
                    $attachmentFile[$name] = $filepath;
                    file_put_contents($filepath, $data);
                }
            }
            $ci->db->replace("registered_wa",[
                'phone_number'=>$number,
                'status'=>0,
                'hold_message'=>json_encode([
                    'subject'=>$subject,
                    'message'=>$message,
                    'attachment'=>$attachmentFile
                ])
            ]);
            $ci->load->model("Settings_m");
            $site = Settings_m::getSetting('site_title');
            $message = $this->sendTemplateMessage($number,"offer_notification",$site,["1"=>$site]);
            $date = date('Y-m-d H:i:s');
            $ci->db->insert('log_proses',array(
                'controller'=>"site",
                'username'=>$number,
                'request'=>json_encode($message),
                'query'=>"WAPPIN NOT REGISTERED",
                'date'=>$date,
            ));
        }
        return $status;
    }

    public function sendMessageOnly($to,$subject,$message){
        $to = $this->normalizeNumber($to);
        $response = $this->composeRequest([
            'client_id' => $this->clientId,
            'project_id' => $this->projectId,
            'message_content' => $this->htmlToWaText($message),
            'recipient_number' => $to
        ], "https://api.wappin.id/v1/message/do-send", "POST", true);
        $responseDecoded = json_decode($response, true);
        $responseDecoded['code'] = $responseDecoded['status'];
        $responseDecoded['status'] = $responseDecoded['status'] == "200";
        return $responseDecoded;
    }

    public function sendMessage($to, $subject, $message)
    {
        if($to == "")
            return ['status'=>false,'message'=>'Invalid Number'];
    
        $to = $this->normalizeNumber($to);
        if($this->checkNumberRegistered($to,$subject,$message,null,null) == false){
            return ['status'=>false,'message'=>'Number not registered'];
        }

        $this->sendTemplateMessage($to, "header_conversation",$subject, ['1'=>"https://wa.me/6289603215099"]);
        $response = $this->composeRequest([
            'client_id' => $this->clientId,
            'project_id' => $this->projectId,
            'message_content' => $this->htmlToWaText($message),
            'recipient_number' => $to
        ], "https://api.wappin.id/v1/message/do-send", "POST", true);
        $responseDecoded = json_decode($response, true);
        $responseDecoded['code'] = $responseDecoded['status'];
        $responseDecoded['status'] = $responseDecoded['status'] == "200";
        return $responseDecoded;
    }

    public function sendMessageWithAttachment($to, $subject, $message, $attachment, $fname = "")
    {
        $to = $this->normalizeNumber($to);
        if($this->checkNumberRegistered($to,$subject,$message,$attachment,$fname) == false){
            return ['status'=>false,'message'=>'Number not registered'];
        }

        $responseMessage = $this->sendMessage($to, $subject, $message);
        if($responseMessage['status']){
            $files = [];
            if (!is_array($attachment)) {
                $files[$fname] = $attachment;
            } else {
                $files = $attachment;
            }
            if (!file_exists(APPPATH . "cache/wappin"))
                mkdir(APPPATH . "cache/wappin");

            foreach ($files as $filename => $filebyte) {
                $responseFile = $this->sendMedia($to, $filename, $filebyte);
                $responseMessage['status'] = $responseMessage['status'] && $responseFile['status'];
                if($responseMessage["status"] == false){
                    $responseMessage['message'] .= ", ".$responseFile['message'];
                }
            }
        }
        return $responseMessage;
    }

    public function sendMedia($to, $filename, $filebyte)
    {
        $ci =& get_instance();
        $ci->load->helper("file");
        $mimetype = get_mime_by_extension($filename);
        if(strpos($mimetype,"image") === 0){
            $mediatype = "image";
        }elseif(strpos($mimetype,"audio") === 0){
            $mediatype = "audio";
        }else{
            $mediatype = "document";
        }
        $to = $this->normalizeNumber($to);
        if(is_string($filebyte) && @file_exists($filebyte)){
            $filepath = $filebyte;
        }else{
            $filepath = APPPATH . "cache/wappin/$filename";
            file_put_contents($filepath, $filebyte);
        }
        $response =  $this->composeRequest([
            'client_id' => $this->clientId,
            'project_id' => $this->projectId,
            'recipient_number' => $to,
            'media_type' => $mediatype,
            'media' => new CURLFile($filepath)
        ], "https://api.wappin.id/v1/message/do-send-media", "POST");
        unlink($filepath);
        $responseDecoded = json_decode($response, true);
        $responseDecoded['code'] = $responseDecoded['status'];
        $responseDecoded['status'] = $responseDecoded['status'] == "200";
        return $responseDecoded;
    }

    public function sendTemplateMessage($to, $template, $subject, $bodyParams)
    {
        $to = $this->normalizeNumber($to);
        $response = $this->composeRequest([
            'client_id' => $this->clientId,
            'project_id' => $this->projectId,
            'type' => $template,
            "language_code"=>"id",
            'recipient_number' => $to,
            'header' => $subject ? ['param' => substr($subject,0,60)] : [],
            'params' => $bodyParams,
        ], "https://api.wappin.id/v1/message/do-send-hsm", "POST", true);
        $responseDecoded = json_decode($response, true);
        $responseDecoded['code'] = $responseDecoded['status'];
        $responseDecoded['status'] = $responseDecoded['status'] == "200";
        return $responseDecoded;
    }

    public function sendTemplateMessageWithMedia($to, $template, $bodyParams,$filebyte,$filename)
    {
        $to = $this->normalizeNumber($to);
        $ci =& get_instance();
        $ci->load->helper("file");
        $mimetype = get_mime_by_extension($filename);
        if(strpos($mimetype,"image") === 0){
            $mediatype = "image";
        }elseif(strpos($mimetype,"audio") === 0){
            $mediatype = "audio";
        }else{
            $mediatype = "document";
        }

       $filepath = APPPATH . "cache/wappin/$filename";
       file_put_contents($filepath, $filebyte);
        $params = [
            'client_id' => $this->clientId,
            'project_id' => $this->projectId,
            'type' => $template,
            "language_code"=>"id",
            'recipient_number' => $to,
            'media_type' => $mediatype,
            'media' => new CURLFILE($filepath,$mimetype)
        ];
        $params['params'] = json_encode($bodyParams);
        $response = $this->composeRequest($params, "https://api.wappin.id/v1/message/do-send-hsm-with-media", "POST");
        $responseDecoded = json_decode($response, true);
        $responseDecoded['code'] = $responseDecoded['status'];
        $responseDecoded['status'] = $responseDecoded['status'] == "200";
        unlink($filepath);
        return $responseDecoded;
    }

    public function getToken()
    {
        $token = file_exists(APPPATH . "cache/wappin_token.json") ? json_decode(file_get_contents(APPPATH . "cache/wappin_token.json"), true) : [];
        $now = new DateTime();
        $tokenExpired = new DateTime($token['expired_datetime'] ?? null);

        if ($token == null ||  $now > $tokenExpired->sub(new DateInterval("PT30M"))) {
            $url = "https://api.wappin.id/v1/token/get";
            $auth = "Basic " . base64_encode($this->clientId . ":" . $this->secretKey);
            $response = $this->composeRequest([], $url, "POST", false, $auth);
            $jsonResponse = json_decode($response, true);
            if (isset($jsonResponse['data'])) {
                file_put_contents(APPPATH . "cache/wappin_token.json", json_encode($jsonResponse['data']));
                return $jsonResponse['data']['access_token'];
            }
        }
        return $token['access_token'] ?? null;
    }

    protected function composeRequest($data, $url, $method = "GET", $isJson = false, $token = null)
    {
        return '{"status":200,"access_token":"","code":200,"message":""}';
        $curl = curl_init();
        if ($token == null)
            $token = "Bearer ".$this->getToken();
        $headers = [
            "Authorization: $token",
        ];
        if ($isJson) {
            $headers[] = "Content-Type: application/json";
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        } else {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt($curl, CURLOPT_STDERR, fopen('php://stderr', 'w'));
        curl_setopt($curl, CURLOPT_ENCODING, '');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
