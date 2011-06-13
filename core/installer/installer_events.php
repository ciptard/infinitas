<?php
	/* 
	 * Short Description / title.
	 * 
	 * Overview of what the file does. About a paragraph or two
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package {see_below}
	 * @subpackage {see_below}
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since {check_current_milestone_in_lighthouse}
	 * 
	 * @author {your_name}
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class InstallerEvents extends AppEvents{
		public function onSetupRoutes(){
			// infinitas is not installed
			$databaseConfig = APP.'config'.DS.'database.php';
			Router::connect('/install/finish/*', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'finish'));
			Router::connect('/install/:step', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'), array('pass' => array('step')));

			if(!file_exists($databaseConfig) || filesize($databaseConfig) == 0) {
				if(!file_exists($databaseConfig)) {
					$file = fopen($databaseConfig, 'w');
					fclose($file);
				}
				
				Configure::write('Session.save', 'php');
				Router::connect('/', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
				Router::connect('/*', array('plugin' => 'installer', 'controller' => 'install', 'action' => 'index'));
			}

			return true;
		}

		public function onPluginRollCall(){
			return array(
				'name' => 'Plugins',
				'description' => 'Manage, install and remove plugins',
				'icon' => '/installer/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'dashboard'),
			);
		}

		public function onAdminMenu($event){
			$menu['main'] = array(
				'Dashboard' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'dashboard'),
				'Plugins' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'index'),
				'Install' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'install'),
				'Update' => array('plugin' => 'installer', 'controller' => 'plugins', 'action' => 'update_infinitas'),
			);

			return $menu;
		}
	 }