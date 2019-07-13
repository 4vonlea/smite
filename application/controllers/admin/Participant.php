<?php


class Participant extends Admin_Controller
{
    public function index(){
        $this->load->model('Category_member_m');
        $statusList = $this->Category_member_m->find()->select('*')->get()->result_array();
        $this->layout->render('participant',['statusList'=>$statusList]);
    }

    public function add_status(){
        $this->load->model('Category_member_m');
        $model = new Category_member_m();
        $data = $this->input->post('value');
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