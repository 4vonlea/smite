<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_upload_video extends CI_Migration
{

    public function up(){
		$this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'filename' => [
                'type' => 'varchar',
                'constraint'=>'100',
			],
			'uploader' => [
				'type' => 'varchar',
				'constraint'=>'100',
			]
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key("id", true);
		$this->dbforge->create_table("upload_video", true);

		$this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'video_id' => [
                'type' => 'int',
			],
			'username' => [
				'type' => 'varchar',
				'constraint'=>'100',
			]
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key("id", true);
		$this->dbforge->create_table("video_like", true);

		$this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'video_id' => [
                'type' => 'int',
			],
			'username' => [
				'type' => 'varchar',
				'constraint'=>'100',
			]
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key("id", true);
		$this->dbforge->create_table("video_komen", true);
		
		$this->dbforge->add_column("papers",[
			'status_fullpaper'=>['type'=>'int'],
			'status_presentasi'=>['type'=>'int'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("papers","status_fullpaper");
		$this->dbforge->drop_column("papers","status_presentasi");
        $this->dbforge->drop_table("upload_video", true);
        $this->dbforge->drop_table("video_komen", true);
        $this->dbforge->drop_table("video_like", true);

	}
}