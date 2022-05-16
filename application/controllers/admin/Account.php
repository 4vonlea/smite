<?php
/**
 * Class Account
 * @property User_account_m $User_account_m
 */

class Account extends Admin_Controller
{
	protected $accessRule = [
		'index'=>'view',
		'grid'=>'view',
		'delete'=>'delete',
		'reset'=>'update',
		'save'=>'insert',
	];
	public function index()
	{
		$this->load->model('User_account_m');
		$this->layout->render("account");
	}

	public function grid()
	{
		$this->load->model('User_account_m');
		$gridConfig = [];
		if($this->input->get("role") >= 0){
			$gridConfig = $this->User_account_m->gridConfig([
				'filter'=>[
					'role'=>$this->input->get('role')
				]
				]);
		}
		$grid = $this->User_account_m->gridData($this->input->get(),$gridConfig);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));

	}

	public function delete()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$this->load->model('User_account_m');
			$ex = $this->User_account_m->delete(['username' => $id]);
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status' => $ex, 'msg' => 'User account deleted successfully !']));

		} else {
			$this->output->set_status_header(403);
		}
	}

	public function reset()
	{
		if ($this->input->is_ajax_request()) {
			$id = $this->input->post('id');
			$password = rand(10000, 99999);
			$this->load->model('User_account_m');
			$ex = $this->User_account_m->update(['password' => password_hash($password, PASSWORD_DEFAULT)], ['username' => $id],false);
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status' => $ex, 'msg' => 'New Password is "' . $password . '"']));

		} else {
			$this->output->set_status_header(403);
		}
	}

	public function save()
	{
		if ($this->input->is_ajax_request()) {
			$this->load->model('User_account_m');
			$data = $this->input->post();
			if ($this->User_account_m->validate($data)) {
				$insert = [
					'username' => $data['username'],
					'name' => $data['name'],
					'password' => password_hash($data['password'], PASSWORD_DEFAULT),
					'role' => $data['role'],
					'token_reset' => "",
				];
				$ex = $this->User_account_m->insert($insert, false);
				$this->output->set_content_type("application/json")
					->_display(json_encode(['status' => $ex, 'msg' => 'Data saved successfully !']));
			} else {
				$error = $this->User_account_m->getErrors();
				$this->output->set_status_header(400)
					->set_content_type("application/json")
					->_display(json_encode(['status' => false, 'validation' => $error]));
			}
		}
	}

	public function access(){
		$this->load->helper('form');
		$this->layout->render("access_control");
	}

	public function access_data(){
		if($this->input->post()){
			$role = $this->input->post('role');
			$rs = $this->Access_control->find()->where("role",User_account_m::$listRole[$role])
				->select("role,module,group_concat(access) as access")
				->group_by("role,module")->get();
			$return = [];
			$index = [];
			$i = 0;
			foreach(scandir(APPPATH."controllers/admin/") as $file){
				$filename = str_replace(".php","",strtolower($file));
				if(!in_array($filename,['.','..','login'])) {
					$index[$filename] = $i;$i++;
					$return[] = [
						'role' => User_account_m::$listRole[$role],
						'module' => $filename,
						'access'=>[]
					];
				}
			}
			foreach($rs->result() as $row){
				$ind = $index[$row->module];
				$return[$ind]['access'] =explode(",",$row->access);
			}
			$this->output->set_content_type("application/json")
				->_display(json_encode(['status' => true, 'data'=>$return]));
		}
	}

	public function save_access(){
		$role = $this->input->post("role");
		$module = $this->input->post("module");
		$access = $this->input->post("access");
		$type = $this->input->post("type");
		if(strtolower($role) == 'superadmin' && strtolower($module) == 'account'){
			header("Message: Forbidden to change access for this module");
			show_error("Forbidden to change access for this module",403);
		}

		$model = $this->Access_control->findOne(['role'=>strtolower($role),'module'=>$module,'access'=>$access]);

		if($model == null && $type == 'insert') {
			$model = new Access_control();
			$model->setAttributes(['role'=>$role,'module'=>$module,'access'=>$access]);
			$model->save();
		}
		if($model != null && $type == 'delete') {
			$model->delete();
		}
	}
}
