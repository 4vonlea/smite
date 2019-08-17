<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_transaction_orders extends CI_Migration
{
	public function up(){
		$this->dbforge->add_field([
			'id' => ['type' => 'varchar', 'constraint' => '255'],
			'member_id' => ['type' => 'varchar', 'constraint' => '100'],
			'checkout' => ['type' => 'int'],
			'status_payment' => ['type' => 'varchar','constraint'=>'100'],
			'message_payment' => ['type' => 'varchar','constraint'=>'255'],
			'channel' => ['type' => 'varchar','constraint'=>'100'],
			'paid_at' => ['type' => 'timestamp','default'=>0],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
			->add_key("id", true)
			->create_table("transaction", true);
		$this->dbforge->add_field([
			'id' => ['type' => 'int', 'constraint' => '50','auto_increment'=>true],
			'member_id' => ['type' => 'varchar', 'constraint' => '100'],
			'transaction_id' => ['type' => 'varchar', 'constraint' => '255'],
			'event_pricing_id' => ['type' => 'int', 'constraint' => '11'],
			'product_name' => ['type' => 'varchar', 'constraint' => '255'],
			'price' => ['type' => 'varchar','constraint'=>'100'],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
			->add_key("id", true)
			->create_table("transaction_details", true);
	}

	public function down(){
		$this->dbforge->drop_table("transaction");
		$this->dbforge->drop_table("transaction_details");
	}
}
