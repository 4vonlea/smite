<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_stand_sponsor extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'varchar',
                'constraint' => '250',
            ],
            'sponsor'=>[
                'type' => 'varchar',
                'constraint' => '250',
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("stand_sponsor", true);

        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'member_id'=>[
                'type'=>'text',
            ],
            'stand_id'=>[
                'type' => 'varchar',
                'constraint' => '250',
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("stand_presence", true);
    }

    public function down()
    {
        $this->dbforge->drop_table("registered_wa");
    }
}