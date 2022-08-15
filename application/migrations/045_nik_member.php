<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_nik_member extends CI_Migration
{
	public function up()
	{
		set_time_limit(0);
        $this->dbforge->add_column("members", [
            'nik' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],			
        ]);
		$this->dbforge->add_field([
            'kode' => [
                'type' => 'varchar',
                'constraint' => '20'
            ],
			'nama' => [
                'type' => 'varchar',
                'constraint' => '250'
            ],
        ]);
        $this->dbforge->add_key("kode", true);
        $this->dbforge->create_table("wilayah", true);

		$jsonString = file_get_contents(__DIR__ . "/wilayah.json");
        $jsonList = json_decode($jsonString, true);
        foreach ($jsonList as $row) {
            $this->db->insert("wilayah", [
                'kode' => $row['kode'],
                'nama' => $row['nama'],
            ]);
        }
	}

	public function down()
	{
		$this->dbforge->drop_column("members","nik");
        $this->dbforge->drop_table("wilayah");
	}
}
