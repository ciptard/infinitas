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

    echo $this->Form->create('Campaign', array('action' => 'mass'));
        $massActions = $this->Infinitas->massActionButtons(
            array(
                'add',
                'edit',
                'copy',
                'toggle',
                'delete'
            )
        );
	echo $this->Infinitas->adminIndexHead($filterOptions, $massActions);

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
                    $paginator->sort('name'),
                    $paginator->sort('description'),
                    $paginator->sort('Template', 'Template.name'),
                    $paginator->sort(__( 'Newsletters', true), 'newsletter_count') => array(
                        'width' => '50px'
                    ),
                    $paginator->sort('created') => array(
                        'style' => 'width:100px;'
                    ),
                    $paginator->sort('modified') => array(
                        'style' => 'width:100px;'
                    ),
                    __('Status', true) => array(
                        'class' => 'actions',
                        'width' => '50px'
                    )
                )
            );

            foreach($campaigns as $campaign){
                ?>
                    <tr class="<?php echo $this->Infinitas->rowClass(); ?>">
                        <td><?php echo $this->Infinitas->massActionCheckBox($campaign); ?>&nbsp;</td>
                        <td><?php echo $campaign['Campaign']['name']; ?></td>
                        <td><?php echo $campaign['Campaign']['description']; ?></td>
                        <td>
                            <?php
                                echo $this->Html->link(
                                    $campaign['Template']['name'],
                                    array(
                                        'controller' => 'templates',
                                        'action' => 'view',
                                        $campaign['Template']['id']
                                    )
                                );
                            ?>
                        </td>
                        <td style="text-align:center;"><?php echo $campaign['Campaign']['newsletter_count']; ?></td>
                        <td><?php echo $this->Time->niceShort($campaign['Campaign']['created']); ?></td>
                        <td><?php echo $this->Time->niceShort($campaign['Campaign']['modified']); ?></td>
                        <td>
                            <?php
                                $newsletterStatuses = Set::extract('/Newsletter/sent', $campaign);
                                $campaignSentStatus = true;

                                if (empty($newsletterStatuses)){
                                    echo $this->Html->link(
                                        $this->Html->image(
                                            'core/icons/actions/16/warning.png',
                                            array(
                                                'alt' => __('No Mails', true),
                                                'title' => __('This Campaign has no mails. Click to add some', true)
                                            )
                                        ),
                                        array(
                                            'controller' => 'newsletters',
                                            'action' => 'add',
                                            'campaign_id' => $campaign['Campaign']['id']
                                        ),
                                        array(
                                            'escape' => false
                                        )
                                    );
                                }

                                else{
                                    foreach($newsletterStatuses as $newsletterStatus){
                                        $campaignSentStatus = $campaignSentStatus && $newsletterStatus;
                                    }

                                    if ($campaignSentStatus){
                                        echo __('All Sent', true);
                                    }

                                    else{
                                        echo $this->Infinitas->status($campaign['Campaign']['active'], $campaign['Campaign']['id']);
                                    }
                                }

                                echo $this->Locked->display($campaign);
                            ?>
                        </td>
                    </tr>
                <?php
            }
        ?>
    </table>
    <?php echo $this->Form->end(); ?>
</div>
<?php echo $this->element('pagination/admin/navigation'); ?>