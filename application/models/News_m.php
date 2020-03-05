<?php


class News_m extends MY_Model
{
	protected $table = "news";
	public $fillable = ['title','content','is_show','author'];

	public function listnews()
	{
		$this->db->select('id, content, title, is_show');
		$this->db->from('news');
		$this->db->where('is_show', '1');
		$this->db->limit(2);
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get()->result();
		return $result;
	}

	public function allnews()
	{
		$this->db->select('id, content, title, is_show');
		$this->db->from('news');
		$this->db->where('is_show', '1');
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get()->result();
		return $result;
	}

	public function read_news($id)
	{
		$this->db->select('id, content, title, is_show');
		$this->db->from('news');
		$this->db->where('id', $id);
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get()->result();
		return $result;
	}
}


