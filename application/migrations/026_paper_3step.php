<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_paper_3step extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("papers",[
			'feedback_file_fullpaper'=>['type'=>'varchar','constraint'=>'250'],
			'feedback_fullpaper'=>['type'=>'varchar','constraint'=>'250'],
			'feedback_presentasi'=>['type'=>'varchar','constraint'=>'250'],
			'feedback_file_presentasi'=>['type'=>'varchar','constraint'=>'250'],
			'time_upload_fullpaper'=>['type'=>'datetime'],
			'time_upload_presentasi'=>['type'=>'datetime'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("papers","feedback_file_fullpaper");
		$this->dbforge->drop_column("papers","feedback_fullpaper");
		$this->dbforge->drop_column("papers","feedback_presentasi");
		$this->dbforge->drop_column("papers","feedback_file_presentasi");
		$this->dbforge->drop_column("papers","time_upload_fullpaper");
		$this->dbforge->drop_column("papers","time_upload_presentasi");

	}
}