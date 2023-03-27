<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_paper_score extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("papers",[
			'score'=>['type'=>'decimal(10,2)'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("papers","score");
	}
}