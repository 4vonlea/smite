<?php


class Presence_m extends MY_Model
{
	protected $table = "presence";
	protected $primaryKey = "id";

	public function getDataToday($event_id){
		$this->load->model("Transaction_m");
		$rs = $this->db->select("t.*,k.kategory as status_member,p.created_at as presence_at")
			->join("kategory_members k","k.id = t.status")
			->join("transaction_details td","td.member_id = t.id")
			->join("transaction tr","tr.id = td.transaction_id")
			->join("event_pricing ep","ep.id = td.event_pricing_id")
			->join("presence p","p.member_id = t.id AND p.event_id = ep.event_id AND DATE(p.created_at) = DATE(NOW())","left")
			->where(['ep.event_id'=>$event_id,'tr.status_payment'=>Transaction_m::STATUS_FINISH])
			->get("members t");
		return $rs->result_array();
	}
}
