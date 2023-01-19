<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_committee extends CI_Migration
{
	public function up(){
		$this->dbforge->add_field([
			'id' => ['type' => 'int', 'auto_increment' => true],
			'name' => ['type' => 'varchar', 'constraint' => '100'],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
			->add_key("id", true)
			->create_table("committee", true);
		$this->dbforge->add_field([
			'id' => ['type' => 'int', 'auto_increment' => true],
			'committee_id' => ['type' => 'int'],
			'event_id' => ['type' => 'int'],
			'status' => ['type' => 'varchar','constraint'=>'100'],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
			->add_key("id", true)
			->create_table("committee_attribute", true);
	}

	public function down(){
		$this->dbforge->drop_table("committee");
		$this->dbforge->drop_table("committee_attribute");
	}
}
