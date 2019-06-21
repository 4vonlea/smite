<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @property Layout $layout
 */
class Site extends CI_Controller {

    public function __construct(){
        parent::__construct();
        $this->layout->setLayout("layouts/porto");
    }

    public function index()
	{
		$this->layout->render('site/home');
	}

	public function certificate(){
        $this->layout->render('site/certificate');
    }

    public function simposium(){
        $this->layout->render('site/simposium');
    }

    public function schedules(){
        $this->layout->render('site/schedules');
    }

    public function download(){
        $this->layout->render('site/download');
    }

    public function login(){
        $this->layout->render('site/login');
    }

    public function register(){
        $this->layout->render('site/register');
    }

    public function forget(){
        $this->layout->render('site/forget');
    }

    public function committee(){
        $this->layout->render('site/committee');
    }

    public function contact(){
        $this->layout->render('site/contact');
    }

    public function paper(){
        $this->layout->render('site/paper');
    }
}
