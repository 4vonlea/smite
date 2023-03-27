<?php
/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_add_submit_paper extends CI_Migration{
    public function up(){
        $this->dbforge->add_field([
            'id' => ['type' => 'int', 'constraint' => '50','auto_increment' => true],
            'member_id' => ['type' => 'varchar', 'constraint' => '100'],
            'filename' => ['type' => 'varchar','constraint'=>'100'],
            'parent_id' => ['type' => 'varchar', 'constraint' => '50']
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
            ->add_key("id", true)
            ->create_table("papers", true);
    }

    public function down(){
        $this->dbforge->drop_table("papers");
    }

}
