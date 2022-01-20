<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_country extends CI_Migration
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
                'constraint' => '100',
            ]
        ]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("country", true);

        $jsonString = file_get_contents(__DIR__ . "/country-list.json");
        $jsonList = json_decode($jsonString, true);
        foreach ($jsonList as $row) {
            $this->db->insert("country", [
                'name' => $row['en']
            ]);
        }

        $this->dbforge->modify_column('members', [
            'country' => [
                'name' => 'country',
                'type' => 'int'
            ]
        ]);
    }

    public function down()
    {
        $this->dbforge->modify_column('members', [
            'country' => [
                'name' => 'country',
                'type' => 'varchar',
                'constraint' => '50',
            ]
        ]);
        $this->dbforge->drop_table("country");
    }
}
