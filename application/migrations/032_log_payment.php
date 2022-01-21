<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_log_payment extends CI_Migration
{
    public function up()
    {

        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'action' => [
                'type' => 'varchar',
                'constraint' => '100',
            ],
            'invoice' => [
                'type' => 'varchar',
                'constraint' => '100',
            ],
            'request' => [
                'type' => 'json',
            ],
            'response' => [
                'type' => 'json',
            ],
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("log_payment", true);
    }

    public function down()
    {
        $this->dbforge->drop_table("log_payment");
    }
}
