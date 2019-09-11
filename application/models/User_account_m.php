<?php
/**
 * Class User_account_m
 * @property $username
 * @property $password
 * @property $role
 * @property $reset_token
 */
class User_account_m extends MY_Model
{
    protected $table = "user_accounts";
    protected $primaryKey = "username";

    const ROLE_MEMBER = 0;
    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_ADMIN_PAPER = 3;

    public static $listRole = [
        self::ROLE_MEMBER => 'Member/Participant',
        self::ROLE_SUPERADMIN => 'Superadmin',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_ADMIN_PAPER => 'Admin Paper',
    ];

    public function rules()
	{
		return [
			['field' => 'username','label'=>'Username', 'rules' => 'required|max_length[100]|is_unique[user_accounts.username]'],
			['field' => 'password','label'=>'Password', 'rules' => 'required|max_length[100]'],
			['field' => 'name','label'=>'name', 'rules' => 'required|max_length[100]'],
			['field' => 'confirm_password','label'=>'Confirm Password','rules' => 'required|matches[password]'],
			['field' => 'role','label'=>'Role', 'rules' => 'required'],
		];
	}

	public function gridConfig()
	{
		return [
			'select'=>['username'=>'t.username','role'=>'role','username_'=>'username'],
		];
	}

	public static function verify($username,$password){
        $user = self::findOne(['username'=>$username]);
        if($user){
            return password_verify($password,$user->password);
        }
        return false;
    }

    public function findWithBiodata($username){
        return $this->setAlias("t")->find()->select("username,role,m.*,km.kategory as status_name")
			->where('username',$username)->join('members m','m.username_account = username')
			->join("kategory_members km","status = km.id","left")->get()->row_array();
    }

}
