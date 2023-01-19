<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_paper_sertifikat extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'paper_id' => [
                'type' => 'int',
            ],
            'description' => [
                'type' => 'varchar',
                'constraint' => '250',
            ],
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("paper_champion", true);
    }

    
    public function down()
    {
        $this->dbforge->drop_table("paper_champion");
    }
}