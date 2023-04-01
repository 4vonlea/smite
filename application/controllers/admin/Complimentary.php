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


    public function save()
    {
        if ($this->input->method() != 'post')
            show_404("Page Not Found !");
        $post = $this->input->post();

        if (isset($post['id'])) {
            $program = $this->ComProgramModel->findOne($post['id']);
        } else {
            $program = new Complimentary_program_m();
        }
        $program->setAttributes($post);
        $status = $program->save();
        $this->output
            ->set_content_type("application/json")
            ->_display(json_encode(['status' => $status, 'data' => $program->toArray()]));
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
}
