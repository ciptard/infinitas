<?php 
/* SVN FILE: $Id$ */
/* Libs schema generated on: 2011-07-20 22:07:18 : 1311196038*/
class LibsSchema extends CakeSchema {
	var $name = 'Libs';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $ratings = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'class' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'foreign_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'rating' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 3),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>