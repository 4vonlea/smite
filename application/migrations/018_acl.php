<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_acl extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field([
			'id' => [
				'type' => 'int',
				'auto_increment' => true,
			],
			'role' => [
				'type' => 'varchar',
				'constraint' => '100'
			],
			'module' => [
				'type' => 'varchar',
				'constraint' => '255'
			],
			'access' => [
				'type' => 'varchar',
				'constraint' => '50'
			],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table("access_control", true);
		$this->db->insert("access_control", ['role' => 'superadmin', 'module' => "account", 'access' => 'insert']);
		$this->db->insert("access_control", ['role' => 'superadmin', 'module' => "account", 'access' => 'update']);
		$this->db->insert("access_control", ['role' => 'superadmin', 'module' => "account", 'access' => 'delete']);
		$this->db->insert("access_control", ['role' => 'superadmin', 'module' => "account", 'access' => 'view']);
	}

	public function down()
	{
		$this->dbforge->drop_table("access_control", true);
	}

}
