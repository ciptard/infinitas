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
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       sort
     * @subpackage    sort.comments
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     * @since         0.5a
     */

	if(!Configure::read(Inflector::camelize($this->plugin).'.allow_comments')){
		return false;
	}
?>
<div class="comments">
	<?php
		echo $this->Html->link(
			__('View all comments', true),
			array(
				'plugin' => 'management',
				'controller' => 'comments',
				'action' => 'index',
				'Comment.class' => 'Content'
			)
		);
		
        /**
         * fields allowed in the comments
         */
        $commentFields = explode(',',Configure::read('Comment.fields'));

        $modelName = (isset($modelName)) ? $modelName : Inflector::singularize($this->name);
    	$Model     = ClassRegistry::init($this->params['plugin'].'.'.$modelName);
		$data = &${strtolower($modelName)};
		
		foreach($data[$modelName]['Comment'] as $comment){
			echo '<div class="comment">blaa</div>';
		}

		if(isset($this->data[$modelName]) && is_array($this->data[$modelName])){
			$this->data[$modelName] = array_merge((array)$this->Session->read('Auth.User'), $this->data[$modelName]);
		}
		else{
			$this->data[$modelName] = $this->Session->read('Auth.User');
		}

        if (isset($urlParams)){
            echo $this->Form->create(
                $modelName,
                array(
                	'url' => array(
                		'action' => 'comment',
                		$urlParams
                	)
                )
            );
        }

        else{
            echo $this->Form->create(
                $modelName,
                array(
                	'url' => array(
                		'action' => 'comment'
                	)
                )
            );
        }

			echo $this->Form->input($modelName.'.'.$Model->primaryKey, array('value' => $data[$modelName][$Model->primaryKey]));

			foreach($commentFields as $field){
				if ($field != 'comment'){
					$value = '';
					$method = 'input';
					if(isset($this->data[$modelName][$field])){
						$value = isset($this->data[$modelName][$field]) ? $this->data[$modelName][$field] : '';
						if($this->action != 'comment'){
							$method = 'hidden';
						}
					}
					echo $this->Form->{$method}('Comment.'.$field, array('value' => $value));
					continue;
				}
				
				
				$options = array('type' => 'textarea', 'class' => 'title');
				$submitOptions = array();
				if($this->action != 'comment'){
					$options = array(
						'label' => false,
						'div' => false,
						'type' => 'text',
						'value' => __('Enter your comment...', true)
					);
					$submitOptions = array('div' => false);
					echo '<div class="comment">';
				}
				
				echo $this->Form->input('Comment.comment', $options);

				echo $this->Form->submit('Submit', $submitOptions);
				
				if($this->action != 'comment'){
					echo '</div>';
				}
			}			
		echo $this->Form->end();
	?>
</div>