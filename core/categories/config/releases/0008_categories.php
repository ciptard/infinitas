<?php
class R4c8e68c0ac604ff284e938ba6318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Categories version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Categories';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'categories' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3, 'key' => 'index'),
					'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'views' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0),
						'idx_access' => array('column' => 'group_id', 'unique' => 0),
						'idx_checkout' => array('column' => 'locked', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'categories'
			),
		),
	);

/**
 * Fixtures for data
 *
 * @var array $fixtures
 * @access public
 */
	public $fixtures = array(
	'core' => array(
		'Categories.Category' => array(
			array(
				'id' => 1,
				'title' => 'Infinitas News',
				'slug' => 'infinitas-news',
				'description' => '<p>\r\n	News about the progres, development and what to expect in the future.</p>\r\n',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 3,
				'parent_id' => 0,
				'lft' => 1,
				'rght' => 2,
				'views' => 1,
				'created' => '2010-04-09 10:27:18',
				'modified' => '2010-05-21 01:49:30'
			),
			array(
				'id' => 4,
				'title' => 'Infinitas Core',
				'slug' => 'infinitas-core',
				'description' => '<p>\r\n	How and why things work</p>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 0,
				'parent_id' => 0,
				'lft' => 3,
				'rght' => 18,
				'views' => 2,
				'created' => '2010-05-21 00:20:50',
				'modified' => '2010-05-21 00:32:23'
			),
			array(
				'id' => 5,
				'title' => 'Blog',
				'slug' => 'blog',
				'description' => '<p>\r\n	The bolg internals and what you need to know</p>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 0,
				'parent_id' => 4,
				'lft' => 4,
				'rght' => 9,
				'views' => 1,
				'created' => '2010-05-21 00:21:36',
				'modified' => '2010-05-21 00:32:50'
			),
			array(
				'id' => 9,
				'title' => 'Setting up your blog',
				'slug' => 'setting-up-your-blog',
				'description' => '<p>\r\n	How to setup your blog to suite the needs of your site</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 0,
				'parent_id' => 5,
				'lft' => 5,
				'rght' => 6,
				'views' => 0,
				'created' => '2010-05-21 00:39:44',
				'modified' => '2010-05-21 00:39:44'
			),
			array(
				'id' => 10,
				'title' => 'Managing your blog the easy way',
				'slug' => 'managing-your-blog-the-easy-way',
				'description' => '<p>\r\n	All the information you need to know about getting your blog setup and running</p>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 4,
				'parent_id' => 5,
				'lft' => 7,
				'rght' => 8,
				'views' => 0,
				'created' => '2010-05-21 00:39:55',
				'modified' => '2010-05-21 00:40:48'
			),
			array(
				'id' => 6,
				'title' => 'Cms',
				'slug' => 'cms',
				'description' => '<p>\r\n	The cms internals and what you need to know</p>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 0,
				'parent_id' => 4,
				'lft' => 10,
				'rght' => 11,
				'views' => 0,
				'created' => '2010-05-21 00:23:40',
				'modified' => '2010-05-21 00:37:33'
			),
			array(
				'id' => 7,
				'title' => 'Newsletters',
				'slug' => 'newsletters',
				'description' => '<p>\r\n	The newsletter internals and what you need to know</p>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 0,
				'parent_id' => 4,
				'lft' => 12,
				'rght' => 13,
				'views' => 0,
				'created' => '2010-05-21 00:36:32',
				'modified' => '2010-05-21 00:38:12'
			),
			array(
				'id' => 8,
				'title' => 'Shop',
				'slug' => 'shop',
				'description' => '<p>\r\n	The shop internals and what you need to know</p>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 0,
				'parent_id' => 4,
				'lft' => 14,
				'rght' => 15,
				'views' => 0,
				'created' => '2010-05-21 00:38:21',
				'modified' => '2010-05-21 00:38:49'
			),
			array(
				'id' => 11,
				'title' => 'Whats happening behind the scens',
				'slug' => 'whats-happening-behind-the-scens',
				'description' => '<p>\r\n	Functionality that is available throughout the site.</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
				'active' => 1,
				'locked' => 0,
				'locked_since' => NULL,
				'locked_by' => NULL,
				'group_id' => NULL,
				'item_count' => 4,
				'parent_id' => 4,
				'lft' => 16,
				'rght' => 17,
				'views' => 0,
				'created' => '2010-05-21 01:17:24',
				'modified' => '2010-05-21 01:17:24'
			),
		),
		),
	);
	
/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>