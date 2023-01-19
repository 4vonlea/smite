<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_last_reset extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("user_accounts",[
			'last_reset'=>[
				'type' => 'datetime',
			],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("user_accounts","last_reset");
	}
}