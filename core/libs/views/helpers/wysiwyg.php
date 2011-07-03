<?php
	/**
	 * Comment Template.
	 *
	 * @todo -c Implement .this needs to be sorted out.
	 *
	 * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 *
	 * @filesource
	 * @copyright	 Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link		  http://infinitas-cms.org
	 * @package	   sort
	 * @subpackage	sort.comments
	 * @license	   http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since		 0.5a
	 */

	class WysiwygHelper extends AppHelper{
		var $helpers = array(
			'Form',
			'Html',
			'Javascript'
		);

		function load($editor = null, $field = null, $config = array()){
			switch($editor){
				case 'text':
					return $this->text($field);
					break;
			} // switch

			$editor = 'Wysiwyg'.Inflector::Classify($editor);

			if (!App::import('Helper', $editor.'.'.$editor)) {
				return $this->input($field, array('style' => 'width:98%; height:500px;', 'value' => sprintf(__('%s was not found', true), $editor)));
			}

			$helper = $editor.'Helper';
			$this->_Editor = new $helper;
			$fields = explode('.', $field);

			$heading = '<h3>'.__(ucfirst(isset($fields[1]) ? $fields[1] : $fields[0])).'</h3>';

			return $this->input($field, array('label' => false)).$this->_Editor->editor($field, $config);
		}

		function text($id = null){
			return $this->input($id, array('type' => 'textarea'));
		}

		function input($id, $params = array('style' => 'width:98%; height:500px;')){
			return $this->Form->input($id, $params);
		}
	}
?>