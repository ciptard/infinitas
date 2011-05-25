<?php
	class InstallerLib{
		/**
		 * minimum php version number required to run infinitas
		 * @var <type>
		 */
		private $__phpVersion = '5.0';

		/**
		 * list of databases that infinitas supports
		 * @var <type>
		 */
		private $__supportedDatabases = array(
			'mysql' => array(
				'name' => 'MySQL',
				'version' => '5.0',
				'versionQuery' => 'select version();',
			 	'function' => 'mysql_connect',
			),
			'mysqli' => array(
				'name' => 'MySQLi',
				'version' => '5.0',
				'versionQuery' => 'select version();',
				'function' => 'mysqli_connect',
			),
			'mssql' => array(
				'name' => 'Microsoft SQL Server',
				'version' => '8.0',
				'versionQuery' => 'SELECT CAST(SERVERPROPERTY(\'productversion\') AS VARCHAR)',
				'function' => 'mssql_connect',
			)
		);

		/**
		 * reccomended settings for running infinitas
		 * @var <type>
		 */
		private $__recommendedIniSettings = array (
			array (
				'setting' => 'safe_mode',
				'recomendation' => 0,
				'desc' => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0'
			),
			array (
				'setting' => 'display_errors',
				'recomendation' => 1,
				'desc' => 'Infinitas will handle errors througout the app'
			),
			array (
				'setting' => 'file_uploads',
				'recomendation' => 1,
				'desc' => 'File uploads are needed for the wysiwyg editors and system installers'
			),
			array (
				'setting' => 'magic_quotes_runtime',
				'recomendation' => 0,
				'desc' => 'This function has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
			),
			array (
				'setting' => 'register_globals',
				'recomendation' => 0,
				'desc' => 'This feature has been DEPRECATED as of PHP 5.3.0 and REMOVED as of PHP 6.0.0. Relying on this feature is highly discouraged.'
			),
			array (
				'setting' => 'output_buffering',
				'recomendation' => 0,
				'desc' => 'Infinitas will handle output_buffering for you throughout the app'
			),
			array (
				'setting' => 'session.auto_start',
				'recomendation' => 0,
				'desc' => 'Sessions are completly handled by Infinitas'
			),
		);

		/**
		 * paths that infinitas will need to install its self
		 * @var <type>
		 */
		private $__paths = array(
			'config/database.php' => array(
				'type' => 'write',
				'message' => 'The database configuration (%s) file should be writable during the installation process. It should be set to be read-only once Infinitas has been installed.'
			),
			'tmp' => array(
				'type' => 'write',
				'message' => 'The temporary directory (%s) should be writable by Infinitas. Caching will not work otherwise and Infinitas will perform very poorly.',
			)
		);

		/**
		 * extentions that are required to run infinitas
		 * @var <type>
		 */
		private $__requiredExtensions = array(
			'zlib' => 'Zlib is required for some of the functionality in Infinitas'
		);

		/**
		 * locations of sql files
		 * @var <type>
		 */
		public $sql = array();

		private $__licence = array();

		private $__welcome = array();

		public $steps = array(
			'welcome',
			'database',
			'install',
			'admin_user',
		);

		public function __construct(){
			$this->sql = array(
				'core_data' => APP . 'infinitas' . DS . 'installer' . DS . 'config' . DS . 'schema' . DS . 'infinitas_core_data.sql',
				'core_sample_data' => APP . 'infinitas' . DS . 'installer' . DS . 'config' . DS . 'schema' . DS . 'infinitas_sample_data.sql',
			);

			$message = array(
				__d('installer',
					'Thank-you for choosing %s to power your website.', true),
				__d('installer',
					'Since you are on the %s installer you probably know a bit about %s. '. "\n" .
					'Before you go to the next step, make sure that you have create '. "\n" .
					'a database and you have the database details at hand. '. "\n" .
					'If you are unsure how to create a database, contact your web '. "\n" .
					'host support.', true),
				__d('installer',
					'%s uses the MIT License, the full license is shown below for '. "\n" .
					'your information. Unless you plan on modifiying and '. "\n" .
					'redistributing %s you do not need to worry about the license. '. "\n" .
					'Note that this license only applies to the %s core code, some '. "\n" .
					'extensions may have other licenses.',
					true)
			);



			$siteName = '<b>Infinitas</b>';

			$this->__welcome['html'] =  str_replace('%s', $siteName, implode('</p><p>', $message));
			$this->__welcome['text'] = strip_tags(str_replace('</p><p>', "\r\n", $this->__welcome['html']));

			$date = date('Y');
			$this->__license['html'] = <<<LICENCE
<blockquote><h2>MIT License</h2><p>Copyright &copy; 2009 - $date Infinitas</p><p>Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:</p><p>The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.</p><p>THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p></blockquote>
LICENCE;
			$text = explode('</p><p>', str_replace('&copy;', '©', $this->__license['html']));
			
			$this->__license['text'] = '';
			foreach($text as $t){
				$this->__license['text'] .= wordwrap(strip_tags($t), 75, "\r\n");
				$this->__license['text'] .= "\r\n";
			}
		}

		/**
		 * get the licence to display on the installler.
		 *
		 * @param string $type the type of licence to return
		 *
		 * @return string the licence
		 */
		public function getLicense($type = 'html'){
			return $this->__getData($type, '__license');
		}

		/**
		 * get the welcome message to display on the installler.
		 *
		 * @param string $type the type of licence to return
		 *
		 * @return string the licence
		 */
		public function getWelcome($type = 'html'){
			return $this->__getData($type, '__welcome');
		}

		private function __getData($type, $from){
			$type = (string)$type;

			if(!isset($this->{$from}[$type])){
				$type = 'html';
			}

			return $type == 'text' ? html_entity_decode($this->{$from}[$type]) : $this->{$from}[$type];
		}

		/**
		 * check what db's are available
		 * @return array
		 */
		public function getSupportedDbs($databaseSupported = false) {
			if(!empty($this->__supportedDatabases)){
				return $this->__supportedDatabases;
			}

			foreach($this->__supportedDatabases as $cakeType => $supportedDatabase) {
				if(function_exists($supportedDatabase['function'])) {
					$this->__supportedDatabases[$cakeType] = sprintf(__('%s (Version %s or newer)', true), $supportedDatabase['name'], $supportedDatabase['version']);;
				}
			}

			return $databaseSupported == true 
				? !empty($this->__supportedDatabases)
				: $this->__supportedDatabases;
		}

		/**
		 * Test if the details provided to connect to the database are correct
		 */
		public function testConnection($connection = array()){
			App::import('Model', 'Installer.Install');

			$install = new Install(false, false, false);
			
			$install->set($connection);
			if($install->validates()) {
				$connectionDetails = $connection;
				$adminConnectionDetails = !isset($connection['root'])
					? false
					: array_merge($connection, $connection['root']);

				if(!@ConnectionManager::create('installer', $connectionDetails)->isConnected()) {
					return 'dbError';
				}
				else {
					if(trim($adminConnectionDetails['login']) != '') {
						if(!@ConnectionManager::create('admin', $adminConnectionDetails)->isConnected()) {
							$this->set('adminDbError', true);
							return false;
						}
					}

					$version = $this->__databaseVersion($connectionDetails);

					if(version_compare($version, $version) >= 0) {
						return true;
					}
					
					else {
						return array(
							'versionError' => $version,
							'requiredDb' => $this->__supportedDatabases[$connection['driver']]['version']
						);
					}
				}
			}

			return false;
		}

		public function populateDatabase($config){
			App::import('Core', 'ConnectionManager');
			$this->config = $config;

			$dbConfig = $this->__cleanConnectionDetails($config);

			$db = ConnectionManager::create('default', $dbConfig);

			$plugins = App::objects('plugin');
			natsort($plugins);

			App::import('Lib', 'Installer.ReleaseVersion');
			$Version = new ReleaseVersion();

			//Install app tables first
			$result = $this->__installPlugin($Version, $dbConfig, 'app');

			$result = true;
			if($result) {
				//Then install the Installer plugin
				$result = $result && $this->__installPlugin($Version, $dbConfig, 'Installer');

				if($result) {
					//Then install all other plugins
					foreach($plugins as $plugin) {
						if($plugin != 'Installer') {
							$result = $result && $this->__installPlugin($Version, $dbConfig, $plugin);
						}
					}
				}
			}

			$this->Plugin = ClassRegistry::init('Installer.Plugin');
			print_r($this->_saved);
			exit;
			foreach($this->_saved as $pluginName => $options){
				$this->Plugin->installPlugin($pluginName, $options);
			}

			return $result;
		}

		private function __installPlugin($Version, $dbConfig, $plugin = 'app') {		
			echo sprintf('Installing :: %s' . "\r\n", $plugin);
			
			if($plugin !== 'app') {
				$pluginPath = App::pluginPath($plugin);
				$configPath = $pluginPath . 'config' . DS;
				$checkFile = $configPath . 'config.json';
			}
			else {
				$configPath = APP . 'config' . DS;
				$checkFile = $configPath . 'releases' . DS . 'map.php';
			}

			if(file_exists($checkFile)) {
				if(file_exists($configPath . 'releases' . DS . 'map.php')) {
					try {
						echo sprintf('%sFixtures%s', "\t", "\r\n");
						$mapping = $Version->getMapping($plugin);

						$latest = array_pop($mapping);

						echo sprintf('%sStructure%s', "\t", "\r\n");
						$versionResult = $Version->run(array(
							'type' => $plugin,
							'version' => $latest['version'],
							'basePrefix' => (isset($dbConfig['prefix']) ? $dbConfig['prefix'] : ''),
							'sample' => (bool)$this->config['sample_data']
						));
					}
					catch (Exception $e) {
						return false;
					}
				}
				else {
					$versionResult = true;
				}

				if($versionResult && $plugin !== 'app') {
					echo sprintf('%sSave%s', "\t", "\r\n");
					$this->_saved[] = array(
						$plugin,
						array('installRelease' => false)
					);
				}

				return $versionResult;
			}

			return true;
		}

		/**
		 * remove unused details from the supplied connection and set the root
		 * username / password if there is one provided.
		 */
		function __cleanConnectionDetails($connectionDetails = array()) {
			$config = $connectionDetails['connection'];
			unset($config['step']);

			if(trim($config['port']) == '') {
				unset($config['port']);
			}

			if(trim($config['prefix']) == '') {
				unset($config['prefix']);
			}

			if($connectionDetails['root']['username'] != false) {
				$config['login'] = $connectionDetails['root']['username'];
				$config['password'] = $connectionDetails['root']['password'];
			}

			return $config;
		}

		/**
		 * get the version of the currently selected database engine.
		 * 
		 * @param <type> $connectionDetails
		 * @return <type>
		 */
		public function __databaseVersion($connectionDetails){
			$dbOptions = $this->__supportedDatabases[$connectionDetails['driver']];
			$version = ConnectionManager::getDataSource('installer')->query($dbOptions['versionQuery']);
			$version = isset($version[0][0]['version()']) ? $version[0][0]['version()'] : false;

			$version = explode('-', $version);
			return $version[0];
		}

		/**
		 * try and determin the type of databases that are available for the
		 * application to use.
		 */
		public function findDbs(){

		}
	}