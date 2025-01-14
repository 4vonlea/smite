<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_registered_wa extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'phone_number' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'hold_message'=>[
                'type'=>'text',
            ],
            'status'=> [
                'type'=>'int'
            ],
            'created_at datetime default current_timestamp',
        ]);
        $this->dbforge->add_key("phone_number", true);
        $this->dbforge->create_table("registered_wa", true);
    }

    public function down()
    {
        $this->dbforge->drop_table("registered_wa");
    }
}