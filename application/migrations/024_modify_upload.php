<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_modify_upload extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("ref_upload",[
			'deadline'=>['type'=>'datetime'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("ref_upload","deadline");
	}
}