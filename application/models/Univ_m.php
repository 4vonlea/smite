<?php


class Univ_m extends MY_Model
{
    protected $table = "univ";
    protected $primaryKey = "univ_id";
	const CREATED_AT = NULL;
	const UPDATED_AT = NULL;

    const UNIV_OTHER = 9999;

    public $last_insert_id;

    public function insert($attributes, $runValidation = true)
	{
		$id = $this->find()->select_max('univ_id')
			->where('univ_id <',Univ_m::UNIV_OTHER)
			->get()->row();
		if($id){
			$id = $id->univ_id+1;
		}
		$attributes['univ_id'] = $id;
		$this->last_insert_id = $id;
		return parent::insert($attributes, $runValidation); // TODO: Change the autogenerated stub
	}

}
