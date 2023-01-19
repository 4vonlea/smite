<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_table_upload_material extends CI_Migration
{
	public function up(){
		$this->dbforge->add_column("reviewer_feedback",[
			'status'=>['type'=>'tinyint'],
        ]);
        
        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'varchar',
                'constraint'=>'100',
            ]
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("ref_upload", true);

        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'member_id' => [
                'type' => 'varchar',
                'constraint'=>'100',
            ],
            'filename' => [
                'type' => 'varchar',
                'constraint'=>'250',
            ],
            'ref_upload_id' => [
                'type' => 'int',
            ],
            'type' => [
                'type' => 'tinyint',
            ]
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("member_upload_material", true);
	}

	public function down(){
        $this->dbforge->drop_column("reviewer_feedback","status");
        $this->dbforge->drop_table("member_upload_material", true);
        $this->dbforge->drop_table("ref_upload", true);
	}
}
