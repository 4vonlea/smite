<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_hotel extends CI_Migration
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
            'address' => [
                'type' => 'varchar',
                'constraint' => '400',
            ],
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("hotels", true);
        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'hotel_id' => [
                'type' => 'int',
            ],
            'name' => [
                'type' => 'varchar',
                'constraint' => '250',
            ],
            'start_date' => [
                'type' => 'date',
            ],
            'end_date' => [
                'type' => 'date',
            ],
            'description'=>[
                'type'=>'text',
            ],
            'qouta' => [
                'type' => 'int',
            ],
            'price' => ['type' => 'decimal'],
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("rooms", true);
        $this->dbforge->add_column("transaction_details", [
            'room_id' => [
                'type' => 'int',
            ],
            'checkin_date' => [
                'type' => 'date',
            ],
            'checkout_date' => [
                'type' => 'date',
            ],
            			
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_table("hotels");
        $this->dbforge->drop_table("rooms");
    }
}