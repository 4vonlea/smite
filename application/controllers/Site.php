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
}
