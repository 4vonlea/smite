<?php
/**
 * Class Area
 * @property Member_m $Member_m
 * @property User_account_m $User_account_m
 */

class Area extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        if($this->user_session_expired())
            redirect(base_url("site/login"));

        $this->layout->setLayout("layouts/porto");
        $this->layout->setBaseView('member/area/');
        $this->load->model(['Member_m','User_account_m']);

    }

    public function index(){
		$this->load->model("Transaction_m");
		$user = Member_m::findOne(['username_account'=>$this->session->user_session['username']]);
		if(!$user)
			show_error("Member not found in sistem or not registered yet !",500,"Member not found");
        $this->layout->render('index',['user'=>$user]);
    }

	public function card($event_id,$member_id)
	{
		$this->load->model('Member_m');
		$member = $this->Member_m->findOne($member_id);
		$member->getCard($event_id)->stream($member->fullname."-member_card.pdf");
	}

    public function save_profile(){
        if($this->input->post()) {
            $post = $this->input->post();
            $user = Member_m::findOne(['username_account' => $this->session->user_session['username']]);
            $user->setAttributes($post);
            $this->output->set_content_type("application/json")
                ->_display(json_encode(['status' => $user->save(false)]));
        }else{
            show_404("Page not found !");
        }
    }

    public function reset_password(){
        if($this->input->post()){
            $this->load->model('User_account_m');
            $this->load->library('form_validation');

            $this->form_validation->set_rules("confirm_password","Confirm Password","required|matches[new_password]")
                ->set_rules("new_password","New Password","required")
                ->set_rules("old_password","Old Password",[
                    'required',
                    ['verify_old',function($old_password){
                        return User_account_m::verify($this->session->user_session['username'],$old_password);
                    }]
                ])->set_message("verify_old","Old Password Wrong !");

            if($this->form_validation->run()){
                $account = User_account_m::findOne(['username'=>$this->session->user_session['username']]);
                $account->password = password_hash($this->input->post('new_password'),PASSWORD_DEFAULT);
                $this->output->set_content_type("application/json")
                    ->_display(json_encode(['status'=>$account->save()]));

            }else{
                $this->output->set_content_type("application/json")
                    ->_display(json_encode($this->form_validation->error_array()));

            }
        }else{
            show_404("Page not found !");
        }
    }

    public function get_events(){
		if($this->input->method() !== 'post')
			show_404("Page not found !");
		$this->load->model("Event_m");
		$events = $this->Event_m->eventVueModel($this->session->user_session['id'],$this->session->user_session['status_name']);
		$this->output->set_content_type("application/json")
			->_display(json_encode(['status'=>true,'events'=>$events]));
	}

	public function delete_paper(){
		if($this->input->method() !== 'post')
			show_404("Page not found !");
		$data = $this->input->post();
		$this->load->model("Papers_m");
		$status = $this->Papers_m->delete(['id'=>$data['id']]);
		if(file_exists( APPPATH."uploads/papers/".$data['filename']) && is_file(APPPATH."uploads/papers/".$data['filename']))
			unlink( APPPATH."uploads/papers/".$data['filename']);
		if(file_exists( APPPATH."uploads/papers/".$data['feedback']) && is_file(APPPATH."uploads/papers/".$data['feedback']))
			unlink( APPPATH."uploads/papers/".$data['feedback']);

		$this->output->set_content_type("application/json")
			->_display(json_encode(['status'=>$status]));
	}

    public function get_paper(){
        if($this->input->method() !== 'post')
            show_404("Page not found !");
        $this->load->model("Papers_m");
        $papers = Papers_m::findAll(['member_id'=>$this->session->user_session['id']]);
		$response['abstractType'] = Papers_m::$typeAbstract;
		$response['status'] = Papers_m::$status;
		$response['data'] = [];
		foreach($papers as $paper){
			$temp = $paper->toArray();
			$temp['co_author'] = json_decode($temp['co_author'],true);
			$response['data'][] = $temp;
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));

    }

    public function add_cart(){
		if($this->input->method() !== 'post')
			show_404("Page not found !");

		$response = ['status'=>true];
		$data = $this->input->post();
		$this->load->model(["Transaction_m","Transaction_detail_m","Event_m"]);
		$this->Transaction_m->getDB()->trans_start();
		$transaction = $this->Transaction_m->findOne(['member_id'=>$this->session->user_session['id'],'checkout'=>0]);
		if(!$transaction) {
			$id = $this->Transaction_m->generateInvoiceID();
			$transaction = new Transaction_m();
			$transaction->id = $id;
			$transaction->checkout = 0;
			$transaction->status_payment = Transaction_m::STATUS_WAITING;
			$transaction->member_id = $this->session->user_session['id'];
			$transaction->save();
			$transaction->id = $id;
		}
		$detail = $this->Transaction_detail_m->findOne(['transaction_id'=>$transaction->id,'event_pricing_id'=>$data['id']]);
		$fee = $this->Transaction_detail_m->findOne(['transaction_id'=>$transaction->id,'event_pricing_id'=>0]);
		if(!$detail){
			$detail = new Transaction_detail_m();
		}
		$feeAlready = false;
		if(!$fee){
			$fee = new Transaction_detail_m();
		}else{
			$feeAlready = true;
		}

		if($this->Event_m->validateFollowing($data['id'],$this->session->user_session['status_name'])) {
			$detail->event_pricing_id = $data['id'];
			$detail->transaction_id = $transaction->id;
			$detail->price = $data['price'];
			$detail->member_id = $this->session->user_session['id'];
			$detail->product_name = "$data[event_name] ($data[member_status])";
			$detail->save();
			if($data['price'] > 0 && $feeAlready == false){
				$fee->event_pricing_id = 0;//$data['id'];
				$fee->transaction_id = $transaction->id;
				$fee->price = rand(100,500);//"6000";//$data['price'];
				$fee->member_id = $this->session->user_session['id'];
				$fee->product_name = "Unique Additional Price";
				$fee->save();
			}
		}else{
			$response['status'] = false;
			$response['message'] = "You are prohibited from following !";
		}
		$this->Transaction_m->getDB()->trans_complete();

		$this->output->set_content_type("application/json")
			->_display(json_encode($response));

	}


	public function get_transaction(){
		if($this->input->method() !== 'post')
			show_404("Page not found !");

		$this->load->model(["Transaction_m"]);
		$transactions = $this->Transaction_m->findAll(['member_id'=>$this->session->user_session['id']]);
		$response = ['status'=>true,'cart'=>null,'transaction'=>null];
		foreach($transactions as $trans){
			if($trans->checkout == 0){
				$response['current_invoice'] = $trans->id;
				foreach ($trans->details as $row){
					$response['cart'][] = $row->toArray();
				}
			}else{
				$detail = [];
				foreach ($trans->details as $row){
					$detail[] = $row->toArray();
				}
				$response['transaction'][] = array_merge($trans->toArray(),['detail'=>$detail]);
			}
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function delete_item_cart(){
		if($this->input->method() !== 'post')
			show_404("Page not found !");
    	$id = $this->input->post('id');
		$this->load->model(["Transaction_detail_m"]);
		$this->Transaction_detail_m->delete($id);
		$count = $this->Transaction_detail_m->find()->select("SUM(price) as c")
			->where('transaction_id',$this->input->post("transaction_id"))
			->where('event_pricing_id > ',"0")
			->get()->row_array();
		if($count['c'] == 0){
			$this->Transaction_detail_m->delete(['event_pricing_id'=>0,'transaction_id'=>$this->input->post("transaction_id")]);
		}
		$this->output->set_content_type("application/json")
			->_display('{"status":true}');
	}

    public function file($name){
        $filepath = APPPATH."uploads/papers/".$name;
        if(file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: '.mime_content_type($filepath));
            header('Content-Disposition: attachment; filename="'.$name.'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath));
            flush(); // Flush system output buffer
            readfile($filepath);
            exit;
        }
    }

    public function upload_fullpaper(){
    	$response = [];
		$this->load->library('upload');

		$configFullpaper = [
    		'upload_path'=>APPPATH.'uploads/papers/',
    		'allowed_types'=>'doc|docx|ods',
			'max_size'=>5120,
			'file_name'=>'fullpaper_'.date("Ymdhis"),
		];
    	$configPresentation = [
    		'upload_path'=>APPPATH.'uploads/papers/',
			'allowed_types'=>'jpg|jpeg|png|ppt|pptx',
			'max_size'=>5120,
			'file_name'=>'presentation_'.date("Ymdhis"),
		];
		$this->upload->initialize($configFullpaper);
		$statusFullpaper = $this->upload->do_upload('fullpaper');
		$dataFullpaper = $this->upload->data();
		$errorFullpaper = $this->upload->display_errors("","");
		$this->upload->initialize($configPresentation);
		$statusPresentation = $this->upload->do_upload('presentation');
		$dataPresentation = $this->upload->data();
		$errorPresentation = $this->upload->display_errors("","");
		if($statusFullpaper && $statusPresentation){
			$this->load->model("Papers_m");

			$paper = $this->Papers_m->findOne($this->input->post("id"));
			$paper->fullpaper = $dataFullpaper['file_name'];
			$paper->poster = $dataPresentation['file_name'];
			$response['status'] = $paper->save(false);
		}else{
			$response['status'] = false;
			$response['message']['fullpaper'] = $errorFullpaper;
			$response['message']['presentation'] = $errorPresentation;
		}
		$this->output->set_content_type("application/json")
			->_display(json_encode($response));
	}

    public function upload_paper(){
        if($this->input->method() !== 'post')
            show_404("Page not found !");

        $config['upload_path']          = APPPATH.'uploads/papers/';
        $config['allowed_types']        = 'pdf|doc|docx|ods';
        $config['max_size']             = 5120;
        $config['overwrite']             = true;
        $config['file_name']        = $this->session->user_session['id'];

        $this->load->library('upload', $config);
        $this->load->model("Papers_m");
        $upload = $this->upload->do_upload('file');
        $validation = $this->Papers_m->validate($this->input->post());
        if($upload && $validation){
            $paper = Papers_m::findOne(['id'=>$this->input->post('id')]);
            if(!$paper)
                $paper = new Papers_m();
            $data = $this->upload->data();
            $paper->member_id = $this->session->user_session['id'];
            $paper->filename = $data['file_name'];
            $paper->status = 1;
            $paper->title = $this->input->post('title');
            $paper->type = $this->input->post('type');
            $paper->introduction = $this->input->post('introduction');
            $paper->aims = $this->input->post('aims');
            $paper->methods = $this->input->post('methods');
            $paper->result = $this->input->post('result');
            $paper->result = $this->input->post('result');
            $paper->conclusion = $this->input->post('conclusion');
            $paper->reviewer = "";
            $paper->message = "";
            $paper->co_author = json_encode($this->input->post('co_author'));
			$paper->created_at = date("Y-m-d H:i:s");
            $paper->save();
            $paper->updated_at = date("Y-m-d H:i:s");
            $response['status'] = true;
            $response['paper'] = $paper->toArray();
        }else{
            $response['status'] = false;
            $response['message'] = array_merge($this->Papers_m->getErrors(),['file'=>$this->upload->display_errors("","")]);
        }

        $this->output->set_content_type("application/json")
            ->_display(json_encode($response));

    }

    public function upload_image(){
        $user = Member_m::findOne(['username_account'=>$this->session->user_session['username']]);

        $config['upload_path']          = 'themes/uploads/profile/';
        $config['allowed_types']        = 'jpg|png|pdf';
        $config['max_size']             = 2048;
        $config['overwrite']             = true;
        $config['file_name']        = $user->id;

        $this->load->library('upload', $config);
        if($this->upload->do_upload('file')){
            $data = $this->upload->data();
            $user->image = $data['file_name'];
            $user->save();
            $response['status'] = true;
            $response['link'] = base_url("themes/uploads/profile/$data[file_name]");
        }else{
            $response['status'] = false;
            $response['message'] = $this->upload->display_errors("","");
        }

        $this->output->set_content_type("application/json")
            ->_display(json_encode($response));
    }

    public function page($name){
        $this->layout->renderAsJavascript($name.'.js');
    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('site');
    }

	public function upload_proof()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");
		$id = $this->input->post("invoice_id");
		$message = $this->input->post("message");
		$config['upload_path']          = APPPATH.'uploads/proof/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['max_size']             = 2048;
		$config['overwrite']             = true;
		$config['file_name']        = $id;

		$this->load->library('upload', $config);
		if($this->upload->do_upload('file_proof')){
			$data = $this->upload->data();

			$this->load->model(["Transaction_m","Gmail_api"]);
			$tran = $this->Transaction_m->findOne($id);
			$tran->client_message = $message;
			$tran->payment_proof =  $data['file_name'];
			$tran->status_payment = Transaction_m::STATUS_NEED_VERIFY;
			$data['status_payment'] =  Transaction_m::STATUS_NEED_VERIFY;
			$mem = $this->Member_m->findOne($tran->member_id);
			$response['status'] = $tran->save();
			$response['data'] = $data;
			if($response['status'] && Settings_m::getSetting("email_receive") != ""){
				$email_message = "$mem->fullname has upload a transfer proof with invoice id <b>$tran->id</b>";
				$file[$data['file_name']] = file_get_contents(APPPATH.'uploads/proof/'.$data['file_name']);
				$this->Gmail_api->sendMessageWithAttachment( Settings_m::getSetting("email_receive") ,'Notification Upload Transfer Proof by $mem->fullname',$email_message,$file);
				$emails = explode(",",Settings_m::getSetting("email_receive") );
				$email_message = "A Participant has upload a transfer proof with invoice id <b>$tran->id</b>";
				$file[$data['file_name']] = file_get_contents(APPPATH . 'uploads/proof/' . $data['file_name']);
				foreach($emails as $email) {
					$this->Gmail_api->sendMessageWithAttachment($email, 'Notification Upload Transfer Proof', $email_message, $file);
				}
			}
		}else{
			$response['status'] = false;
			$response['message'] = $this->upload->display_errors("","");
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function detail_transaction(){
		if($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model("Transaction_m");
		$id = $this->input->post('id');
		$detail = $this->Transaction_m->findOne($id);
		if($detail){
			$response = $detail->toArray();
			$response['member'] = $detail->member->toArray();
			$response['finish'] = $response['status_payment'] == Transaction_m::STATUS_FINISH;
			foreach($detail->details as $row){
				$response['details'][] = $row->toArray();
			}
		}
		$response['banks'] = json_decode(Settings_m::getSetting(Settings_m::MANUAL_PAYMENT),true);

		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));

	}

	public function download($type,$id){
		$this->load->model(['Transaction_m','Member_m']);
		$tr = $this->Transaction_m->findOne(['id'=>$id]);
		$member = $this->Member_m->findOne(['id'=>$tr->member_id]);
		if($type == "invoice")
			$tr->exportInvoice()->stream($member->fullname."-Invoice.pdf");
		elseif($type == "proof")
			$tr->exportPaymentProof()->stream($member->fullname."-Payment_Proof.pdf");
		else
			show_404();
	}

	public function count_followed_events(){
		$this->load->model(['Transaction_m','Member_m']);
		$c = $this->Member_m->countFollowedEvent($this->session->user_session['id']);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>true,'count'=>$c]));
	}
}
