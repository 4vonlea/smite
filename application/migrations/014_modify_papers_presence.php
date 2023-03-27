<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modify_papers_presence extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("papers",[
			'type_presence'=>['type'=>'varchar','constraint'=>'100','null'=>true],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("papers","type_presence");
	}
}
