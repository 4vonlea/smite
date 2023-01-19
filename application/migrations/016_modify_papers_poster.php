<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modify_papers_poster extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("papers",[
			'poster'=>['type'=>'varchar','constraint'=>'100','null'=>true],
			'fullpaper'=>['type'=>'varchar','constraint'=>'100','null'=>true],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("papers","poster");
		$this->dbforge->drop_column("papers","fullpaper");
	}
}
