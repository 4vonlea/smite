<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_init extends CI_Migration
{

    protected function t_user_accounts()
    {
        $this->dbforge->add_field([
            'username' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],
            'password' => [
                'type' => 'varchar',
                'constraint' => '255'
            ],
            'role' => [
                'type' => 'tinyint',
            ],
            'token_reset' => [
                'type' => 'varchar',
                'constraint' => '255',
            ]
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key('username', true);
        $this->dbforge->create_table("user_accounts", true);
    }

    protected function t_members()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],
            'image' => [
                'type' => 'varchar',
                'constraint' => '255'
            ],
            'email' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],
            'fullname' => [
                'type' => 'varchar',
                'constraint' => '255'
            ],
            'gender' => [
                'type' => 'varchar',
                'constraint' => '1'
            ],
            'phone' => [
                'type' => 'varchar',
                'constraint' => '20'
            ],
            'birthday' => [
                'type' => 'date',
            ],
            'country' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'region' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'city' => [
                'type' => 'varchar',
                'constraint' => '50',
            ],
            'address' => [
                'type' => 'text',
            ],
            'username_account' => [
                'type' => 'varchar',
                'constraint' => '100'
            ],
            'status' => [
                'type' => 'int',
            ],
            'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key('id', true);
        $this->dbforge->create_table("members", true);
    }

    protected function t_registrations()
    {
        $this->dbforge->add_field([
            'id' => [
                'type' => 'int',
                'auto_increment' => true,
            ],
            'member_id' => [
                'type' => 'int'
            ],
            'hotel_service_id' => [
                'type' => 'int'
            ]
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
        $this->dbforge->add_key("id", true);
        $this->dbforge->create_table("registrations", true);
    }

    protected function t_event()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'name' => ['type' => 'varchar', 'constraint' => '100'],
            'kategory' => ['type' => 'varchar', 'constraint' => '100'],
            'description' => ['type' => 'text'],
            'price' => ['type' => 'float']
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])->add_key("id", true)
            ->create_table("events", true);
    }

    protected function t_event_pricing()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'name' => ['type' => 'varchar', 'constraint' => '100'],
            'condition' => ['type' => 'varchar', 'constraint' => '100'],
            'condition_date' => ['type' => 'varchar', 'constraint' => '100'],
            'price' => ['type' => 'decimal'],
            'event_id' => ['type' => 'int']
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
            ->add_key('id', true)
            ->create_table("event_pricing", true);
    }

    protected function t_hotels_service()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'name' => ['type' => 'varchar', 'constraint' => '100'],
            'room_tipe' => ['type' => 'varchar', 'constraint' => '100'],
            'price' => ['type' => 'decimal'],
            'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])->add_key('id', true)
            ->create_table("hotel_services", true);
    }

    protected function t_setting()
    {
        $this->dbforge->add_field([
            'name' => ['type' => 'varchar', 'constraint' => '100'],
            'value' => ['type' => 'text'],
            'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])->add_key('name', true)
            ->create_table("settings", true);
    }

    protected function t_kategory_members()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'int', 'auto_increment' => true],
            'kategory' => ['type' => 'varchar', 'constraint' => '100'],
            'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
            ->add_key('id', true)
            ->create_table("kategory_members", true);
    }

    protected function t_area()
    {
        $this->dbforge->add_field([
            'id' => ['type' => 'varchar', 'constraint' => '50'],
            'name' => ['type' => 'varchar', 'constraint' => '50'],
            'level' => ['type' => 'int'],
            'parent_id' => ['type' => 'varchar', 'constraint' => '50']
            , 'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']])
            ->add_key("id", true)
            ->create_table("areas", true);
    }

    public function up()
    {
        $this->t_user_accounts();
        $this->t_members();
        $this->t_registrations();
        $this->t_area();
        $this->t_setting();
        $this->t_event();
        $this->t_event_pricing();
        $this->t_hotels_service();
        $this->t_kategory_members();
        $this->db->insert("user_accounts", [
            'username' => 'admin',
            'password' => password_hash("admin", PASSWORD_DEFAULT),
            'role' => '1']);
    }

    public function down()
    {
        $this->dbforge->drop_table("user_accounts", true);
        $this->dbforge->drop_table("members", true);
        $this->dbforge->drop_table("registrations", true);
        $this->dbforge->drop_table("events", true);
        $this->dbforge->drop_table("event_pricing", true);
        $this->dbforge->drop_table("hotel_services", true);
        $this->dbforge->drop_table("settings", true);
        $this->dbforge->drop_table("kategory_members", true);
        $this->dbforge->drop_table("areas", true);
    }
}