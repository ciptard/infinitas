<?php
	/**
	 * CategoriesController for the management and display of categories and
	 * related data.
	 * 
	 * @filesource
	 * @copyright Copyright (c) 2009 Carl Sutton ( dogmatic69 )
	 * @link http://infinitas-cms.org
	 * @package Infinitas.Categories.controllers
	 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
	 * @since 0.7a
	 *
	 * Licensed under The MIT License
	 * Redistributions of files must retain the above copyright notice.
	 */

	class CategoriesController extends CategoriesAppController {
		public $name = 'Categories';

		public function index() {
			if(isset($this->params['category'])){
				$this->paginate['Category']['conditions']['Category.slug'] = $this->params['category'];
			}

			$categories = $this->paginate();
			// redirect if there is only one category.
			if (count($categories) == 1 && Configure::read('Cms.auto_redirect')) {
				$this->redirect(
					array(
						'controller' => 'categories',
						'action' => 'view',
						$categories[0]['Category']['id']
					)
				);
			}

			$this->set('categories', $categories);
		}

		public function view() {
			if(isset($this->params['category'])){
				$conditions['Category.slug'] = $this->params['category'];
			}

			$category = $this->Category->find(
				'first',
				array(
					'conditions' => $conditions
				)
			);

			// redirect if there is only one content item.
			if ((isset($category['Content']) && count($category['Content']) == 1) && Configure::read('Cms.auto_redirect')) {
				$this->redirect(
					array(
						'controller' => 'contents',
						'action' => 'view',
						$category['Content'][0]['id']
					)
				);
			}
			
			else if(empty($category)){
				$this->Session->setFlash(__('Invalid category', true));
				$this->redirect($this->referer());
			}

			$this->set('title_for_layout', $category['Category']['title']);
			$this->set('category', $category);
		}

		public function admin_index() {
			$categories = $this->paginate(null, $this->Filter->filter);

			$filterOptions = $this->Filter->filterOptions;
			$filterOptions['fields'] = array(
				'title',
				'parent_id' => array(null => __('All', true), 0 => __('Top Level Categories', true)) + $this->Category->generatetreelist(),
				'group_id' => array(null => __('Public', true)) + $this->Category->Group->find('list'),
				'active' => (array)Configure::read('CORE.active_options')
			);
			$this->set(compact('filterOptions','categories'));
		}

		public function admin_view($id = null) {
			if (!$id) {
				$this->Infinitas->noticeInvalidRecord();
			}
			$this->set('category', $this->Category->read(null, $id));
		}

		public function admin_add() {
			parent::admin_add();

			$parents = array(__('Top Level Category', true)) + $this->Category->generatetreelist();
			$groups = $this->Category->Group->find('list');
			$this->set(compact('parents', 'groups'));
		}

		public function admin_edit($id = null) {
			parent::admin_edit($id);

			$parents = array(__('Top Level Category', true)) + $this->Category->generatetreelist();
			$groups = $this->Category->Group->find('list');
			$this->set(compact('parents', 'groups'));
		}

		public function admin_delete($id = null) {
			if (!$id) {
				$this->Infinitas->noticeInvalidRecord();
			}

			$count = $this->Category->find('count', array('conditions' => array('Category.parent_id' => $id)));
			if ($count > 0) {
				$this->notice(
					sprintf(__('That %s has sub-categories', true), $this->prettyModelName),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}

			$category = $this->Category->read(null, $id);

			if (!empty($category['Content'])) {
				$this->notice(
					sprintf(__('That %s has content items, remove them before continuing', true), $this->prettyModelName),
					array(
						'level' => 'warning',
						'redirect' => true
					)
				);
			}

			return parent::admin_delete($id);
		}
	}