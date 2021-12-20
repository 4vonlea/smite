<?php

class Layout {
	public $layout = 'layouts/main';

    /**
     * @var CI_Controller
     */
	protected $ci;
	protected $menu_active;
	protected $additional_head;
    protected $base_view = "";
    protected $breadcrumb = "";
    protected $script_js;
	protected $theme = "default";

	public function __construct($params = array()){
		$this->ci =& get_instance();
		if(isset($params['layout']))
			$this->layout = $params['layout'];
	}

	 /**
     * @param $base string
     */
	public function setTheme($theme){
	    $this->theme = $theme;
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
     * @param $breadcrumb string
     */
	public function set_breadcrumb($breadcrumb){
	    $this->breadcrumb = $breadcrumb;
    }

    /**
     * Flag as a start js script
     */
    public function begin_script(){
	    ob_start();
    }

    /**
     * Flag as a end js script
     */
    public function end_script(){
	    $this->script_js = ob_get_clean();
    }
	/**
	 * Render Layout
	 * @param  String $view       Path File View
	 * @param  array  $data       array data untuk view
	 * @param  array  $dataLayout array data untuk layout
	 */
	public function render($view,$data = array(),$dataLayout = array()){
		$viewPath = $this->base_view.$view;
		$content = "";
		if(file_exists(APPPATH."views/".$viewPath.".php")){
			$content = $this->ci->load->view($viewPath,$data,true);
		}else{
			$viewPath = str_replace($this->theme,"default",$viewPath);
			$content = $this->ci->load->view($viewPath,$data,true);
		}

		$dataLayout['active_menu'] = (isset($dataLayout['active_menu'])) ? $dataLayout['active_menu']: $this->menu_active;
		$dataLayout['breadcrumb'] = ($this->breadcrumb == ""?str_replace("_"," ",$this->ci->router->class):$this->breadcrumb);
        $dataLayout['script_js'] = $this->script_js;
        $dataLayout['additional_head'] = $this->additional_head;

		$this->ci->load->view($this->layout,array_merge($dataLayout,array('content'=>$content)));
	}

	public function renderPartial($view,$data = array()){
		$this->ci->load->view($this->base_view.$view,$data);
	}

	public function renderAsJavascript($view,$data = array()){
		$viewPath = $this->base_view.$view;
		if(!file_exists(APPPATH."views/".$viewPath.".php")){
			$viewPath = str_replace($this->theme,"default",$viewPath);
		}
        $this->ci->output->set_content_type("application/javascript");
        $this->ci->load->view($viewPath,$data);
    }

	/**
	 * Flag as a start js script
	 */
	public function begin_head(){
		ob_start();
	}

	/**
	 * Flag as a end js script
	 */
	public function end_head(){
		$this->additional_head = ob_get_clean();
	}
}
