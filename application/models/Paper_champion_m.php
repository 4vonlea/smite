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


    public function champion($id){
        $this->find()->select("'1' as isPaper,fullname,type_presence,title,email,CONCAT(st.value,LPAD(papers.id,3,0)) as id_paper,description as status")
				->join("papers","paper_champion.paper_id = papers.id")
				->join("members","members.id = member_id")
				->join("settings st",'st.name = "format_id_paper"','left')
                ->where("paper_champion.id",$id)
                ->get()->row_array();
    }
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
                'id_paper' => 'CONCAT(st.value,LPAD(papers.id,3,0))',
                'category_name' => 'category_paper.name',
                'title',
                'paper_id',
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