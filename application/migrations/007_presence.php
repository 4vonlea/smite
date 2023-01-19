<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_presence extends CI_Migration
{
	public function up(){
		$this->dbforge->add_field([
			'id' => ['type' => 'int', 'auto_increment' => true],
			'member_id' => ['type' => 'varchar', 'constraint' => '100'],
			'event_id' => ['type' => 'int'],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
			->add_key("id", true)
			->create_table("presence", true);

	}

	public function down(){
		$this->dbforge->drop_table("presence");
	}
}
