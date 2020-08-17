<?php


class Sponsor_link_m extends My_model
{
	protected $table = "link_sponsor";
	protected $primaryKey = "id";

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
		$rs = $this->db->distinct("category")->get($this->table);
		$return = [];
		foreach($rs->result() as $row){
			$return[$row->category] = $row->category;
		}
		$return['OTHER'] = 'OTHER';
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

}
