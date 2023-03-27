<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modify_transaction_2 extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("transaction",[
			'payment_proof'=>['type'=>'varchar','constraint'=>'100','null'=>true],
			'client_message'=>['type'=>'varchar','constraint'=>'255','null'=>true],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("transaction","payment_proof");
		$this->dbforge->drop_column("transaction","client_message");
	}
}
