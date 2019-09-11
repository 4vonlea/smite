<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_modify_papers extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("papers",[
			'type'=>['type'=>'varchar', 'constraint'=>'100'],
			'reviewer'=>['type'=>'varchar', 'constraint'=>'100'],
			'introduction'=>['type'=>'text'],
			'aims'=>['type'=>'text'],
			'methods'=>['type'=>'text'],
			'result'=>['type'=>'text'],
			'conclusion'=>['type'=>'text'],
			'feedback'=>['type'=>'text'],
			'co_author'=>['type'=>'text'],
		]);
		$this->dbforge->add_column("user_accounts",[
			'name'=>['type'=>'varchar', 'constraint'=>'100','null'=>true],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("papers","type");
		$this->dbforge->drop_column("papers","introduction");
		$this->dbforge->drop_column("papers","aims");
		$this->dbforge->drop_column("papers","methods");
		$this->dbforge->drop_column("papers","result");
		$this->dbforge->drop_column("papers","conclusion");
		$this->dbforge->drop_column("papers","feedback");
		$this->dbforge->drop_column("papers","co_author");
		$this->dbforge->drop_column("user_accounts","name");
	}
}
