<?php
/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_invoice_usd extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_column(
            "transaction_details",
            [
                'price_usd' => [
                    'type' => 'varchar',
                    'constraint'=>'100',
                    'default'=>'0',
                ]
            ]
        );
    }

    public function down()
    {
        $this->dbforge->drop_column("transaction_details", "price_usd");
    }
}
