<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_modify_member extends CI_Migration
{
	public function up()
	{
		if ($this->db->field_exists("univ", "members") == false) {
			$this->dbforge->add_column("members", [
				'univ' => ['type' => 'int'],
				
			]);
			$this->dbforge->add_column("members", [
				'sponsor' => [
					'type' => 'varchar',
					'constraint' => '50'
				],				
			]);
		}
		$this->dbforge->add_field([
			'univ_id' => [
				'type' => 'int',
				'auto_increment' => true
			],
			'univ_nama' => [
				'type' => 'varchar',
				'constraint' => '255'
			]
			,]);
		$this->dbforge->add_key('univ_id', true);
		$this->dbforge->create_table("univ", true);

	}

	public function down()
	{
		if ($this->db->field_exists("univ", "members"))
			$this->dbforge->drop_column("members", "univ");
		$this->dbforge->drop_table("univ", true);
	}
}
