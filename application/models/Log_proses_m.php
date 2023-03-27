<?php


class Log_proses_m extends MY_Model
{
	protected $table = "log_proses";
	public $fillable = ['controller','username','request','query'];
}


