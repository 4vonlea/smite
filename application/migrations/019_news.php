<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_news extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field([
			'id' => [
				'type' => 'int',
				'auto_increment' => true,
			],
			'content' => [
				'type' => 'text',
			],
			'title' => [
				'type' => 'varchar',
				'constraint'=>'200',
			],
			'author' => [
				'type' => 'varchar',
				'constraint'=>'100',
			],
			'is_show' => [
				'type' => 'smallint',
			],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table("news", true);
	}

	public function down()
	{
		$this->dbforge->drop_table("news", true);
	}

}
