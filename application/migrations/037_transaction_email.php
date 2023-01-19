<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_transaction_email extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("transaction",[
			'email_group'=>[
				'type' => 'varchar',
                'constraint' => '100',
			],
		]);
		$this->db->query("ALTER TABLE `papers`
		CHANGE COLUMN `message` `message` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_general_ci' AFTER `updated_at`,
		CHANGE COLUMN `feedback_file_fullpaper` `feedback_file_fullpaper` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_general_ci' AFTER `status_presentasi`,
		CHANGE COLUMN `feedback_fullpaper` `feedback_fullpaper` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_general_ci' AFTER `feedback_file_fullpaper`,
		CHANGE COLUMN `feedback_presentasi` `feedback_presentasi` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_general_ci' AFTER `feedback_fullpaper`,
		CHANGE COLUMN `feedback_file_presentasi` `feedback_file_presentasi` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8_general_ci' AFTER `feedback_presentasi`;
	");
	}

	public function down(){
		$this->dbforge->drop_column("transaction","email_group");
	}
}