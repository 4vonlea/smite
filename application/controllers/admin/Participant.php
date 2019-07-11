<?php


class Participant extends Admin_Controller
{
    public function index(){
        $this->layout->render('participant');
    }

    public function grid()
    {
        $this->load->model('Participant_m');

        $grid = $this->Participant_m->gridData($this->input->get());
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode($grid));

    }

}