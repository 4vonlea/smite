<?php

class Layout {
	public $layout = 'layouts/main';

	protected $ci;
	protected $menu_active;
    protected $base_view = "";
	public function __construct($params = array()){
		$this->ci =& get_instance();
		if(isset($params['layout']))
			$this->layout = $params['layout'];
	}

    /**
     * @param $base string
     */
	public function setBaseView($base){
	    $this->base_view = $base;
    }

	/**
	 * Setting layout yang digunakan
	 * @param [String] $layout [path to layout]
	 */
	public function setLayout($layout){
		$this->layout = $layout;
	}

	/**
	 * Set Menu Active = nama menu di layout
	 * @param [type] $menu [description]
	 */
	public function set_active_menu($menu){
		$this->menu_active = $menu;
	}

	/**
	 * Render Layout
	 * @param  String $view       Path File View
	 * @param  array  $data       array data untuk view
	 * @param  array  $dataLayout array data untuk layout
	 */
	public function render($view,$data = array(),$dataLayout = array()){
		$content = $this->ci->load->view($this->base_view.$view,$data,true);
		$dataLayout['active_menu'] = (isset($dataLayout['active_menu'])) ? $dataLayout['active_menu']: $this->menu_active;
		$this->ci->load->view($this->layout,array_merge($dataLayout,array('content'=>$content)));
	}

	public function renderPartial($view,$data = array()){
		$this->ci->load->view($this->base_view.$view,$data);
	}
}