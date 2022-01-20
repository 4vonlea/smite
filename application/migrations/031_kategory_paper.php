<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_kategory_paper extends CI_Migration
{
	public function up(){
        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '255',
            ]
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("category_paper", true);
        $this->db->insert("country", [
            'name' => "PIN"
        ]);
        $this->db->insert("country", [
            'name' => "AMOC"
        ]);


		$this->dbforge->add_column("papers",[
			'category'=>['type'=>'int'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("papers","category");
        $this->dbforge->drop_table("category_paper");

	}
}
