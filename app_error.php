<?php
	/**
	 * App Error class
	 *
	 * Over load cakes default error handeling.
	 *
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 *
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package AppError
	 * @subpackage AppError
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * @author Carl Sutton ( dogmatic69 )
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class AppError extends ErrorHandler {
		
		/**
		 * Event errors
		 * 
		 * Create errors for events that are not configured
		 * properly.
		 * 
		 * @param array $params the array of params to display to the user.
		 */
		public function eventError($params){
			$this->controller->set('params', $params);
			$this->_outputMessage('event_error');
		}

		public function _outputMessage($template) {
			$this->log('Where: ' . serialize($this->controller->params['url']), 'page_errors');
			$this->log('What:  ' . serialize($this->controller->viewVars), 'page_errors');
			$this->controller->render($template);
			$this->controller->afterFilter();
			echo $this->controller->output;

			if(!Configure::read('debug')){
				return;
			}

			$backtrace = debug_backtrace(false);
			?>
				<table width="80%" style="margin:auto;">
					<tr>
						<th>File</th>
						<th>Code</th>
						<th>Line</th>
					</tr>
					<?php
						foreach($backtrace as $_b){
							$_b = array_merge(
								array(
									'file' => '?',
									'class' => '?',
									'type' => '?',
									'function' => '?',
									'line' => '?'
								),
								$_b
							);
							?>
								<tr>
									<td><?php echo $_b['file']; ?></td>
									<td><?php echo $_b['class'], $_b['type'], $_b['function']; ?></td>
									<td><?php echo $_b['line']; ?></td>
								</tr>
							<?php
						}
					?>
				</table>
			<?php
		}
	}