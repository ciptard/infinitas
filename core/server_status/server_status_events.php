<?php
	final class ServerStatusEvents extends AppEvents{
		public function __construct() {
			parent::__construct();


		}
		
		public function onAdminMenu($event){
			$menu['main']['Dashboard'] = array('controller' => 'server_status', 'action' => 'dashboard');
			switch($event->Handler->params['controller']){
				case 'php':
					$menu['main']['Php Info'] = array('controller' => 'php', 'action' => 'info');
					$menu['main']['APC'] = array('controller' => 'php', 'action' => 'apc');
					break;
			}

			return $menu;
		}
	}