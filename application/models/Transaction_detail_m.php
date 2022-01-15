<?php


class Transaction_detail_m extends My_model
{
	protected $primaryKey = "id";
	protected $table = "transaction_details";

	public function member()
	{
		return $this->hasOne('Member_m', 'id', 'member_id');
	}
}
