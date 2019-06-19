<?php


class Participant extends Admin_Controller
{
    public function index(){
        $this->layout->render('participant');
    }

}