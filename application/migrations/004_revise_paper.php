<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_revise_paper extends CI_Migration
{
    public function up()
    {
        $this->dbforge->modify_column("papers", ['parent_id'=>[
            'name' => 'status',
            'type' => 'int'
        ]]);
        $this->dbforge->add_column("papers",[
            'message'=>[
                'type'=>'varchar',
                'constraint'=>'255'
            ],'title'=>[
                'type'=>'varchar',
                'constraint'=>'255'
            ],
        ]);
    }

    public function down()
    {
        $this->dbforge->modify_column("papers", ['status'=>[
            'name' => 'parent_id',
            'type' => 'varchar',
            'constraint' => '50'
        ]]);
        $this->dbforge->drop_column("papers","message");
        $this->dbforge->drop_column("papers","title");
    }
}