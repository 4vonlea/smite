<?php


class Dashboard extends Admin_Controller
{

    public function index(){
        $this->layout->render("dashboard");
    }
}