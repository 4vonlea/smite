<?php
/**
 * Class MY_Controller
 * @property CI_Session $session
 * @property Layout $layout
 * @property CI_Config $config
 * @property CI_Loader $load
 * @property CI_Input $input
 * @property CI_Output $out
 */
class MY_Controller extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $this->load->helper('untung_helper');

        $language = $this->input->get("language");
        if($language){
            $this->session->set_userdata("language",$language);
        }else{
            $language = $this->session->language ?? $this->config->item("language");
        }
        $this->config->set_item('language',$language);
        $this->lang->load('page', $language);
    }

    protected function user_session_expired(){
        if($this->session->has_userdata('user_session') && isset($this->session->user_session['role'])){
            $sess = $this->session->userdata("sess_expired");
            if($sess && time() > $sess){
                $this->session->sess_destroy();
                return true;
            }

            if($this->session->has_userdata('rememberme')){
                $this->session->set_userdata('sess_expired', time()+60*60*24*7);
            }else{
                $this->session->set_userdata('sess_expired', time()+3600);
            }
            return false;
        }
        return true;
    }


	public static function formatdate($date)
	{
		$str = explode('-', $date);

		$bulan = array(
			'01' => 'Jan',
			'02' => 'Feb',
			'03' => 'Mar',
			'04' => 'Apr',
			'05' => 'May',
			'06' => 'Jun',
			'07' => 'Jul',
			'08' => 'Aug',
			'09' => 'Sep',
			'10' => 'Oct',
			'11' => 'Nov',
			'12' => 'Dec',
		);
		return $str['2'] . " " . $bulan[$str[1]] . " " . $str[0];
	}
}

/**
 * Class Admin_Controller
 * @property Access_control $Access_control
 */
class Admin_Controller extends MY_Controller {
	protected $accessRule = [];
	protected $roleID;
	protected $roleName;

    public function __construct(){
        parent::__construct();

        if($this->user_session_expired())
            redirect(base_url("admin/login"));

        if($this->session->user_session['role'] == '0'){
            redirect(base_url("member/area"));
        }
		$this->load->model(['Settings_m','Access_control','User_account_m']);

        $this->roleID = $this->session->user_session['role'];
        $this->roleName = User_account_m::$listRole[$this->roleID];

        $this->layout->setLayout("layouts/argon");
        $this->layout->setBaseView("admin/");

        if($this->getAccessRule($this->router->method) !== true && $this->Access_control->isAllowed($this->roleName,$this->router->class,$this->getAccessRule($this->router->method)) === false){
        	header("Message: You are prohibited from accessing this page");
        	show_error("You are prohibited from accessing this page",403,'Forbidden Access');
		}
    }

    protected function getAccessRule($method){
    	return (isset($this->accessRule[$method]) ? $this->accessRule[$method]:true);
	}
}
