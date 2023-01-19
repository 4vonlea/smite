<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_modify_sometable extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("committee",[
			'email'=>['type'=>'varchar','constraint'=>'100'],
			'no_contact'=>['type'=>'varchar','constraint'=>'30'],
		]);
		$this->dbforge->add_column("events",[
			'special_link'=>['type'=>'text'],
		]);
		$this->dbforge->add_field([
			'id' => ['type' => 'int', 'auto_increment' => true],
			'paper_id' => ['type' => 'int'],
			'member_id' => ['type' => 'varchar', 'constraint' => '100'],
			'result' => ['type' => 'text'],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
			->add_key("id", true)
			->create_table("reviewer_feedback", true);
		$this->dbforge->add_field([
				'id' => ['type' => 'int', 'auto_increment' => true],
				'name' => ['type' => 'varchar', 'constraint' => '100'],
				'logo' => ['type' => 'varchar', 'constraint' => '100'],
				'link' => ['type' => 'varchar', 'constraint' => '300'],
				'category' => ['type' => 'varchar', 'constraint' => '50'],
				'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
				->add_key("id", true)
				->create_table("link_sponsor", true);
		$this->dbforge->add_field([
					'id' => ['type' => 'int', 'auto_increment' => true],
					'link_id' => ['type' => 'int'],
					'username' => ['type' => 'varchar', 'constraint' => '100'],
					'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
					->add_key("id", true)
					->create_table("link_click", true);
	}

	public function down(){
		$this->dbforge->drop_column("events","special_link");
		$this->dbforge->drop_column("committee","email");
		$this->dbforge->drop_column("committee","no_contact");
		$this->dbforge->drop_table("reviewer_feedback");
		$this->dbforge->drop_table("link_sponsor");
		$this->dbforge->drop_table("link_click");
	}
}
