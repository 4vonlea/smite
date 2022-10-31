<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_broadcast extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'varchar',
                'constraint' => '250',
            ],
            'channel' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'type' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'status' => [
                'type' => 'varchar',
                'constraint' => '100',
            ],
            'subject' => [
                'type' => 'varchar',
                'constraint' => '300',
            ],
            'message' => [
                'type' => 'text',
                'null' => true,
            ],
            'attribute' => [
                'type' => 'longtext',
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("broadcast", true);
    }

    public function down()
    {
        $this->dbforge->drop_table("broadcast");
    }
}