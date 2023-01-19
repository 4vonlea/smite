<?php

require_once APPPATH."third_party/simplehtmldom/simple_html_dom.php";
class News_m extends MY_Model
{
	protected $table = "news";
	public $fillable = ['title','content','is_show','author'];

	public function listnews()
	{
		$this->db->select('id, content, title, is_show');
		$this->db->from('news');
		$this->db->where('is_show', '1');
		$this->db->limit(3);
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get()->result();
		return $result;
	}

	public function allnews()
	{
		$this->db->from('news');
		$this->db->where('is_show', '1');
		$this->db->order_by('id', 'DESC')->limit(8);
		$result = $this->db->get()->custom_result_object("News_m");
		return $result;
	}

	public function read_news($id)
	{
		$this->db->select('id, content, title, is_show,created_at');
		$this->db->from('news');
		$this->db->where('id', $id);
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get()->custom_row_object(0,"News_m");
		return $result;
	}

	public function imageCover(){
		$doc = new DOMDocument();
		@$doc->loadHTML($this->content);
		$img = $doc->getElementsByTagName("img")->item(0);
		if($img){
			return $img->attributes->getNamedItem("src")->value;
		}
		return "https://via.placeholder.com/250?text=No+Image";
	}

	protected function sanitize($node){
		$style = $node->style;
		if($style){
			preg_match('/font\-size\:(.*?)\;/',$style,$match);
			if(count($match) > 0){
				$style = $match[0];
				$node->setAttribute("style",$style);
			}else{
				$node->removeAttribute("style");
			}
		}
		$node->removeAttribute("class");
		foreach($node->children() as $child){
			$this->sanitize($child);
		}
	}

	public function clearContent(){
		$doc = str_get_html($this->content);
		foreach($doc->nodes as $node)
			$this->sanitize($node);
		return $doc->save();
	}
}


