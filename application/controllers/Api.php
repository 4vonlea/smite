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
            if (User_account_m::verify($username, $password) || ($username == "dummy@email.com" && $password == "PassDummy123")) {
                if($username == "dummy@email.com"){
                    $user = $this->User_account_m->findMemberWithTransaction();
                    $user['email'] = "dummy@email.com";
                    $user['fullname'] = "Dummy Unreal";
                    $user['gender'] = "Male";
                    $user['phone'] = "6280000000000";
                    $user['univ_nama'] = "Unreal University";
                }else{
                    $user = $this->User_account_m->findWithBiodata($username);
                }
                if($user){
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
                    $this->send_response(self::CODE_BAD_REQUEST,"Email no registered as participant but as official",[]);
                }
            }else{
                $this->send_response(self::CODE_BAD_REQUEST,"Invalid email or password",[]);
            }
        }else{
            $this->send_response(self::CODE_BAD_REQUEST,"email and password required",[]);
        }
    }

    public function file($type, $id, $name)
	{
		$this->load->model("Papers_m");
		$paper = $this->Papers_m->findOne($id);
        if(!$paper)
			show_404('File not found on server !');

        $name = $type == 'presentation' ? $paper->poster : $paper->voice;
		$filepath = APPPATH . "uploads/papers/" . $name;

		if (file_exists($filepath)) {
			list(, $ext) = explode(".", $name);

			$dataTitle = explode(" ", $paper->title);
			$title = count($dataTitle) > 3 ? "{$dataTitle[0]} {$dataTitle[1]} {$dataTitle[2]}" : implode(" ", $dataTitle);

			$member = $paper->member;

			// $filename = $type . '-' . $paper->getIdPaper() . '-' . $member->fullname . '.' . $ext;
			$filename = "{$paper->getIdPaper()}-{$type}-{$member->fullname}-{$title}.{$ext}";
			header('Content-Description: File Transfer');
			header('Content-Type: ' . mime_content_type($filepath));
			header('Content-Disposition: attachment; filename="' . $filename . '"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($filepath));
			flush(); // Flush system output buffer
			readfile($filepath);
			exit;
		} else {
			show_404('File not found on server !');
		}
	}

    public function user_event(){
        $token = $this->input->post("token");
		$this->load->model(['Event_m','Papers_m']);
        if($token){
            if($this->verify_sign($token)){
                $decodedBase64 = base64_decode($token);
                $jsonDecoded = json_decode($decodedBase64,true);
        		$result = $this->Event_m->getParticipant(
                    "t.id as event_id,t.name as event_name,t.kategory as event_kategory,t.held_on as event_held_on,t.held_in as event_held_in,t.theme as event_theme"
                )->where('m.id', $jsonDecoded['memberId'])
                ->get();
                $papers = $this->Papers_m->find()->select("CONCAT(st.value,LPAD(papers.id,3,0)) as id,title,type_presence as presentationOn,status as statusAbstract,
                                                        status_fullpaper as statusFullpaper,status_presentasi as statusPresentation,category_paper.name as manuscriptSection,
                                                        papers.id as rawId,poster,voice,
                                                        methods as manuscriptCategory,type as manuscriptType,")
                                                    ->join("settings st",'st.name = "format_id_paper"',"left")
                                                    ->join("category_paper","category_paper.id = category","left")
                                                    ->where("member_id",$jsonDecoded['memberId'])->get()->result_array();
                $paperList = [];
                foreach($papers as $row){
                    $row['statusAbstract'] = Papers_m::$status[$row['statusAbstract']] ?? "-";
                    $row['statusFullpaper'] = Papers_m::$status[$row['statusFullpaper']] ?? "-";
                    $row['statusPresentation'] = Papers_m::$status[$row['statusPresentation']] ?? "-";
                    $row['filePresentation'] = base_url('api/file/presentation/'.$row['rawId'].'/'.$row['poster']);
                    $row['fileVoice'] = base_url('api/file/voice/'.$row['rawId'].'/'.$row['voice']);
                    unset($row['poster'],$row['voice']);
                    $paperList[] = $row;
                }
                $this->send_response(self::CODE_OK,"Success",[
                    'followedEvents'=>$result->result_array(),
                    'submitPaper' => $paperList,
                ]
                );
            }else{
                $this->send_response(self::CODE_BAD_REQUEST,"Token Expired or Invalid",[]);
            }
        }else{
            $this->send_response(self::CODE_BAD_REQUEST,"Token Required",[]);
        }
    }

    public function available_room(){
		$this->load->model('Room_m');
        $checkin = $this->input->post("checkin");
        $checkout = $this->input->post("checkout");
        $data = $this->Room_m->availableRoom($checkin,$checkout);
        $this->send_response(self::CODE_OK,"Success",$data);
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