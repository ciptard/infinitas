<?php
	/**
	 *
	 *
	 */
	class Module extends ModulesAppModel{
		public $name = 'Module';

		public $lockable = true;

		public $virtualFields = array(
			'list_name' => "IF(Module.admin = 1, CONCAT('Admin :: ', Module.name), Module.name)",
			'save_name' => "IF(Module.admin = 1, CONCAT('admin/', Module.module), Module.module)"
		);

		public $actsAs = array(
			'Libs.Sequence' => array(
				'group_fields' => array(
					'position_id'
				)
			)
		);

		public $order = array(
			'Module.position_id' => 'ASC',
			'Module.ordering' => 'ASC'
		);

		public $belongsTo = array(
			'Position' => array(
				'className' => 'Modules.ModulePosition',
				'foreignKey' => 'position_id',
				'counterCache' => true,
				'counterScope' => array('Module.active' => 1)
			),
			'Users.Group',
			'Theme' => array(
				'className' => 'Themes.Theme',
				'foreignKey' => 'theme_id'
			),
		);

		public $hasAndBelongsToMany = array(
			'Route' => array(
				'className' => 'Routes.Route',
				'with' => 'Modules.ModulesRoute',
				'foreignKey' => 'module_id',
				'associationForeignKey' => 'route_id',
				'unique' => true
			)
		);

		private $__contain = array(
			'Position' => array(
				'fields' => array(
					'Position.id',
					'Position.name'
				)
			),
			'Group' => array(
				'fields' => array(
					'Group.id',
					'Group.name'
				)
			),
			'Route' => array(
				'fields' => array(
					'Route.id',
					'Route.name',
					'Route.url'
				)
			),
			'Theme' => array(
				'fields' => array(
					'Theme.id',
					'Theme.name'
				)
			)
		);

		public function __construct($id = false, $table = null, $ds = null) {
			parent::__construct($id, $table, $ds);

			$this->subPath = 'views' . DS . 'elements' . DS . 'modules' . DS;

			$this->validate = array(
				'name' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter a name for this module', true)
					)
				),
				'config' => array(
					'validateEmptyOrJson' => array(
						'rule' => 'validateEmptyOrJson',
						'message' => __('Please enter a valid json config or leave blank', true)
					)
				),
				'group_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the group this module is for', true)
					)
				),
				'position_id' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please select the position this module will show in', true)
					)
				),
				'author' => array(
					'notEmpty' => array(
						'rule' => 'notEmpty',
						'message' => __('Please enter the author of this module', true)
					)
				),
				'url' => array(
					'notEmpty' => array(
						'rule' => array('url', true),
						'message' => __('Please enter the url of this module', true)
					)
				)
			);
		}

		/**
		 * decide if its an admin module or not.
		 */
		public function beforeSave($options = array()){
			$this->data['Module']['admin'] = strstr($this->data['Module']['module'], 'admin/') ? 1 : 0;
			return true;
		}

		public function getModules($position = null, $admin = false){
			if (!$position) {
				return array();
			}

			$modules = Cache::read($position . '.' . (($admin) ? 'admin' : 'user'), 'modules');
			if($modules !== false){
				return $modules;
			}

			$modules = $this->find(
				'all',
				array(
					'fields' => array(
						'Module.id',
						'Module.name',
						'Module.plugin',
						'Module.content',
						'Module.module',
						'Module.config',
						'Module.show_heading'
					),
					'conditions' => array(
						'Position.name' => $position,
						'Module.admin' => $admin,
						'Module.active' => 1
					),
					'contain' => $this->__contain
				)
			);
			Cache::write($position . '.' . (($admin) ? 'admin' : 'user'), $modules, 'modules');

			return $modules;
		}

		public function getModule($module = null, $admin = false){
			if (!$module) {
				return array();
			}

			$_module = Cache::read('single.' . (($admin) ? 'admin' : 'user'), 'modules');
			if($_module !== false){
				return $_module;
			}

			$module = $this->find(
				'first',
				array(
					'fields' => array(
						'Module.id',
						'Module.name',
						'Module.plugin',
						'Module.content',
						'Module.module',
						'Module.config',
						'Module.show_heading'
					),
					'conditions' => array(
						'Module.name' => $module,
						'Module.admin' => $admin,
						'Module.active' => 1
					),
					'contain' => $this->__contain
				)
			);
			Cache::write('single.' . (($admin) ? 'admin' : 'user'), $module, 'modules');

			return $module;
		}

		public function getModuleList($plugin = null){
			$admin = $nonAdmin = array();

			$conditions = array();
			$path = APP;
			if ($plugin) {
				$plugin = strtolower($plugin);
				$path   = App::pluginPath($plugin);
				$conditions = array('Module.plugin' => $plugin);
			}

			App::import('File');
			$this->Folder = new Folder($path.$this->subPath);

			$files = $this->Folder->read();

			foreach($files[1] as $file){
				$file = str_replace('.ctp', '', $file);
				$nonAdmin[$file] = Inflector::humanize($file);
			}

			if(!empty($files[0]) && is_dir($path.$this->subPath.'admin')){
				$this->Folder->cd($path.$this->subPath.'admin');
				$files = $this->Folder->read();

				foreach($files[1] as &$file){
					$file = str_replace('.ctp', '', $file);
					$admin['admin/'.$file] = Inflector::humanize($file);
				}
			}

			return array(
				'admin' => $admin,
				'user' => $nonAdmin
			);
		}
	}
