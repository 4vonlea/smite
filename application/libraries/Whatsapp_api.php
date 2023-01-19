<?php


class Whatsapp_api implements iNotification
{
	protected $primaryKey = "name";
	protected $table = "settings";
	protected $token = "";
	const NAME_SETTINGS = "wa_api_token";

	public function getToken(){
		return $this->token;
	}

	/**
	 * @param $token
	 * @return bool
	 */
	public function setToken($token){
		return $this->token = $token;
	}

	protected function composeRequest($data,$url)
	{
		$curl = curl_init();
		$token = $this->getToken();

		curl_setopt($curl, CURLOPT_HTTPHEADER,
			array(
				"Authorization: $token",
			)
		);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($curl);
		curl_close($curl);
		return $result;
	}

	protected function convertHtmlToWa($message){
		$message = str_replace("<strong>","*",$message);
		$message = str_replace("</strong>","*",$message);
		$message = \Soundasleep\Html2Text::convert($message,[
			'drop_links'=>false,
		]);
		return $message;
	}

	public function sendMessage($to, $subject, $message)
	{
		if($to[0] === "0"){
			$to = "62".substr($to,1,strlen($to));
		}
		$url = "https://teras.wablas.com/api/send-message";
		$message = "*".$subject."*\n\n".$message;
		$data = [
			'phone' => $to,
			'message' => $this->convertHtmlToWa($message),
		];

		$result = $this->composeRequest($data,$url);
		$resJson = json_decode($result,true);
		return $resJson;
	}

    public function sendMessageWithAttachment($to,$subject,$message,$attachment,$fname = ""){

    }
}
