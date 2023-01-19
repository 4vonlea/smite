<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_field_comment extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("video_komen",[
			'comment'=>['type'=>'varchar','constraint'=>'250'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("video_komen","comment");
	}
}