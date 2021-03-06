<?php
    /**
     * Management Config admin edit post.
     *
     * this page is for admin to manage the setup of the site
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       management
     * @subpackage    management.views.configs.admin_edit
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    echo $this->Form->create('ShortUrl');
        echo $this->Infinitas->adminEditHead(); ?>
		<fieldset>
			<h1><?php echo __('Short Url', true); ?></h1><?php
			echo $this->Form->input('id');
			echo $this->Form->input('url', array('type' => 'text')); ?>
		</fieldset>
	<?php echo $this->Form->end(); ?>