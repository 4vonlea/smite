<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_temp_calendar extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field([
            'date' => [
                'type' => 'date',
            ],
        ]);
        $this->dbforge->add_key("date", true);
        $this->dbforge->create_table("temp_calendar", true);
    }

    public function down()
    {
        $this->dbforge->drop_table("temp_calendar");
    }
}