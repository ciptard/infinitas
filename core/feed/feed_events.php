<?php
	final class FeedEvents extends AppEvents {
		public function onPluginRollCall(){
			return array(
				'name' => 'Feeds',
				'description' => 'Provide RSS feeds of anything to your users',
				'icon' => '/feed/img/icon.png',
				'author' => 'Infinitas',
				'dashboard' => array('plugin' => 'feed', 'controller' => 'feeds', 'action' => 'index')
			);
		}

		public function onAdminMenu(&$event){
			$menu['main'] = array(
				'Feeds' => array('plugin' => 'feed', 'controller' => 'feeds', 'action' => 'index')
			);

			return $menu;
		}

		public function onSetupCacheStart(){
			return array(
				'name' => 'feed',
				'config' => array(
					'prefix' => 'feed.',
				)
			);
		}

		public function onSetupExtentions(){
			return array(
				'rss'
			);
		}

		public function onListAvailableFeeds(){
			return ClassRegistry::init('Feed.Feed')->listFeeds();
		}
	}