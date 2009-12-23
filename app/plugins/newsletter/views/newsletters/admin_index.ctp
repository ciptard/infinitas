<?php
    echo $this->Letter->adminIndexHead( $this, $paginator );
?>
<div class="table">
    <?php echo $this->Letter->adminTableHeadImages(); ?>
    <?php echo $this->Form->create( 'Newsletter', array( 'url' => array( 'controller' => 'newsletters', 'action' => 'mass', 'admin' => 'true' ) ) ); ?>
    <table class="listing" cellpadding="0" cellspacing="0">
        <tr>
            <th class="first" style="width:10px;"><?php echo $this->Form->checkbox( 'all' ); ?></th>
            <th style="width:100px;"><?php echo $paginator->sort( 'Campaign', 'Campaign.name' ); ?></th>
            <th style="width:150px;"><?php echo $paginator->sort( 'subject' ); ?></th>
            <th style="width:100px;"><?php echo $paginator->sort( 'from' ); ?></th>
            <th style="width:100px;"><?php echo $paginator->sort( 'reply_to' ); ?></th>
            <th style="width:50px;"><?php echo $paginator->sort( 'status' ); ?></th>
            <th style="width:50px;"><?php echo $paginator->sort( 'sent' ); ?></th>
            <th class="last actions"><?php echo __( 'Actions', true ); ?></th>
        </tr>
        <?php
            $i = 0;
            foreach( $newsletters as $newsletter )
            {
                ?>
                    <tr class="<?php echo $this->Letter->rowClass( $i ); ?>">
                        <td><?php echo $this->Form->checkbox( $newsletter['Newsletter']['id'] ); ?>&nbsp;</td>
                        <td>
                            <?php
                                echo $html->link(
                                    $newsletter['Campaign']['name'],
                                    array(
                                        'controller' => 'campaign',
                                        'action' => 'view',
                                        $newsletter['Newsletter']['campaign_id']
                                    )
                                );
                            ?>&nbsp;
                        </td>
                        <td><?php echo $newsletter['Newsletter']['subject'] ?>&nbsp;</td>
                        <td><?php echo $newsletter['Newsletter']['from'] ?>&nbsp;</td>
                        <td><?php echo $newsletter['Newsletter']['reply_to'] ?>&nbsp;</td>
                        <td>
                            <?php
                                if ( $newsletter['Newsletter']['sent'] )
                                {
                                    echo $this->Html->link(
                                        $this->Html->image(
                                            'core/icons/actions/16/reports.png'
                                        ),
                                        array(
                                            'action' => 'report',
                                            $newsletter['Newsletter']['id']
                                        ),
                                        array(
                                            'title' => __( 'Sending complete. See the report.', true ),
                                            'alt' => __( 'Done', true ),
                                            'escape' => false
                                        )
                                    );
                                }
                                else
                                {
                                    echo $this->Status->toggle( $newsletter['Newsletter']['active'], $newsletter['Newsletter']['id'] );
                                }
                            ?>
                        </td>
                        <td>
                            <?php
                                if ( $newsletter['Newsletter']['active'] && !$newsletter['Newsletter']['sent'] )
                                {
                                    echo $this->Html->image(
                                        'core/icons/actions/16/update.png',
                                        array(
                                            'alt' => __( 'In Progress', true ),
                                            'title' => __( 'Busy sending', true )
                                        )
                                    );
                                }
                                else
                                {
                                    echo $this->Letter->toggle( $newsletter['Newsletter']['id'], $newsletter['Newsletter']['sent'] );
                                }
                            ?>
                        </td>
                        <td>
                            <?php

                                echo $this->Html->link( 'to', array( 'action' => 'sentTo', $newsletter['Newsletter']['id'] ) ), ' ',
                                    $this->Html->link( 'view', array( 'action' => 'view', $newsletter['Newsletter']['id'] ) ), ' ';

                                if ( !$newsletter['Newsletter']['sent'] )
                                {
                                    echo $this->Html->link( 'edit', array( 'action' => 'edit', $newsletter['Newsletter']['id'] ) ), ' ',
                                        $this->Html->link( 'delete', array( 'action' => 'delete', $newsletter['Newsletter']['id'] ) );
                                }
                            ?>
                        </td>
                    </tr>
                <?
                $i++;
            }
        ?>
    </table>
    <?php
        echo $this->Form->button( __( 'Delete', true ), array( 'value' => 'delete', 'name' => 'delete' ) );
        echo $this->Form->button( __( 'Toggle', true ), array( 'value' => 'toggle' ) );
        echo $this->Form->end();
    ?>
</div>
<?php echo $this->element( 'pagination/navigation' ); ?>