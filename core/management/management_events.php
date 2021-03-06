<?php
	final class ManagementEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Setup',
				'description' => 'Configure Your site',
				'icon' => '/management/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'management', 'controller' => 'management', 'action' => 'site')
			);
		}
		
		public function onSetupConfig(){
			return Configure::load('management.config');
		}

		public function onSetupCache(){
			return array(
				'name' => 'core',
				'config' => array(
					'prefix' => 'core.',
				)
			);
		}

		public function onAdminMenu($event){
			return array();
		}

		public function onSlugUrl($event, $data){
			switch($data['type']){
				case 'comments':
					return array(
						'plugin' => 'management',
						'controller' => 'comments',
						'action' => $data['data']['action'],
						'id' => $data['data']['id'],
						'category' => 'user-feedback'
					);
					break;
			} // switch
		}

		public function onSetupRoutes(){		
			Router::connect(
				'/admin',
				array(
					'plugin' => 'management',
					'controller' => 'management',
					'action' => 'dashboard',
					'admin' => true,
					'prefix' => 'admin'
				)
			);
		}

		public function onGetRequiredFixtures($event){
			return array(
				'Management.Ticket',
				'Management.Session'
			);
		}
	}