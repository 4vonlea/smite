<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_noted_transaction extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("transaction",[
			'note'=>[
				'type' => 'text',
			],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("transaction","note");
	}
}