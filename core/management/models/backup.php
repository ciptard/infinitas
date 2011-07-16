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

	class Backup extends ManagementAppModel {
		public $name = 'Backup';

		public $lastId = 0;
		
		/**
		 * Constructor
		 *
		 * @access protected
		 */
		public function getLastBackup($odel = null, $plugin = null) {
			$lastBackup = $this->find(
				'first',
				array(
					'fields' => array(
						'Backup.last_id'
					),
					'conditions' => array(
						'Backup.plugin' => $plugin,
						'Backup.model' => $model
					),
					'order' => array(
						'Backup.id' => 'DESC'
					)
				)
			);

			if (!empty($lastBackup)) {
				$this->lastId = $lastBackup['Backup']['last_id'];
			}

			return $this->lastId;
		}

		public function getRecordsForBackup($Model) {
			return $Model->find(
				'all',
				array(
					'conditions' => array(
						$Model->alias . '.id > ' => $this->lastId
					),
					'contain' => false
				)
			);
		}
	}