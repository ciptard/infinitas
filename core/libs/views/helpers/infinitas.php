<?php
	/**
	 * Infinitas Helper.
	 *
	 * Does a lot of stuff like generating ordering buttons, load modules and
	 * other things needed all over infinitas.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package libs
	 * @subpackage libs.views.helpers.infinitas
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class InfinitasHelper extends AppHelper{
		public $helpers = array(
			'Html',
			'Form',
			'Libs.Design',
			'Libs.Image',
			'Libs.Wysiwyg'
		);

		/**
		* JSON errors.
		*
		* Set up some errors for json.
		* @access public
		*/
		public $jsonErrors = array(
			JSON_ERROR_NONE	  => 'No error',
			JSON_ERROR_DEPTH	 => 'The maximum stack depth has been exceeded',
			JSON_ERROR_CTRL_CHAR => 'Control character error, possibly incorrectly encoded',
			JSON_ERROR_SYNTAX	=> 'Syntax error',
		);

		protected $_menuData = '';

		protected $_menuLevel = 0;

		public $external = true;

		public $View = null;

		private $__rowCount = 0;

		private $__massActionCheckBoxCounter = 0;

		/**
		 * Set to true when the menu has a current marker to avoid duplicates.
		 * @var unknown_type
		 */
		protected $_currentCssDone = false;

		/**
		* Create a status icon.
		*
		* Takes a int 0 || 1 and returns a icon with title tags etc to be used
		* in places like admin to show iff something is on/off etc.
		*
		* @param int $status the status tinyint(1) from a db find.
		*
		* @return string some html for the generated image.
		*/
		public function status($status = null){
			$image = false;
			$params = array();

			switch (strtolower($status)){
				case 1:
				case 'yes':
				case 'on':
					if ($this->external){
						$params['title'] = __( 'Active', true );
					}

					$image = $this->Html->image(
						$this->Image->getRelativePath('status', 'active'),
						$params + array(
							'width' => '16px',
							'alt' => __('On', true)
						)
					);
					break;

				case 0:
				case 'no':
				case 'off':
					if ($this->external){
						$params['title'] = __('Disabled', true);
					}

					$image = $this->Html->image(
						$this->Image->getRelativePath('status', 'inactive'),
						$params + array(
							'width' => '16px',
							'alt' => __('Off', true)
						)
					);
					break;
			}

			return $image;
		}

		/**
		 * Featured icon.
		 *
		 * Creates a featured icon like the status and locked.
		 *
		 * @param array $record the data from find
		 * @param string $model the model alias
		 *
		 * @return string html of the icon.
		 */
		public function featured($record = array(), $model = 'Feature'){
			if (empty($record[$model])){
				$this->messages[] = 'This has no featured items.';

				return $this->Html->image(
					$this->Image->getRelativePath('status', 'not-featured'),
					array(
						'alt'   => __('No', true),
						'title' => __('Not a featured item', true),
						'width' => '16px'
					)
				);
			}

			return $this->Html->image(
				$this->Image->getRelativePath('status', 'featured'),
				array(
					'alt'   => __('Yes', true),
					'title' => __('Featured Item', true),
					'width' => '16px'
				)
			);
		}

		public function loggedInUserText($counts){
			$allInIsAre	= ($counts['all'] > 1) ? __('are', true) : __('is', true);
			$loggedInIsAre = ($counts['loggedIn'] > 1) ? __('are', true) : __('is', true);
			$guestsIsAre   = ($counts['guests'] > 1) ? __('are', true) : __('is', true);
			$guests		= ($counts['guests'] > 1) ? __('guests', true) : __('a guest', true);

			return '<p>'.
				sprintf(
					__('There %s %s people on the site, %s %s logged in and %s %s %s.', true),
					$allInIsAre, $counts['all'],
					$counts['loggedIn'], $loggedInIsAre,
					$counts['guests'], $guestsIsAre,
					$guests
				).
			'</p><p>&nbsp;</p>';
		}

		/**
		 * @brief generate a checkbox for rows that use mass_action stuff
		 *
		 * it will keep track of the $i for the checkbox number so there are no duplicates.
		 * MassActionComponent::filter() will remove these fields from the searches so there
		 * are no sql errors.
		 *
		 * @param array $data the row from find either find('first') or find('all')[x]
		 * @param array $options set the fk or model manually
		 *
		 * @return a checkbox
		 */
		public function massActionCheckBox($data = array(), $options = array()){
			$model = isset($this->params['models'][0]) ? $this->params['models'][0] : null;
			$options = array_merge(
				array('model' => $model, 'primaryKey' => ClassRegistry::init($model)->primaryKey, 'hidden' => false, 'checked' => false),
				$options
			);

			if(!$data || !isset($data[$options['model']])){
				return false;
			}

			$checkbox = $this->Form->checkbox(
				$options['model'] . '.' . $this->__massActionCheckBoxCounter . '.' . 'massCheckBox',
				array(
					'value' => $data[$options['model']][$options['primaryKey']],
					'hidden' => $options['hidden'],
					'checked' => $options['checked']
				)
			);

			$this->__massActionCheckBoxCounter++;

			return $checkbox;
		}
	}
