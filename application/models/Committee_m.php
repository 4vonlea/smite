<?php


class Committee_m extends My_model
{
	protected $table = "committee";

	public function gridConfig($option = array())
	{
		return [
			'select'=>['t.name',"GROUP_CONCAT(CONCAT(attr.id,',',attr.status,',',event.name) SEPARATOR ';') as status"],
			'relationships' => [
				'attr' => ['committee_attribute', 'committee_id = t.id'],
				'event' => ['events', 'event_id = event.id']
			],
			'group_by'=>'t.id',
		];
	}
}
