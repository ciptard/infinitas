<?php
	final class ThemeEvents extends AppEvents{
		public function onPluginRollCall(){
			return array(
				'name' => 'Themes',
				'description' => 'Theme your site',
				'icon' => '/theme/img/icon.png',
				'author' => 'Infinitas'
			);
		}
		
		public function onSetupConfig(){
			return array();
		}

		public function onSetupCache(){
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Themes' => array('controller' => 'themes', 'action' => 'index')
			);

			return $menu;
		}
	}