<?php


class Whatsapp_api implements iNotification
{
	protected $primaryKey = "name";
	protected $table = "settings";

	const NAME_SETTINGS = "wa_api_token";

	public function getToken(){
		$token = $this->findOne(['name'=>self::NAME_SETTINGS]);
		return ($token ? $token->value:null);
	}

	/**
	 * @param $token
	 * @return bool
	 */
	public function setToken($token){
		return $this->replace([
			'name'=>self::NAME_SETTINGS,
			'value'=>$token
		]);
	}

	protected function composeRequest($phone,$message,$url)
	{
		$curl = curl_init();
		$token = $this->getToken();
		$data = [
			'phone' => $phone,
			'message' => $message,
		];

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

		return json_decode($result,true);
	}

	public function sendMessage($to, $subject, $message)
	{
		if($to[0] === "0"){
			$to = "62".substr($to,1,strlen($to));
		}
		$url = "https://wablas.com/api/send-message";
		$message = "*".$subject."*\n\n".$message;
		$result = $this->composeRequest($to,$message,$url);
		return $result['status'];
	}

    public function sendMessageWithAttachment($to,$subject,$message,$attachment,$fname = ""){

    }
}
