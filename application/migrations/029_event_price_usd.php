<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 * @property CI_DB_query_builder $db
 */
class Migration_event_price_usd extends CI_Migration
{

    public function up(){
		$this->dbforge->add_column("event_pricing",[
			'price_usd'=>['type'=>'decimal(10,0)'],
		]);
	}

	public function down(){
		$this->dbforge->drop_column("price_usd","price_usd");
	}
}