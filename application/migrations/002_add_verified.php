<?php
/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_add_verified extends CI_Migration{
    public function up(){
        $this->dbforge->add_column("members",[
            'verified_by_admin'=>['type'=>'tinyint','constraint'=>1],
            'verified_email'=>['type'=>'tinyint','constraint'=>1],
        ]);
    }

    public function down(){
        $this->dbforge->drop_column("members","verified_by_admin");
        $this->dbforge->drop_column("members","verified_email");
    }
}