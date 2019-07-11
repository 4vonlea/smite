<?php


class Register extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->layout->setLayout("layouts/porto");
    }

    public function index(){
        $this->layout->render('member/register');

    }
}