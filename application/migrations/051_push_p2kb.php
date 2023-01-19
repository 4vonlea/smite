<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_push_p2kb extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("members",[
			'p2kb_member_id'=>[
				'type' => 'varchar',
				'constraint'=>'250'
			],
		]);
		$this->dbforge->add_column("events",[
			'p2kb_mapping'=>[
				'type' => 'text',
			],
		]);
		$this->dbforge->add_column("transaction_details",[
			'p2kb_push'=>[
				'type' => 'text',
			],
		]);
		$this->dbforge->add_column("papers",[
			'p2kb_push'=>[
				'type' => 'text',
			],
		]);
		$this->dbforge->add_column("paper_champion",[
			'p2kb_push'=>[
				'type' => 'text',
			],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("members","p2kb_member_id");
		$this->dbforge->drop_column("events","p2kb_mapping");
		$this->dbforge->drop_column("transaction_details","p2kb_push");
		$this->dbforge->drop_column("papers","p2kb_push");
		$this->dbforge->drop_column("paper_champion","p2kb_push");
	}
}