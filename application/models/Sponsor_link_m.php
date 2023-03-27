<?php


class Sponsor_link_m extends My_model
{
	protected $table = "link_sponsor";
	protected $primaryKey = "id";

	public function gridConfig($option = array()){
		return [
			'select'=>["t.id","t.name","t.link","t.category","click_count"=>'COUNT(click.id)'],
			'relationships' => [
				'click' => ['link_click', 'click.link_id = t.id','left'],
			],
			'disable_search_field'=>["click_count","id"],
			'group_by'=>'t.id',
		];
	}

	public function rules()
	{
		return [
			['field' => 'name', 'label' => 'Sponsor Name', 'rules' => 
				['required','max_length[100]',
					['check_unique',function($val){
						$id = $this->input->post("id");
						if($id){
							$before = $this->db->get_where("link_sponsor",['id'=>$id])->row();
							$check = $this->db->where(['name'=>$val,'name !='=>$before->name])->count_all_results("link_sponsor");
						}else{
							$check = $this->db->where(['name'=>$val])->count_all_results("link_sponsor");
						}
						return $check == 0;
					}]
				], 'errors'=>['check_unique'=>'Sponsor name must unique'],
			],
			['field' => 'category', 'label' => 'Sponsor Category', 'rules' => 'required|max_length[50]'],
			['field' => 'link', 'label' => 'Sponsor Link', 'rules' => 'required|max_length[300]'],
		];
	}

	public function getLink($identity){
		return $this->findOne(['name'=>$identity]);
	}

	public function getLogoUrl($filename = ""){
		return base_url("themes/uploads/sponsor/$filename");
	}

	public function listCategory(){
		$rs = $this->db->select("category")->distinct()->get($this->table);
		$return = [];
		foreach($rs->result() as $row){
			$return[] = $row->category;
		}
		$return[] = 'OTHER';
		return $return;
	}

	public function listspplatinum()
	{
		$this->db->select('*');
		$this->db->from('link_sponsor');
		$this->db->where('category', 'Platinum');
		$result = $this->db->get()->result();
		return $result;
	}

	public function listspgold()
	{
		$this->db->select('*');
		$this->db->from('link_sponsor');
		$this->db->where('category', 'Gold');
		$result = $this->db->get()->result();
		return $result;
	}

	public function listspsilver()
	{
		$this->db->select('*');
		$this->db->from('link_sponsor');
		$this->db->where('category', 'Silver');
		$result = $this->db->get()->result();
		return $result;
	}

	public function getFieldGrid(){
		$rs = $this->find()->get()->result_array();
		$return = [['name'=>'fullname','title'=>'Member Name']];
		foreach($rs as $row){
			$return[] = [
				'id'=>$row['id'],
				'name'=>"c_".$row['id'],
				'title'=>$row['name'],
				'sortField'=>$row['id']
			];
		}
		return $return;
	}

	public function getReportMemberClick($params){
		// $return['column'] = $this->find()->get()->result_array();
		// $return['data'] = $this->setAlias("t")->find()
		// 	->join("members m","1 = 1")
		// 	->join("link_click lc","lc.link_id = t.id AND lc.username = m.username_account","left")
		// 	->select("m.fullname,t.id, t.name,t.link,COUNT(lc.id) AS click")
		// 	->group_by("m.username_account,t.id")
		// 	->get()->result_array();
		$select = ['fullname'];
		$fields = $this->getFieldGrid();
		$disable_search = [];
		for($i=1;$i<count($fields);$i++){
			$row = $fields[$i];
			$disable_search[] = $row['name'];
			$select[$row['name']] = "SUM(IF(lc.link_id = ".$row['id'].",1,0))";
		}
		$config = [
			'select'=>$select,["m.fullname","t.id", "t.name","click"=>"t.link,COUNT(lc.id)"],
			'relationships'=>[
				'm'=>['(SELECT fullname,username_account FROM members UNION ALL SELECT DISTINCT username AS fullname,username AS username_account FROM link_click WHERE username NOT IN (SELECT username_account FROM members))','1 = 1'],
				'lc'=>['link_click','lc.link_id = t.id AND lc.username = m.username_account','left']
			],
			'group_by'=>'m.username_account',
			'disable_search_field'=>$disable_search,
		];
		return parent::gridData($params,$config);
	}

}
