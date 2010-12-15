<?php
	class LibsEvents extends AppEvents{
		public function onSetupConfig(){
			return
			Configure::load('libs.images') &&
			Configure::load('libs.config');
		}

		public function onSetupExtensions(){
			return array(
				'json'
			);
		}

		public function onAttachBehaviors($event) {
			if(is_subclass_of($event->Handler, 'Model') && isset($event->Handler->_schema) && is_array($event->Handler->_schema)) {
				
				// attach the expandable (eva) behavior if there is a table for it
				$attributesTable = Inflector::singularize($event->Handler->tablePrefix.$event->Handler->table).'_attributes';
				if(in_array($attributesTable, $event->Handler->getTables($event->Handler->useDbConfig))){
					$event->Handler->bindModel(
						array(
							'hasMany' => array(
								$event->Handler->name.'Attribute' => array(
									'className' => Inflector::camelize($attributesTable),
									'foreignKey' => Inflector::underscore($event->Handler->name).'_id',
									'dependent' => true
								)
							)
						),
						false
					);

					$event->Handler->Behaviors->attach('Libs.Expandable');
				}

				if ($event->Handler->hasField('slug') && !$event->Handler->Behaviors->enabled('Libs.Sluggable')) {
					$event->Handler->Behaviors->attach(
						'Libs.Sluggable',
						array(
							'label' => array($event->Handler->displayField)
						)
					);
				}

				if ($event->Handler->hasField('ordering') && !$event->Handler->Behaviors->enabled('Libs.Sequence')) {
					$event->Handler->Behaviors->attach('Libs.Sequence');
				}

				if ($event->Handler->hasField('rating') && !$event->Handler->Behaviors->enabled('Libs.Rateable')) {
					$event->Handler->Behaviors->attach('Libs.Rateable');
				}

				if ($event->Handler->hasField('lft') && $event->Handler->hasField('rght') && !$event->Handler->Behaviors->enabled('Tree')) {
					$event->Handler->Behaviors->attach('Tree');
				}
				
				$event->Handler->Behaviors->attach('Validation');
			}
		}

		public function onRequireComponentsToLoad($event){
			return array(
				'Libs.Infinitas',
				'Session','RequestHandler', 'Auth', 'Acl', 'Security', // core general things from cake
				'Libs.MassAction'
			);
		}

		public function onRequireHelpersToLoad($event){
			return array(
				'Html', 'Form', 'Javascript', 'Session', 'Time', // core general things from cake
				'Libs.Infinitas' => true
			);
		}

		public function onRequireCssToLoad(){
			return '/assets/css/jquery_ui';
		}
	}