<?php


class Access_control extends MY_Model
{
	protected $table = "access_control";

	/**
	 * @param $roleName
	 * @param $module
	 * @param $access
	 * @return bool
	 */
	public function isAllowed($roleName,$module,$access){
		$roleName = strtolower($roleName);
		$module = strtolower($module);
		$access = strtolower($access);
		return ($this->find()->where(['role'=>$roleName,'module'=>$module,'access'=>$access])->count_all_results() == 1);
	}
}
