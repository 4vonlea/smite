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
        $this->layout->render('index',['user'=>$user]);
    }

    public function save_profile(){
        if($this->input->post()) {
            $post = $this->input->post();
            $user = Member_m::findOne(['username_account' => $this->session->user_session['username']]);
            $user->setAttributes($post);
            $this->output->set_content_type("application/json")
                ->_display(json_encode(['status' => $user->save()]));
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

    public function get_paper(){
        if($this->input->method() !== 'post')
            show_404("Page not found !");
        $this->load->model("Papers_m");
        $paper = Papers_m::findOne(['member_id'=>$this->session->user_session['id']]);
        if($paper)
            $this->output->set_content_type("application/json")
                ->_display(json_encode($paper->toArray()));
        else
            $this->output->set_content_type("application/json")
                ->_display('{"status":0}');

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
		if(!$detail){
			$detail = new Transaction_detail_m();
		}
		if($this->Event_m->validateFollowing($data['id'],$this->session->user_session['status_name'])) {
			$detail->event_pricing_id = $data['id'];
			$detail->transaction_id = $transaction->id;
			$detail->price = $data['price'];
			$detail->member_id = $this->session->user_session['id'];
			$detail->product_name = "$data[event_name] ($data[member_status])";
			$detail->save();
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

    public function upload_paper(){
        if($this->input->method() !== 'post')
            show_404("Page not found !");

        $config['upload_path']          = APPPATH.'uploads/papers/';
        $config['allowed_types']        = 'pdf|doc|docx';
        $config['max_size']             = 5120;
        $config['overwrite']             = true;
        $config['file_name']        = $this->session->user_session['id'];

        $this->load->library('upload', $config);
        $this->load->model("Papers_m");
        $upload = $this->upload->do_upload('file');
        $validation = $this->Papers_m->validate($this->input->post());
        if($upload && $validation){
            $paper = Papers_m::findOne(['member_id'=>$this->session->user_session['id']]);
            if(!$paper)
                $paper = new Papers_m();
            $data = $this->upload->data();
            $paper->member_id = $this->session->user_session['id'];
            $paper->filename = $data['file_name'];
            $paper->status = 1;
            $paper->title = $this->input->post('title');
            $paper->save();
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
}
