<?php
/**
* Comment Template.
*
* @todo Implement .this needs to be sorted out.
*
* Copyright (c) 2009 Carl Sutton ( dogmatic69 )
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
* @filesource
* @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
* @link http://infinitas-cms.org
* @package sort
* @subpackage sort.comments
* @license http://www.opensource.org/licenses/mit-license.php The MIT License
* @since 0.5a
*/

class AppModel extends Model {
	/**
	* The database configuration to use for the site.
	*/
	var $useDbConfig = 'default';

	/**
	* Behaviors to attach to the site.
	*/
	var $actsAs = array(
		'Containable',
		'Libs.Lockable',
		'Libs.Logable',
		//'Libs.AutomaticAssociation'
	);

	var $blockedPlugins = array(
		'DebugKit',
		'Filter',
		'Libs'
	);

	function getPlugins(){
		$plugins = Configure::listObjects('plugin');

		foreach($plugins as $plugin){
			if (!in_array($plugin, $this->blockedPlugins)){
				$return[Inflector::underscore($plugin)] = $plugin;
			}
		}

		return array('' => 'None') + (array)$return;
	}
}

?>