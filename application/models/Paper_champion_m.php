<?php

/**
 * Class Papers_m
 *
 * Introduction as Abstract
 * Methods as type_study
 */

class Paper_champion_m extends MY_Model
{

    protected $timestamps = false;
	protected $table = "paper_champion";

    public function gridConfig($option = array())
	{
		return [
			'relationships' => [
				'papers' => ['papers', 'papers.id = paper_id'],
                'member' => ['members', 'member.id = member_id'],
				'kategory_members' => ['kategory_members','member.status = kategory_members.id'],
				'category_paper' => ['category_paper', 'category_paper.id = category', 'left'],
				'st' => ['settings', 'st.name = "format_id_paper"', "left"],
                'univ' => ['univ','member.univ=univ_id'],
			],
            'select' => [
                't_id' => 't.id',
                'id_paper' => 'CONCAT(st.value,LPAD(t.id,3,0))',
                'category_name' => 'category_paper.name',
                'title',
                'fullname',
                'description',
                'phone',
                'email',
				'institution'=>'univ.univ_nama',
                'status_member'=>'kategory',
            ],
			'sort' => ['description', 'desc']
		];
	}


}