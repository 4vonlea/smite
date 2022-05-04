<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Layout $layout
 */
class Api extends MY_Controller
{

    const CODE_OK = 200;
    const CODE_UNAUTHORIZED = 401;
    const CODE_ERROR = 500;
    const CODE_BAD_REQUEST = 400;

    const KEY = "KeyToVerifiySmite12#12";

    public function auth(){
        $this->load->model("User_account_m");
        $username   = $this->input->post('email');
        $password   = $this->input->post('password');
        if($username && $password){
            if (User_account_m::verify($username, $password)) {
                $user = $this->User_account_m->findWithBiodata($username);
                $createdTime = time();
                $this->send_response(self::CODE_OK,"Authentication Success",[
                    'email'=>$user['email'],
                    'fullname'=>$user['fullname'],
                    'gender'=>$user['gender'] == "F" ? "Female":"Male",
                    'phone'=>$user['phone'],
                    'statusAs' => $user['status_name'],
                    'institution' => $user['univ_nama'],
                    'token'=>base64_encode(json_encode([
                            'memberId'=>$user['id'],
                            'created'=>$createdTime,
                            'signature'=>$this->sign($user['id'],$createdTime)
                        ],
                    )
                    )
                ]);
            }else{
                $this->send_response(self::CODE_BAD_REQUEST,"Invalid email or password",[]);
            }
        }else{
            $this->send_response(self::CODE_BAD_REQUEST,"email and password required",[]);
        }
    }

    public function user_event(){
        $token = $this->input->post("token");
		$this->load->model('Event_m');
        if($token){
            if($this->verify_sign($token)){
                $decodedBase64 = base64_decode($token);
                $jsonDecoded = json_decode($decodedBase64,true);
        		$result = $this->Event_m->getParticipant(
                    "t.id as event_id,t.name as event_name,t.kategory as event_kategory,t.held_on as event_held_on,t.held_in as event_held_in,t.theme as event_theme"
                )->where('m.id', $jsonDecoded['memberId'])
                ->get();
                $this->send_response(self::CODE_OK,"Success",$result->result_array());
            }else{
                $this->send_response(self::CODE_BAD_REQUEST,"Token Expired or Invalid",[]);
            }
        }else{
            $this->send_response(self::CODE_BAD_REQUEST,"Token Required",[]);
        }
    }

    public function event_list(){
		$this->load->model('Event_m');
        $result = $this->Event_m->getEventWithCountParticipant()->get();
        $this->send_response(self::CODE_OK,"Success",$result->result_array());
    }

    protected function sign($username,$createdTime){
        return hash('sha256',$username.$createdTime.self::KEY);
    }

    protected function verify_sign($token){
        $decodedBase64 = base64_decode($token);
        $jsonDecoded = json_decode($decodedBase64,true);
        return $jsonDecoded['signature'] == $this->sign($jsonDecoded['memberId'] ?? "-",$jsonDecoded['created'] ?? "-") 
                    && time() - $jsonDecoded['created'] < 60 * 60 * 24 * 30;
    }

    protected function send_response($code,$message,$data){
        $this->output->set_content_type("application/json")
        ->_display(json_encode([
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        ]));
    }
}