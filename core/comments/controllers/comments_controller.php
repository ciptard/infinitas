<?php
	/**
	 * @brief CommentsController is used for the management of comments
	 *
	 * allowing admins to view, toggle and delete as needed.
	 *
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Comments.controllers
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.6a
	 *
	 * @author dogmatic69
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CommentsController extends CommentsAppController {
		/**
		 * the name of the controller
		 *
		 * @var sting
		 * @access public
		 */
		public $name = 'Comments';

		public function index(){
			$conditions = array(
				'Comment.active' => 1
			);

			if(isset($this->params['named']['Comment.class'])){
				$conditions['Comment.class'] = $this->params['named']['Comment.class'];
			}

			if(isset($this->params['named']['Comment.foreign_id'])){
				$conditions['Comment.foreign_id'] = $this->params['named']['Comment.foreign_id'];
			}

			$this->paginate = array(
				'conditions' => $conditions,
				'contain' => array(
					'CommentAttribute'
				)
			);

			$this->set('comments', $this->paginate());
		}

		public function admin_index() {
			$this->paginate = array(
				'fields' => array(
					'Comment.id',
					'Comment.class',
					'Comment.email',
					'Comment.user_id',
					'Comment.comment',
					'Comment.active',
					'Comment.status',
					'Comment.points',
					'Comment.foreign_id',
					'Comment.created',
				),
				'contain' => array(
					'CommentAttribute'
				),
				'order' => array(
					'Comment.active' => 'asc',
					'Comment.created' => 'desc',
				),
				'limit' => 20
			);

			$comments = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;

			$filterOptions['fields'] = array(
				'class' => $this->Comment->getUniqueClassList(),
				'email',
				'comment',
				'active' => Configure::read('CORE.active_options')
			);

			$this->set(compact('comments', 'filterOptions'));
		}

		public function admin_reply(){
			// @todo reply to the comment.
		}

		public function admin_commentPurge($class = null) {
			if (!$class) {
				$this->Session->setFlash(__('Nothing chosen to purge', true));
				$this->redirect($this->referer());
			}

			if (!Configure::read('Comments.purge')) {
				$this->Session->setFlash(__('Purge is disabled', true));
				$this->redirect($this->referer());
			}

			$ids = ClassRegistry::init('Comment.Comment')->find(
				'list',
				array(
					'fields' => array(
						'Comment.id',
						'Comment.id'
					),
					'conditions' => array(
						'Comment.class' => $class,
						'Comment.active' => 0,
						'Comment.created < ' => date('Y-m-d H:i:s', strtotime('-' . Configure::read('Comments.purge')))
					)
				)
			);

			if (empty($ids)) {
				$this->Session->setFlash(__('Nothing to purge', true));
				$this->redirect($this->referer());
			}

			$counter = 0;

			foreach($ids as $id) {
				if (ClassRegistry::init('Comment.Comment')->delete($id)) {
					$counter++;
				}
			}

			$this->Session->setFlash(sprintf(__('%s comments were purged.', true), $counter));
			$this->redirect($this->referer());
		}
	}