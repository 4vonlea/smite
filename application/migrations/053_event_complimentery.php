<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_event_complimentery extends CI_Migration
{

    public function up()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'name' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],
            'held_on' => [
                'type' => 'date',
            ],
            'description' => [
                'type' => 'text',
            ], 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table("complimentary_program", true);

        $this->dbforge->add_field([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'name' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],
            'contact' => [
                'type' => 'varchar',
                'constraint' => '20'
            ],
            'status' => [
                'type' => 'varchar',
                'constraint' => '20'
            ],
            'program_id' => [
                'type' => 'int',
            ],
            'member_id' => [
                'type' => 'varchar',
                'constraint' => '100'
            ], 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']
        ]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table("compli_program_parcitipant", true);
    }

    public function down()
    {
        $this->dbforge->drop_table("complimentary_program", true);
        $this->dbforge->drop_table("compli_program_parcitipant", true);
    }
}
