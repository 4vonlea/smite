<?php


class Member extends Admin_Controller
{
    public function index(){
        $this->load->model('Category_member_m');
        $statusList = $this->Category_member_m->find()->select('*')->get()->result_array();
        $this->layout->render('member',['statusList'=>$statusList]);
    }

    public function add_status(){
        $this->load->model('Category_member_m');
        $data = $this->input->post('value');
        $return = [];
        foreach($data as $i=>$row){
            $model = null;
            if(isset($row['id']))
                $model = Category_member_m::findOne($row['id']);
            if($model == null)
                $model = new Category_member_m();
            $model->setAttributes($row);
            $model->save();
            $return[] = $model->toArray();
        }
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode($return));
    }

    public function remove_status(){
        $this->load->model('Category_member_m');
        $status = $this->Category_member_m->delete($this->input->post('id'));
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode(['status'=>$status]));
    }

    public function verify(){
    	if($this->input->method() != 'post')
    		show_404("Page Not Found !");
		$this->load->model("Member_m");
		$post = $this->input->post();
		$status = false;$message = '';
		if(isset($post['response'])) {
			$member = Member_m::findOne($post['id']);
			if ($post['response'] == 1)
				$member->verified_by_admin = 1;
			elseif ($post['response'] == 0) {
				$member->verified_by_admin = 1;
				$member->status = $post['status'];
			}
			$status = $member->save();
		}else{
			$message = "Response has not been set";
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status'=>$status,'message'=>$message]));
	}

	public function get_proof($id){
    	$this->load->model("Member_m");
    	$extension = explode("|",Member_m::$proofExtension);
    	foreach($extension as $ext) {
			$filepath = APPPATH . "uploads/proof/$id.$ext";
			if(file_exists($filepath)) {
				header('Content-Description: File Transfer');
				header('Content-Type: '.mime_content_type($filepath));
				header('Content-Disposition: attachment; filename="'.$id.'.'.$ext.'"');
				header('Expires: 0');
				header('Cache-Control: must-revalidate');
				header('Pragma: public');
				header('Content-Length: ' . filesize($filepath));
				flush(); // Flush system output buffer
				readfile($filepath);
				break;
			}
		}

	}

    public function grid()
    {
        $this->load->model('Member_m');

        $grid = $this->Member_m->gridData($this->input->get());
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode($grid));

    }

}
