<?php

/**
 * Class Migration_init
 * @property CI_DB_forge $dbforge
 */
class Migration_acl extends CI_Migration
{
	public function up()
	{
		$this->dbforge->add_field([
			'id' => [
				'type' => 'int',
				'auto_increment' => true,
			],
			'role' => [
				'type' => 'varchar',
				'constraint' => '100'
			],
			'module' => [
				'type' => 'varchar',
				'constraint' => '255'
			],
			'access' => [
				'type' => 'varchar',
				'constraint' => '50'
			],
			'created_at' => ['type' => 'datetime'], 'updated_at' => ['type' => 'datetime']]);
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table("access_control", true);
		$this->db->query("INSERT INTO `access_control` (`id`, `role`, `module`, `access`, `created_at`, `updated_at`) VALUES
        (1, 'superadmin', 'account', 'insert', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
        (2, 'superadmin', 'account', 'update', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
        (3, 'superadmin', 'account', 'delete', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
        (4, 'superadmin', 'account', 'view', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
        (5, 'Superadmin', 'administration', 'view', '2020-06-19 11:57:48', '2020-06-19 11:57:48'),
        (6, 'Superadmin', 'administration', 'insert', '2020-06-19 11:57:48', '2020-06-19 11:57:48'),
        (7, 'Superadmin', 'administration', 'update', '2020-06-19 11:57:49', '2020-06-19 11:57:49'),
        (8, 'Superadmin', 'administration', 'delete', '2020-06-19 11:57:50', '2020-06-19 11:57:50'),
        (9, 'Superadmin', 'committee', 'delete', '2020-06-19 11:57:51', '2020-06-19 11:57:51'),
        (10, 'Superadmin', 'committee', 'update', '2020-06-19 11:57:52', '2020-06-19 11:57:52'),
        (11, 'Superadmin', 'committee', 'insert', '2020-06-19 11:57:53', '2020-06-19 11:57:53'),
        (12, 'Superadmin', 'committee', 'view', '2020-06-19 11:57:54', '2020-06-19 11:57:54'),
        (13, 'Superadmin', 'dashboard', 'view', '2020-06-19 11:57:55', '2020-06-19 11:57:55'),
        (14, 'Superadmin', 'dashboard', 'insert', '2020-06-19 11:57:55', '2020-06-19 11:57:55'),
        (15, 'Superadmin', 'dashboard', 'update', '2020-06-19 11:57:56', '2020-06-19 11:57:56'),
        (16, 'Superadmin', 'dashboard', 'delete', '2020-06-19 11:57:57', '2020-06-19 11:57:57'),
        (17, 'Superadmin', 'event', 'delete', '2020-06-19 11:57:58', '2020-06-19 11:57:58'),
        (18, 'Superadmin', 'event', 'update', '2020-06-19 11:57:58', '2020-06-19 11:57:58'),
        (19, 'Superadmin', 'event', 'insert', '2020-06-19 11:58:00', '2020-06-19 11:58:00'),
        (20, 'Superadmin', 'event', 'view', '2020-06-19 11:58:00', '2020-06-19 11:58:00'),
        (21, 'Superadmin', 'member', 'view', '2020-06-19 11:58:01', '2020-06-19 11:58:01'),
        (22, 'Superadmin', 'member', 'insert', '2020-06-19 11:58:02', '2020-06-19 11:58:02'),
        (23, 'Superadmin', 'member', 'update', '2020-06-19 11:58:02', '2020-06-19 11:58:02'),
        (24, 'Superadmin', 'member', 'delete', '2020-06-19 11:58:04', '2020-06-19 11:58:04'),
        (25, 'Superadmin', 'news', 'delete', '2020-06-19 11:58:04', '2020-06-19 11:58:04'),
        (26, 'Superadmin', 'news', 'update', '2020-06-19 11:58:05', '2020-06-19 11:58:05'),
        (27, 'Superadmin', 'news', 'insert', '2020-06-19 11:58:06', '2020-06-19 11:58:06'),
        (28, 'Superadmin', 'news', 'view', '2020-06-19 11:58:07', '2020-06-19 11:58:07'),
        (29, 'Superadmin', 'notification', 'view', '2020-06-19 11:58:07', '2020-06-19 11:58:07'),
        (30, 'Superadmin', 'notification', 'insert', '2020-06-19 11:58:08', '2020-06-19 11:58:08'),
        (31, 'Superadmin', 'notification', 'update', '2020-06-19 11:58:09', '2020-06-19 11:58:09'),
        (32, 'Superadmin', 'notification', 'delete', '2020-06-19 11:58:10', '2020-06-19 11:58:10'),
        (33, 'Superadmin', 'paper', 'delete', '2020-06-19 11:58:11', '2020-06-19 11:58:11'),
        (34, 'Superadmin', 'presence', 'delete', '2020-06-19 11:58:11', '2020-06-19 11:58:11'),
        (35, 'Superadmin', 'presence', 'update', '2020-06-19 11:58:12', '2020-06-19 11:58:12'),
        (36, 'Superadmin', 'paper', 'update', '2020-06-19 11:58:13', '2020-06-19 11:58:13'),
        (37, 'Superadmin', 'paper', 'insert', '2020-06-19 11:58:14', '2020-06-19 11:58:14'),
        (38, 'Superadmin', 'presence', 'insert', '2020-06-19 11:58:15', '2020-06-19 11:58:15'),
        (39, 'Superadmin', 'presence', 'view', '2020-06-19 11:58:16', '2020-06-19 11:58:16'),
        (40, 'Superadmin', 'paper', 'view', '2020-06-19 11:58:16', '2020-06-19 11:58:16'),
        (41, 'Superadmin', 'setting', 'view', '2020-06-19 11:58:20', '2020-06-19 11:58:20'),
        (42, 'Superadmin', 'setting', 'insert', '2020-06-19 11:58:21', '2020-06-19 11:58:21'),
        (43, 'Superadmin', 'setting', 'update', '2020-06-19 11:58:21', '2020-06-19 11:58:21'),
        (44, 'Superadmin', 'setting', 'delete', '2020-06-19 11:58:22', '2020-06-19 11:58:22'),
        (45, 'Superadmin', 'transaction', 'delete', '2020-06-19 11:58:23', '2020-06-19 11:58:23'),
        (46, 'Superadmin', 'transaction', 'update', '2020-06-19 11:58:23', '2020-06-19 11:58:23'),
        (47, 'Superadmin', 'transaction', 'insert', '2020-06-19 11:58:24', '2020-06-19 11:58:24'),
        (48, 'Superadmin', 'transaction', 'view', '2020-06-19 11:58:24', '2020-06-19 11:58:24');");
	}

	public function down()
	{
		$this->dbforge->drop_table("access_control", true);
	}

}
