<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modify_event extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("events",[
			'held_on'=>['type'=>'varchar', 'constraint'=>'100'],
			'theme'=>['type'=>'varchar', 'constraint'=>'250'],
			'held_in'=>['type'=>'varchar', 'constraint'=>'100'],
		]);

	}

	public function down(){
		$this->dbforge->drop_column("events","held_on");
		$this->dbforge->drop_column("events","held_in");
		$this->dbforge->drop_column("events","theme");
	}
}
