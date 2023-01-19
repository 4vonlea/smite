<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_event_required extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("events",[
			'event_required'=>['type' => 'int',],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("events","event_required");
	}
}