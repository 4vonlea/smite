<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_transaction_email extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("transaction",[
			'email_group'=>[
				'type' => 'varchar',
                'constraint' => '100',
			],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("transaction","email_group");
	}
}