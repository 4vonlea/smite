<?php


class Participant_m extends MY_Model
{
    protected $table = "members";

    public $fillable = ['id', 'image', 'email', 'fullname', 'gender', 'phone', 'birthday', 'country', 'region', 'city', 'address', 'username_account', 'status','verified_by_admin', 'verified_email',];

    public function rules()
    {
        return [
            ['field' => 'email', 'rules' => 'required|max_length[100]|valid_email'],
            ['field' => 'password', 'rules' => 'required|max_length[100]'],
            ['field' => 'confirm_password', 'rules' => 'required|matches[password]'],
            ['field' => 'status', 'rules' => 'required'],
            ['field' => 'fullname', 'rules' => 'required|max_length[100]'],
            ['field' => 'address', 'rules' => 'required'],
            ['field' => 'city', 'rules' => 'required'],
            ['field' => 'phone', 'rules' => 'required|numeric'],
            ['field' => 'birthday', 'rules' => 'required'],
        ];
    }
}