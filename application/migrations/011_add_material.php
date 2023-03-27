<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_add_material extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("events",[
			'material'=>['type'=>'text'],
		]);

	}

	public function down(){
		$this->dbforge->drop_column("events","material");
	}
}
