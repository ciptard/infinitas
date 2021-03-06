<?php
class R4c94edcd61c04d8992e678d86318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Installer version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Installer';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'plugins' => array(
					'id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 36, 'key' => 'primary'),
					'internal_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'unique'),
					'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'author' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'website' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'update_url' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'description' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'license' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 75),
					'dependancies' => array('type' => 'text', 'null' => false, 'default' => NULL),
					'version' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10),
					'enabled' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
					'core' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'update_url' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'internal_name' => array('column' => 'internal_name', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'plugins'
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