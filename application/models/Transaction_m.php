<?php


class Transaction_m extends MY_Model
{
	protected $table = "transaction";

	public static $transaction_status = [
		'capture'=>'Transaction is accepted and ready to for settlement.',
		'cancel'=>'Transaction is cancelled and will not proceed to settlement.',
		'settlement'=>"Funds from the transaction has moved from customer to merchant's account.",
		'deny'=>"Transaction is denied by the bank or Midtrans Fraud Detection System.",
		"pending"=>"payment transaction has not been processed and is waiting to be completed.",
		"expire"=>"Transaction has not been completed by the expiry date.",
	];

	const STATUS_FINISH = "settlement";
	const STATUS_WAITING = "WAITING";
	const STATUS_PENDING = "pending";
	const STATUS_UNFINISH = "UNFINISH";

	public function midtransTransactionStatusDefinition($status){
		$status = strtolower($status);
		return isset(self::$transaction_status[$status]) ?self::$transaction_status[$status]:"-";
	}

	public function generateInvoiceId(){
		$prefix = "INV-".date("Ymd")."-";
		$rs = $this->find()->like("id",$prefix,"after")->order_by("id","DESC")->limit(1)->get();
		$row = $rs->row();
		$no = 1;
		if($row){
			$expl = explode("-",$row->id);
			$no = isset($expl[2])?$expl[2]+1:1;
		}
		return $prefix.str_pad($no,5,"0",STR_PAD_LEFT);
	}

	public function details()
	{
		return $this->hasMany('Transaction_detail_m', 'transaction_id');
	}
}
