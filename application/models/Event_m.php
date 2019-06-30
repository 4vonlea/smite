<?php


class Event_m extends MY_Model
{
    protected $table = "events";
    public function rules()
    {
        return [
            ['field'=>'name','label'=>'Event Name','rules'=>'required'],
            ['field'=>'kategory','label'=>'Event Category','rules'=>'required'],
        ];
    }
}