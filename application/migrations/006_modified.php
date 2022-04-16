<?php
/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modified extends CI_Migration{
	public function up(){
		$this->dbforge->add_column("event_pricing",[
			'show'=>[
				'type'=>'smallint',
				'default'=>'1'
			]
		]);
	}

	public function down(){
		$this->dbforge->drop_column("event_pricing","show");
	}
}
