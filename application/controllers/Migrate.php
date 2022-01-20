<?php


class Migrate extends CI_Controller
{

    public function index()
    {
//        if (is_cli()) {
            $this->load->library(['migration']);
            var_dump($this->migration->latest());

            if ($this->migration->latest() === FALSE) {
                show_error($this->migration->error_string());
            }
//        }else{
//            echo "Not Found !";
//        }
    }

}