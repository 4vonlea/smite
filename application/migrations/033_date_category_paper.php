<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_date_category_paper extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column(
            "category_paper",
            [
                'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']
            ]
        );
    }

    public function down()
    {
        $this->dbforge->drop_column("category_paper", "created_at");
        $this->dbforge->drop_column("category_paper", "updated_at");
    }
}
