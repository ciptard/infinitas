<?php
class R4c8fccf5809c407a80bd58946318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Routes version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Routes';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'routes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
					'url' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'prefix' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'plugin' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'controller' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'action' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
					'values' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'pass' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'rules' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'force_backend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'force_frontend' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'order_id' => array('type' => 'integer', 'null' => false, 'default' => '1'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'theme_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'routes'
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
		'Route' => array(
			array(
				'id' => 7,
				'core' => 0,
				'name' => 'Home Page',
				'url' => '/',
				'prefix' => '',
				'plugin' => 'cms',
				'controller' => 'frontpages',
				'action' => '',
				'values' => '',
				'pass' => '',
				'rules' => '',
				'force_backend' => 0,
				'force_frontend' => 0,
				'order_id' => 1,
				'ordering' => 2,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-01-13 16:50:39',
				'modified' => '2010-01-20 17:45:43'
			),
			array(
				'id' => 8,
				'core' => 0,
				'name' => 'Pages',
				'url' => '/pages/*',
				'prefix' => '',
				'plugin' => '0',
				'controller' => 'pages',
				'action' => 'display',
				'values' => '',
				'pass' => '',
				'rules' => '',
				'force_backend' => 0,
				'force_frontend' => 0,
				'order_id' => 1,
				'ordering' => 3,
				'theme_id' => 4,
				'active' => 1,
				'created' => '2010-01-13 18:26:36',
				'modified' => '2010-01-14 00:38:53'
			),
			array(
				'id' => 13,
				'core' => 0,
				'name' => 'Blog Home - Frontend',
				'url' => '/blog',
				'prefix' => '',
				'plugin' => 'blog',
				'controller' => 'posts',
				'action' => '',
				'values' => '',
				'pass' => '',
				'rules' => '',
				'force_backend' => 0,
				'force_frontend' => 1,
				'order_id' => 1,
				'ordering' => 5,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-01-13 18:47:07',
				'modified' => '2010-01-13 19:10:00'
			),
			array(
				'id' => 14,
				'core' => 0,
				'name' => 'Cms Home - Backend',
				'url' => '/admin/cms',
				'prefix' => 'admin',
				'plugin' => 'cms',
				'controller' => 'categories',
				'action' => 'dashboard',
				'values' => '',
				'pass' => '',
				'rules' => '',
				'force_backend' => 1,
				'force_frontend' => 0,
				'order_id' => 1,
				'ordering' => 6,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-01-13 19:01:14',
				'modified' => '2010-01-13 19:04:59'
			),
			array(
				'id' => 15,
				'core' => 0,
				'name' => 'Cms Home - Frontend',
				'url' => '/cms',
				'prefix' => '',
				'plugin' => 'cms',
				'controller' => 'frontpages',
				'action' => '',
				'values' => '',
				'pass' => '',
				'rules' => '',
				'force_backend' => 0,
				'force_frontend' => 1,
				'order_id' => 1,
				'ordering' => 7,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-01-13 19:05:28',
				'modified' => '2010-01-18 01:40:23'
			),
			array(
				'id' => 19,
				'core' => 0,
				'name' => 'Cms SEO',
				'url' => '/cms/:category/:id-:slug',
				'prefix' => '',
				'plugin' => 'cms',
				'controller' => 'contents',
				'action' => 'view',
				'values' => '',
				'pass' => 'id,slug',
				'rules' => '{\"id\":\"[0-9]+\"}',
				'force_backend' => 0,
				'force_frontend' => 1,
				'order_id' => 1,
				'ordering' => 8,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-01-18 01:35:21',
				'modified' => '2010-01-18 02:09:17'
			),
			array(
				'id' => 18,
				'core' => 0,
				'name' => 'Blog Test',
				'url' => '/p/:year/:month/:day',
				'prefix' => '',
				'plugin' => 'blog',
				'controller' => 'posts',
				'action' => '',
				'values' => 'day:null',
				'pass' => '',
				'rules' => '{\"year\":\"[12][0-9]{3}\",\r\n\"month\":\"0[1-9]|1[012]\",\r\n\"day\":\"0[1-9]|[12][0-9]|3[01]\"}\r\n',
				'force_backend' => 0,
				'force_frontend' => 1,
				'order_id' => 1,
				'ordering' => 9,
				'theme_id' => 1,
				'active' => 1,
				'created' => '2010-01-13 19:36:31',
				'modified' => '2010-01-18 01:35:41'
			),
			array(
				'id' => 22,
				'core' => 0,
				'name' => 'Blog - Posts Seo',
				'url' => '/blog/:category/:id-:slug',
				'prefix' => '',
				'plugin' => 'blog',
				'controller' => 'posts',
				'action' => 'view',
				'values' => '',
				'pass' => 'id,slug',
				'rules' => '{\"id\":\"[0-9]+\"}',
				'force_backend' => 0,
				'force_frontend' => 1,
				'order_id' => 1,
				'ordering' => 10,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-02-10 16:49:30',
				'modified' => '2010-02-18 18:49:20'
			),
			array(
				'id' => 23,
				'core' => 0,
				'name' => 'Contact - Contacts Seo',
				'url' => '/contact/:branch/:id-:slug',
				'prefix' => '',
				'plugin' => 'contact',
				'controller' => 'contacts',
				'action' => 'view',
				'values' => '',
				'pass' => 'id,slug',
				'rules' => '{\"id\":\"[0-9]+\"}',
				'force_backend' => 0,
				'force_frontend' => 1,
				'order_id' => 1,
				'ordering' => 11,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-02-18 18:46:08',
				'modified' => '2010-02-18 18:48:40'
			),
			array(
				'id' => 24,
				'core' => 0,
				'name' => 'Contact - Branches Seo',
				'url' => '/contact/:id-:slug',
				'prefix' => '',
				'plugin' => 'contact',
				'controller' => 'branches',
				'action' => 'view',
				'values' => '',
				'pass' => 'id,slug',
				'rules' => '{\"id\":\"[0-9]+\"}',
				'force_backend' => 0,
				'force_frontend' => 1,
				'order_id' => 1,
				'ordering' => 12,
				'theme_id' => 0,
				'active' => 1,
				'created' => '2010-02-18 18:47:38',
				'modified' => '2010-02-18 18:48:30'
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