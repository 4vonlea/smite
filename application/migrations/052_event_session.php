<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_event_session extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column("events", [
            'session' => [
                'type' => 'text',
            ],
        ]);
        $this->dbforge->add_column("presence", [
            'session' => [
                'type' => 'text',
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->drop_column("events", "session");
        $this->dbforge->drop_column("presence", "session");
    }
}
