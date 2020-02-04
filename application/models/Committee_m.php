<?php


class Committee_m extends My_model
{
	protected $table = "committee";

	public function gridConfig($option = array())
	{
		return [
			'select'=>['t_id'=>'t.id','t_name'=>'t.name',"GROUP_CONCAT(CONCAT(attr.id,',',attr.status,',',event.name,',',event.id) SEPARATOR ';') as status"],
			'relationships' => [
				'attr' => ['committee_attribute', 'committee_id = t.id','left'],
				'event' => ['events', 'event_id = event.id','left']
			],
			'group_by'=>'t.id',
		];
	}
}
