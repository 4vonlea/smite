<?php

/**
 * @property Complimentary_program_m $ComProgramModel
 */
class Complimentary extends Admin_Controller
{
    protected $accessRule = [
        'index' => 'view',
        'grid' => 'view',
        'save' => 'insert',
        'delete' => 'delete'
    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model("Complimentary_program_m", "ComProgramModel");
    }

    public function index()
    {
        $this->layout->render('complimentary');
    }

    public function grid()
    {
        $grid = $this->ComProgramModel->gridData($this->input->get());
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode($grid));
    }


    public function grid_participant($id)
    {
        $this->load->model("Com_participant_m");
        $grid = $this->Com_participant_m->gridData($this->input->get(), ['filter' => ['program_id' => $id]]);
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode($grid));
    }


    public function save()
    {
        if ($this->input->method() != 'post')
            show_404("Page Not Found !");
        $post = $this->input->post();
        $status = false;
        if ($this->ComProgramModel->validate($post)) {
            if (isset($post['id'])) {
                $program = $this->ComProgramModel->findOne($post['id']);
            } else {
                $program = new Complimentary_program_m();
            }
            $program->setAttributes($post);
            $status = $program->save();
        }
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode(['status' => $status, 'validation' => $this->ComProgramModel->getErrors()]));
    }

    public function delete()
    {

        if ($this->input->method() != 'post')
            show_404("Page Not Found !");
        $message = "";
        $this->load->model("News_m");
        $post = $this->input->post();

        $status = $this->ComProgramModel->find()->where('id', $post['id'])->delete();
        if ($status == false)
            $message = "Failed to delete member, error on server !";
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode(['status' => $status, 'message' => $message]));
    }

    public function download_participant($id)
    {
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $this->load->model("Com_participant_m");
        $program = $this->ComProgramModel->findOne($id);
        $data = $this->Com_participant_m->find()->select("name,contact")->where("program_id", $program->id)->get()->result_array();
        $this->load->library('Exporter');
        $exporter = new Exporter();
        $exporter->setData($data)
            ->setTitle($program->name . " participant")
            ->setFilename($program->name . " participant")
            ->asExcel(['contact' => 'asPhone'], true);
    }

    public function save_participant()
    {
        if ($this->input->method() != 'post')
            show_404("Page Not Found !");
        $post = $this->input->post();
        $this->load->model("Com_participant_m");
        $status = false;
        if ($this->Com_participant_m->validate($post)) {
            if (isset($post['id'])) {
                $program = $this->Com_participant_m->findOne($post['id']);
            } else {
                $program = new Com_participant_m();
            }
            $post['member_id'] = $this->session->user_session['username'];
            $program->setAttributes($post);
            $status = $program->save();
        }
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode(['status' => $status, 'validation' => $this->Com_participant_m->getErrors()]));
    }

    public function delete_participant()
    {

        if ($this->input->method() != 'post')
            show_404("Page Not Found !");
        $message = "";
        $this->load->model("Com_participant_m");
        $post = $this->input->post();
        $status = $this->Com_participant_m->find()->where('id', $post['id'])->delete();
        if ($status == false)
            $message = "Failed to delete participant, error on server !";
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode(['status' => $status, 'message' => $message]));
    }
}
