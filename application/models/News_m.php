<?php


class News_m extends MY_Model
{
	protected $table = "news";
	public $fillable = ['title','content','is_show','author'];
}
