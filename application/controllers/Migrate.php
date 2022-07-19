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

    public function tes_range(){
        $this->load->model(["Transaction_m","Room_m"]);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-15','2022-07-16') == 0);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-16','2022-07-17') == 1);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-17','2022-07-18') == 3);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-18','2022-07-19') == 3);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-19','2022-07-20') == 1);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-15','2022-07-19') == 4);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-16','2022-07-18') == 3);
        var_dump($this->Transaction_m->countOverlapHotelBooking(1,'2022-07-15','2022-07-20') == 4);
        var_dump($this->Room_m->availableRoom('2022-07-15','2022-07-19'));

    }

}