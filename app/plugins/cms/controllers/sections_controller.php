<?php
    class SectionsController extends CmsAppController
    {
        var $name = 'Sections';

        function index()
        {
            $this->Section->recursive = 0;
            $this->set( 'sections', $this->paginate() );
        }

        function view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Invalid section', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
            $this->set( 'section', $this->Section->read( null, $id ) );
        }

        function admin_dashboard()
        {

        }

        function admin_index()
        {
            $this->Section->recursive = 0;
            $this->set( 'sections', $this->paginate() );
        }

        function admin_view( $id = null )
        {
            if ( !$id )
            {
                $this->Session->setFlash( __( 'Invalid section', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
            $this->set( 'section', $this->Section->read( null, $id ) );
        }

        function admin_add()
        {
            if ( !empty( $this->data ) )
            {
                $this->Section->create();
                if ( $this->Section->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The section has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The section could not be saved. Please, try again.', true ) );
                }
            }

            $groups = $this->Section->Group->find( 'list' );
            $this->set( compact( 'groups' ) );
        }

        function admin_edit( $id = null )
        {
            if ( !$id && empty( $this->data ) )
            {
                $this->Session->setFlash( __( 'Invalid section', true ) );
                $this->redirect( array( 'action' => 'index' ) );
            }
            if ( !empty( $this->data ) )
            {
                if ( $this->Section->save( $this->data ) )
                {
                    $this->Session->setFlash( __( 'The section has been saved', true ) );
                    $this->redirect( array( 'action' => 'index' ) );
                }
                else
                {
                    $this->Session->setFlash( __( 'The section could not be saved. Please, try again.', true ) );
                }
            }
            if ( empty( $this->data ) )
            {
                $this->data = $this->Section->lock( null, $id );
                if ( $this->data === false )
                {
                    $this->Session->setFlash( __( 'The section is currently locked', true ) );
                    $this->redirect( $this->referer() );
                }
            }

            $groups = $this->Section->Group->find( 'list' );
            $this->set( compact( 'groups' ) );
        }
    }
?>