<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modify_revise extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("events",[
			'kouta'=>['type'=>'int','default'=>'0'],
		]);
		$this->dbforge->add_column("kategory_members",[
			'need_verify'=>['type'=>'boolean','default'=>'0'],
		]);

	}

	public function down(){
		$this->dbforge->drop_column("events","kouta");
		$this->dbforge->drop_column("kategory_members","need_verify");
	}
}
