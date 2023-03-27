<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_discount extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '250',
            ],
            'event_combination' => [
                'type' => 'json',
            ],
            'discount' => [
                'type' => 'decimal',
            ],
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("event_discount", true);
    }

    public function down()
    {
        $this->dbforge->drop_table("event_discount");
    }
}