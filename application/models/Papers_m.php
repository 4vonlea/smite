<?php


class Papers_m extends MY_Model
{
	public static $status = [
		0 =>'Returned to author',
		1 =>'Need Review',
		2 =>'Accepted'
	];
    protected $table = "papers";

    public function rules()
    {
        return [
            ['field' => 'title', 'rules' => 'required|max_length[255]'],
        ];
    }

    public function gridConfig()
	{
		return [
			'relationships'=>[
				'member'=>['members','member.id = member_id']
			],
			'select'=>['t_id'=>'t.id','fullname','title','status'=>'t.status','t_updated_at'=>'t.updated_at','author'=>'member.fullname','filename']
		];
	}

	public function gridData($params, $gridConfig = [])
	{
		$data = parent::gridData($params, $gridConfig);
		$result = $this->find()->select("SUM(IF(status = 0,1,0)) as stat_0")
			->select("SUM(IF(status = 1,1,0)) as stat_1")
			->select("SUM(IF(status = 2,1,0)) as stat_2")
			->get()->row_array();
		$data['total_stat_0'] = $result['stat_0'];
		$data['total_stat_1'] = $result['stat_1'];
		$data['total_stat_2'] = $result['stat_2'];
		return $data;
	}
}
