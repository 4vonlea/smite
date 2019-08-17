<?php


class Transaction_m extends MY_Model
{
	protected $table = "transaction";

	public function generateInvoiceId(){
		$prefix = "INV/".date("Ymd")."/";
		$rs = $this->find()->like("id",$prefix,"after")->order_by("id","DESC")->limit(1)->get();
		$row = $rs->row();
		$no = 1;
		if($row){
			$expl = explode("/",$row->id);
			$no = isset($expl[2])?$expl[2]+1:1;
		}
		return $prefix.str_pad($no,5,"0",STR_PAD_LEFT);
	}

	public function details()
	{
		return $this->hasMany('Transaction_detail_m', 'transaction_id');
	}
}
