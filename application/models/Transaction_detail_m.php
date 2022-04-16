<?php


class Transaction_detail_m extends MY_Model
{
	protected $primaryKey = "id";
	protected $table = "transaction_details";
	public $fillable = ['id','member_id','transaction_id','event_pricing_id','product_name','price','checklist','price_usd'];

	public function member()
	{
		return $this->hasOne('Member_m', 'id', 'member_id');
	}
}
