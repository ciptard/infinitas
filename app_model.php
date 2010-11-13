<?php
	App::import('Lib', 'Libs.LazyModel');

	/**
	 * AppModel is the base Model that all models should extend, unless you are
	 * doing something completely different.
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
	class AppModel extends LazyModel {
		/**
		* The database configuration to use for the site.
		*/
		public $useDbConfig = 'default';

		/**
		* Behaviors to attach to the site.
		*/
		public $actsAs = array(			
			'Libs.Infinitas',
			'Events.Event'			
			//'Libs.Logable',
			//'DebugKit.Timed',

			//'Libs.AutomaticAssociation'
		);

		public $recursive = -1;

		/**
		* error messages in the model
		 * @access public
		*/
		public $_errors = array();

		/**
		 * Plugin that the model belongs to.
		 * 
		 * @var string
		 * @access public
		 */
		public $plugin = null;

		/**
		 * auto delete cache
		 *
		 * @var bool
		 */
		public $autoCacheClear = true;

		public function __construct($id = false, $table = null, $ds = null) {
			$this->__getPlugin();

			if($this->tablePrefix != ''){
				$config = $this->getDataSource()->config;

				if(isset($config['prefix'])) {
					$this->tablePrefix = $config['prefix'] . $this->tablePrefix;
				}
			}

			parent::__construct($id, $table, $ds);

			$this->__setupDatabaseConnections();
			
			$thisClass = get_class($this);
			if(php_sapi_name() != 'cli' && $this->alias != 'Session' && ($thisClass == 'AppModel' || $thisClass == 'Model')){
				trigger_error(sprintf(__('%s is using AppModel, please create a model file', true), $this->alias), E_USER_WARNING);
			}

			if (isset($this->_schema) && is_array($this->_schema) && php_sapi_name() != 'cli') {
				if($this->Behaviors->enabled('Event')) {
					$this->triggerEvent('attachBehaviors');
					$this->Behaviors->attach('Containable');
				}
			}
			elseif(php_sapi_name() == 'cli') {
				$this->actsAs = array();
			}
		}

		/**
		 * allow plugins to use their own db configs. If there is a conflict,
		 * eg: a plugin tries to set a config that alreay exists an error will
		 * be thrown and the connection will not be created.
		 *
		 * default is a reserved connection that can only be set in database.php
		 * and not via the events.		 
		 */
		private function __setupDatabaseConnections(){
			$connections = array_filter(current(EventCore::trigger($this, 'requireDatabaseConfigs')));			

			$existingConnections = ConnectionManager::getInstance()->config;			
			
			foreach($connections as $plugin => $connection){
				$key = current(array_keys($connection));
				$connection = current($connection);

				if(strtolower($key) == 'default' || (isset($existingConnections->{$key}) && $existingConnections->{$key} !== $connection)){
					trigger_error(sprintf(__('The connection "%s" in the plugin "%s" has already been used. Skipping', true), $key, $plugin), E_USER_WARNING);
					continue;
				}

				ConnectionManager::create($key, $connection);
			}
		}

		/**
		 * called after something is saved
		 */
		public function afterSave($created){
			$this->__clearCache();
		}

		/**
		 * called after something is deleted.
		 */
		public function afterDelete(){
			$this->__clearCache();
		}

		/**
		 * get a unique list of any model field, used in the search
		 * 
		 * @param string $displayField the field to search by
		 * @param bool $primaryKey if true will return array(id, field) else array(field, field)
		 * @return array the data from the find
		 */
		public function uniqueList($displayField = '', $primaryKey = false){
			if(empty($displayField) || !is_string($displayField) || !$this->hasField($displayField)){
				return false;
			}

			$displayField = $displayField;

			$primaryKey = $primaryKey
				? $this->primaryKey
				: $displayField;

			return $this->find(
				'list',
				array(
					'fields' => array(
						$this->alias . '.' . $primaryKey,
						$this->alias . '.' . $displayField
					),
					'group' => array(
						$this->alias . '.' . $displayField
					),
					'order' => array(
						$this->alias . '.' . $displayField => 'asc'
					)
				)
			);
		}

		/**
		 * Delete all cahce for the plugin.
		 *
		 * Will automaticaly delete all the cache for a model that it can detect.
		 * you can overlaod after save/delete to stop this happening if you dont
		 * want all your cache rebuilt after a save/delete.
		 */
		private function __clearCache(){
			if(in_array($this->plugin, Cache::configured())){
				$_cache = Cache::getInstance()->__config[$this->plugin];
				$path = CACHE.str_replace('.', DS, $_cache['prefix']);
				if(CACHE !== $path && is_dir($path)){
					$Folder = new Folder($path);
					if($Folder->delete()){
						$this->log('deleted: '.$path, 'cache_clearing');
					}
					else{
						$this->log('failed: '.$path, 'cache_clearing');
					}
				}
				else{
					$this->log('skip: '.$path, 'cache_clearing');
				}
			}

			return true;
		}

		/**
		 * Get model name.
		 *
		 * Get a model name with the plugin prepended in the format used in
		 * CR::init() and Usefull for polymorphic relations.
		 *
		 * @return string Name of the model in the form of Plugin.Name.
		 */
		public function modelName() {
			if($this->plugin == null) {
				$this->__getPlugin();
			}

			return $this->plugin == null ? $this->name : $this->plugin . '.' . $this->name;
		}

		/**
		 * Get the current plugin.
		 *
		 * try and get the name of the current plugin from the parent model class
		 *
		 * @return the plugin name
		 */
		private function __getPlugin() {
			$parentName = get_parent_class($this);

			if($parentName !== 'AppModel' && $parentName !== 'Model' && strpos($parentName, 'AppModel') !== false) {
				$this->plugin = str_replace('AppModel', '', $parentName);
			}
		}


		/**
		 * general validation rules
		 * can be moved to a validation behavior
		 */
		/**
		 * This can either be empty or a valid json string.
		 *
		 * @param array $field the field being validated
		 * @return bool is it valid?
		 */
		public function validateEmptyOrJson($field){
			return strlen(current($field)) == 0 || $this->validateJson(current($field));
		}

		/**
		 * allow the selection of one field or another or nothing
		 *
		 * @param array $field not used
		 * @params array $fields list of 2 fields that should be checked
		 * 
		 * @return bool is it valid?
		 */
		public function validateNothingEitherOr($field, $fields = array()){
			return
				// nothing
				empty($this->data[$this->alias][$fields[0]]) && empty($this->data[$this->alias][$fields[1]]) ||

				// either or
				$this->validateEitherOr($field, $fields);
				
		}

		/**
		 * allow the selection of one field or another
		 *
		 * @param array $field not used
		 * @params array $fields list of 2 fields that should be checked
		 *
		 * @return bool is it valid?
		 */
		public function validateEitherOr($field, $fields){
			return
				// either
				empty($this->data[$this->alias][$fields[0]]) && !empty($this->data[$this->alias][$fields[1]]) ||

				// or
				!empty($this->data[$this->alias][$fields[0]]) && empty($this->data[$this->alias][$fields[1]]);
		}

		/**
		 * this can be a url relative to the site /my/page or full like
		 * http://site.com/my/page it can also be empty for times when the selects
		 * are used to build the url
		 *
		 * @param array $field the field being validated
		 * @return bool is it valid
		 */
		public function validateUrlOrAbsolute($field){
			return
				// not in use
				current($field) == '' ||

				// aboulute url
				substr(current($field), 0, 1) == '/' ||

				// valid url
				Validation::url(current($field), true);
		}

		/**
		 * compare 2 fields and make sure they are the same
		 *
		 * @param array $field not used
		 * @param bool $fields the fields to compare
		 */
		public function validateCompareFields($field, $fields){
			if($fields[0] == 'password'){
				return Security::hash($this->data[$this->alias][$fields[1]], null, true) === $this->data[$this->alias][$fields[0]];
			}

			return $this->data[$this->alias][$fields[0]] === $this->data[$this->alias][$fields[1]];
		}
	}

	/**
	 * CoreAppModel is used by most of Infinitas core models. All this does is
	 * Set the table prefix so it does not need to be set in every {Core}AppModel
	 */
	class CoreAppModel extends AppModel{
		public $tablePrefix = 'core_';
	}