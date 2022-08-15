<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_nik_member extends CI_Migration
{
	public function up()
	{
        $this->dbforge->add_column("members", [
            'nik' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],			
        ]);
	}

	public function down()
	{
		$this->dbforge->drop_column("members","nik");
	}
}
