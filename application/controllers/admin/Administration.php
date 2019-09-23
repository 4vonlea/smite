<?php


class Administration extends Admin_Controller
{
	public function index()
	{
		$this->load->model(['Event_m']);
		$this->layout->render("administration",[
			'event'=>Event_m::asList(Event_m::findAll(),'id','name')
		]);
	}
}
