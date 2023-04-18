<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_upload_video_multiple extends CI_Migration
{
    public function up()
    {
        $this->dbforge->modify_column("upload_video", [
            'filename' => [
                'type' => 'text',
            ],
        ]);
        $this->dbforge->add_column("upload_video", [
            'position' => [
                'type' => 'int',
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->modify_column("upload_video", [
            'filename' => [
                'type' => 'varchar',
            ],
        ]);
    }
}
