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
			->group_by("t.id")
			->get("members t");
		return $rs->result_array();
	}

	public function getReportData($event_id){
		$formatDate = "d M Y";
		$this->load->model("Transaction_m");
		$rsd = $this->find()->select("DISTINCT date(created_at) as date")->where("event_id",$event_id)->order_by("created_at")->get();
		$col = ['no','fullname','status_member'];
		foreach($rsd->result_array() as $row){
			$date = new DateTime($row['date']);
			$col[] = $date->format($formatDate);
		}
		$rs = $this->db->select("t.*,k.kategory as status_member,GROUP_CONCAT(p.created_at separator ';') as presence")
			->join("kategory_members k","k.id = t.status")
			->join("transaction_details td","td.member_id = t.id")
			->join("transaction tr","tr.id = td.transaction_id")
			->join("event_pricing ep","ep.id = td.event_pricing_id")
			->join("presence p","p.member_id = t.id AND p.event_id = ep.event_id","left")
			->where(['ep.event_id'=>$event_id,'tr.status_payment'=>Transaction_m::STATUS_FINISH])
			->group_by("t.id")
			->get("members t");

		$return = [];
		$i = 1;
		foreach($rs->result_array() as $row){
			$temp = [];
			foreach(explode(";",$row['presence']) as $t){
				$date = new DateTime($t);
				$temp[$date->format($formatDate)] = $date->format("H:i:s");
			}
			$rTemp = [];
			$row = array_merge($row,$temp);
			$row['no'] = $i;
			foreach($col as $c){
				$rTemp[$c] = (isset($row[$c]) ? $row[$c]:"-");
			}
			$return[] = $rTemp;
			$i++;
		}
		return $return;

	}
}
