<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_modify_events extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("kategory_members",[
			'is_hide'=>['type'=>'tinyint'],
		]);
		$this->dbforge->modify_column("events",[
			'special_link'=>['type'=>'mediumtext'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("kategory_members","is_hide");
	}
}
