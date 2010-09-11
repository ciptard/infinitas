<?php
	/**
	 * Display a list of available plugin links
	 * 
	 * Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2010 Carl Sutton ( dogmatic69 )
	 * @link http://www.infinitas-cms.org
	 * @package Infinitas.menu
	 * @subpackage Infinitas.menu.modules
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.8a
	 * 
	 * @author dogmatic69
	 * 
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */
?>
<div class="dashboard">
	<h1><?php echo __('Dashboard', true); ?></h1>
	<ul class="icons">
		<li><?php echo implode('</li><li>', $this->Menu->adminDashboard); ?></li>
	</ul>
</div>