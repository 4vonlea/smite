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

    public function job(){
        $result = run_job("job","send_unpaid_invoice",[
            '172f22cc-f041-48a3-ba6d-29ffc920345d',
            "INV-20220608-00034"
        ]);
        var_dump($result);
    }

}