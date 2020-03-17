<?php


class Committee_m extends My_model
{
	protected $table = "committee";

	public function gridConfig($option = array())
	{
		return [
			'select'=>['t_id'=>'t.id','t_name'=>'t.name',"GROUP_CONCAT(CONCAT(attr.id,',',attr.status,',',event.name,',',event.id) SEPARATOR ';') as status","a_id"=>"attr.id"],
			'relationships' => [
				'attr' => ['committee_attribute', 'committee_id = t.id','left'],
				'event' => ['events', 'event_id = event.id','left']
			],
			'search_operator'=>['a_id'=>'='],
			'include_search_field'=>['a_id'],
			'disable_search_field'=>['t_id'],
			'group_by'=>'t.id',
		];
	}

	public function getDataCommittee(){
		$result = $this->db->select("e.name as Event, c.name as nama, ca.status")
			->from("committee_attribute ca")
			->join("committee c","ca.committee_id = c.id ")
			->join("events e", "e.id = ca.event_id")
			->order_by("e.id")
		    ->get()->result_array();

		return $result;
	}
}
