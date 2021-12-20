<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modify_transaction extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("transaction_details",[
			'checklist'=>['type'=>'varchar', 'constraint'=>'200','default'=>'{}'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("transaction_details","checklist");
	}
}
