<?php


class Papers_m extends MY_Model
{
    protected $table = "papers";

    public function rules()
    {
        return [
            ['field' => 'title', 'rules' => 'required|max_length[255]'],
        ];
    }
}