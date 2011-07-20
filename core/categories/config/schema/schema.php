<?php 
/* SVN FILE: $Id$ */
/* Categories schema generated on: 2011-07-20 22:07:08 : 1311195668*/
class CategoriesSchema extends CakeSchema {
	var $name = 'Categories';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}

	var $categories = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => '0', 'key' => 'index'),
		'group_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 3, 'key' => 'index'),
		'item_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'parent_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
		'rght' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'views' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'tree_list_all' => array('column' => array('lft', 'id', 'title', 'rght'), 'unique' => 1), 'cat_idx' => array('column' => array('active', 'group_id'), 'unique' => 0), 'idx_access' => array('column' => 'group_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);
}
?>