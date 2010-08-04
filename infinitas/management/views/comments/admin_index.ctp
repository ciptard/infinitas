<?php
    /**
     * Management Comments admin index view file.
     *
     * this is the admin index file that displays a list of comments in the
     * admin section of the blog plugin.
     *
     * Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     *
     * Licensed under The MIT License
     * Redistributions of files must retain the above copyright notice.
     *
     * @filesource
     * @copyright     Copyright (c) 2009 Carl Sutton ( dogmatic69 )
     * @link          http://infinitas-cms.org
     * @package       blog
     * @subpackage    blog.views.comments.admin_index
     * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
     */

    //echo $this->Core->adminIndexHead( $this, $paginator, $filterOptions );
    echo $this->Form->create('Comment', array('url' => array('action' => 'mass')));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'toggle',
                'delete'
            )
        );
        echo $this->Infinitas->adminIndexHead($this, $paginator, $filterOptions, $massActions);
?>
<div class="table">
    <table class="listing" cellpadding="0" cellspacing="0">
        <?php
            echo $this->Infinitas->adminTableHeader(
                array(
                    $this->Form->checkbox('all') => array(
                        'class' => 'first',
                        'style' => 'width:25px;'
                    ),
                    $paginator->sort(__('Where', true), 'class'),
                    $paginator->sort('name') => array(
                        'style' => '50px;'
                    ),
                    $paginator->sort('email') => array(
                        'style' => '50px;'
                    ),
                    $paginator->sort('website') => array(
                        'style' => '50px;'
                    ),
                    $paginator->sort('created') => array(
                        'width' => '100px'
                    ),
                    $paginator->sort('points') => array(
                        'width' => '50px'
                    ),
                    __('Status', true) => array(
                        'class' => 'actions'
                    )
                )
            );

            foreach($comments as $comment){
                ?>
                    <tr class="<?php echo $this->Infinitas->rowClass(); ?>" title="<?php echo sprintf( '%s said :: %s', $comment['Comment']['name'], $this->Text->truncate(htmlspecialchars($comment['Comment']['comment']))); ?>">
                        <td><?php echo $this->Form->checkbox($comment['Comment']['id']); ?>&nbsp;</td>
                        <td><?php echo $comment['Comment']['class']; ?>&nbsp;</td>
                        <td><?php echo $comment['Comment']['name']; ?>&nbsp;</td>
                        <td><?php echo $this->Text->autoLinkEmails($comment['Comment']['email']); ?>&nbsp;</td>
                        <td><?php echo $this->Text->autoLinkUrls($comment['Comment']['website']); ?>&nbsp;</td>
                        <td><?php echo $this->Time->timeAgoInWords($comment['Comment']['created']); ?>&nbsp;</td>
                        <td><?php echo $comment['Comment']['points']; ?>&nbsp;</td>
                        <td>
                            <?php
                            	if(!$comment['Comment']['active']){
                            		echo Inflector::humanize($comment['Comment']['status']);
                            	}
                            	else{
                                	echo $this->Infinitas->status($comment['Comment']['active'], $comment['Comment']['id']);
                            	}
                            ?>
                        </td>
                    </tr>
                <?php
            }
        ?>
    </table>
</div>
<?php echo $this->element('admin/pagination/navigation'); ?>