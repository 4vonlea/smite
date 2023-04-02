<?php


class Com_participant_m extends MY_Model
{
    protected $table = "compli_program_parcitipant";

    public function rules()
    {
        return [
            ['field' => 'name', 'label' => 'Name', 'rules' => 'required'],
            ['field' => 'contact', 'label' => 'Contact', 'rules' => 'required|numeric|max_length[15]']
        ];
    }
}
