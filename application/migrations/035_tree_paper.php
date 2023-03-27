<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_tree_paper extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("category_paper",[
			'tree'=>['type' => 'json',],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("category_paper","tree");
	}
}