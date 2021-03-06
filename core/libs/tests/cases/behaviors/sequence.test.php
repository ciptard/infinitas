<?php
	class Item extends CakeTestModel {
		public $name = 'Item';
		public $actsAs = array('Libs.Sequence');
	}

	class GroupedItem extends CakeTestModel {
		public $name = 'GroupedItem';
		public $actsAs = array('Libs.Sequence' => array('group_fields' => 'group_field'));
	}

	class MultiGroupedItem extends CakeTestModel {
		public $name = 'MultiGroupedItem';
		public $actsAs = array('Libs.Sequence' => array('group_fields' => array('group_field_1', 'group_field_2')));
	}

	App::import('lib', 'Libs.test/AppBehaviorTest');

	class SequenceBehaviorNoGroupTestCase extends AppBehaviorTestCase {
		/**
		 * @brief Configuration for the test case
		 *
		 * Loading fixtures:
		 *
		 * List all the needed fixtures in the do part of the fixture array.
		 * In replace you can overwrite fixtures of other plugins by your own.
		 *
		 * 'fixtures' => array(
		 *		'do' => array(
		 *			'SomePlugin.SomeModel
		 *		),
		 *		'replace' => array(
		 *			'Core.User' => 'SomePlugin.User
		 *		)
		 * )
		 * @var array
		 */
		public $setup = array(
			'behavior' => 'Libs.Sequence',
			'models' => array(
				'Libs.Item',
				'Libs.GroupedItem',
				'Libs.MultiGroupedItem'
			)
		);

		public $tests = false;

		/**
		 * @breif start test
		 *
		 * reset some options on customized sequencing
		 * 
		 * @param string $method
		 */
		public function startTest($method) {
			parent::startTest($method);
			
			$this->Item->Behaviors->detach('Sequence');
			$this->Item->Behaviors->attach('Libs.Sequence', 'ordering');
			
			$this->GroupedItem->Behaviors->detach('Sequence');
			$this->GroupedItem->Behaviors->attach('Libs.Sequence', array('groupFields' => 'group_field'));

			$this->MultiGroupedItem->Behaviors->detach('Sequence');
			$this->MultiGroupedItem->Behaviors->attach('Libs.Sequence', array('groupFields' => array('group_field_1', 'group_field_2')));
		}

		/**
		 * @test getting the current order counts based on any conditions
		 */
		public function testGetHighestOrder() {
			/**
			 * ungrouped
			 */
			$this->assertEqual(5, $this->Item->getHighestOrder());
			$this->assertTrue($this->Item->delete(1));
			$this->assertEqual(4, $this->Item->getHighestOrder());
			$this->assertTrue($this->Item->save(array('Item' => array('name' => 'foo'))));
			$this->assertEqual(5, $this->Item->getHighestOrder());

			/**
			 * grouped
			 */
			$this->assertEqual(5, $this->GroupedItem->getHighestOrder(array('group_field' => 1)));
			$this->assertEqual(5, $this->GroupedItem->getHighestOrder(array('group_field' => 2)));
			$this->assertEqual(5, $this->GroupedItem->getHighestOrder(array('group_field' => 3)));
			
			$this->assertTrue($this->GroupedItem->delete(1));
			$this->assertTrue($this->GroupedItem->delete(6)); $this->assertTrue($this->GroupedItem->delete(7));
			$this->assertTrue($this->GroupedItem->delete(13)); $this->assertTrue($this->GroupedItem->delete(14)); $this->assertTrue($this->GroupedItem->delete(15));
			$this->assertTrue($this->GroupedItem->save(array('GroupedItem' => array('name' => 'foo', 'group_field' => 1))));
			$this->assertEqual(5, $this->GroupedItem->getHighestOrder(array('group_field' => 1)));
			$this->assertEqual(3, $this->GroupedItem->getHighestOrder(array('group_field' => 2)));
			$this->assertEqual(2, $this->GroupedItem->getHighestOrder(array('group_field' => 3)));

			/**
			 * multi grouped
			 */
			$this->assertEqual(5, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 1, 'group_field_2' => 1)));
			$this->assertEqual(5, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 1, 'group_field_2' => 2)));
			$this->assertEqual(5, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 2, 'group_field_2' => 1)));
			$this->assertEqual(5, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 2, 'group_field_2' => 2)));

			$this->assertTrue($this->MultiGroupedItem->delete(1));
			$this->assertTrue($this->MultiGroupedItem->save(array('MultiGroupedItem' => array('name' => 'foo', 'group_field_1' => 2, 'group_field_2' => 2))));

			$this->assertEqual(4, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 1, 'group_field_2' => 1)));
			$this->assertEqual(5, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 1, 'group_field_2' => 2)));
			$this->assertEqual(5, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 2, 'group_field_2' => 1)));
			$this->assertEqual(6, $this->MultiGroupedItem->getHighestOrder(array('group_field_1' => 2, 'group_field_2' => 2)));
		}

		/**
		 * @test delete rows
		 */
		public function testDelete() {
			/**
			 * deleting ungrouped rows
			 */
			$this->assertTrue($this->Item->delete(1));
			$expected = array(
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 1)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 2)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 3)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 4)));
			$this->assertEqual($expected, $this->Item->find('all'));

			$this->assertTrue($this->Item->delete(3));
			$expected = array(
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 1)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 2)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 3)));
			$this->assertEqual($expected, $this->Item->find('all'));
			
			$this->assertTrue($this->Item->delete(5));
			$expected = array(
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 1)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 2)));
			$this->assertEqual($expected, $this->Item->find('all'));

			/**
			 * group delete
			 */
			$results = $this->GroupedItem->find('all',
				array('conditions' => array('GroupedItem.group_field' => array(1, 2)),
				'order' => '`GroupedItem`.`group_field`, `GroupedItem`.`ordering`'));

			$expected = array(
				array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 5)),

				array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);

			$this->assertTrue($this->GroupedItem->delete(1));
			$results = $this->GroupedItem->find('all',
				array('conditions' => array('GroupedItem.group_field' => array(1, 2)),
				'order' => '`GroupedItem`.`group_field`, `GroupedItem`.`ordering`'));

			$expected = array(
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 4)),

				array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);

			/**
			 * delete multi grouped item
			 */
			$this->MultiGroupedItem->delete(1);
			$results = $this->MultiGroupedItem->find('all', array('conditions' => array('group_field_1' => 1, 'group_field_2' => array(1, 2)), 'order' => '`MultiGroupedItem`.`group_field_1`, `MultiGroupedItem`.`group_field_2`, `MultiGroupedItem`.`ordering`'));
			$expected = array(
				array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 6, 'name' => 'Group1 1 Group2 2 Item A', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 7, 'name' => 'Group1 1 Group2 2 Item B', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 8, 'name' => 'Group1 1 Group2 2 Item C', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 9, 'name' => 'Group1 1 Group2 2 Item D', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 10, 'name' => 'Group1 1 Group2 2 Item E', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);
		}

		/**
		 * @test insert rows
		 */
		public function testInsert() {	
			$this->Item->create();
			$this->assertTrue($this->Item->save(array('Item' => array('name' => 'Item F'))));
			$expected = array(
				array('Item' => array('id' => 1, 'name' => 'Item A', 'ordering' => 1)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 2)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 3)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 4)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 5)),
				array('Item' => array('id' => 6, 'name' => 'Item F', 'ordering' => 6)),
			);
			$this->assertEqual($expected, $this->Item->find('all'));

			$this->Item->create();
			$this->assertTrue($this->Item->save(array('Item' => array('name' => 'Item G', 'ordering' => '1'))));
			$expected = array(
				array('Item' => array('id' => 7, 'name' => 'Item G', 'ordering' => 1)),
				array('Item' => array('id' => 1, 'name' => 'Item A', 'ordering' => 2)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 3)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 4)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 5)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 6)),
				array('Item' => array('id' => 6, 'name' => 'Item F', 'ordering' => 7)),
			);
			$this->assertEqual($expected, $this->Item->find('all'));

			$this->Item->create();
			$this->assertTrue($this->Item->save(array('Item' => array('name' => 'Item H', 'ordering' => '3'))));
			$expected = array(
				array('Item' => array('id' => 7, 'name' => 'Item G', 'ordering' => 1)),
				array('Item' => array('id' => 1, 'name' => 'Item A', 'ordering' => 2)),
				array('Item' => array('id' => 8, 'name' => 'Item H', 'ordering' => 3)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 4)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 5)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 6)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 7)),
				array('Item' => array('id' => 6, 'name' => 'Item F', 'ordering' => 8)),
			);
			$this->assertEqual($expected, $this->Item->find('all'));

			$this->Item->create();
			$this->assertTrue($this->Item->save(array('Item' => array('name' => 'Item I', 'ordering' => '9'))));
			$expected = array(
				array('Item' => array('id' => 7, 'name' => 'Item G', 'ordering' => 1)),
				array('Item' => array('id' => 1, 'name' => 'Item A', 'ordering' => 2)),
				array('Item' => array('id' => 8, 'name' => 'Item H', 'ordering' => 3)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 4)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 5)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 6)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 7)),
				array('Item' => array('id' => 6, 'name' => 'Item F', 'ordering' => 8)),
				array('Item' => array('id' => 9, 'name' => 'Item I', 'ordering' => 9)),
			);
			$this->assertEqual($expected, $this->Item->find('all'));

			$this->Item->create();
			$this->assertTrue($this->Item->save(array('Item' => array('name' => 'Item J', 'ordering' => '20'))));
			$expected = array(
				array('Item' => array('id' => 7, 'name' => 'Item G', 'ordering' => 1)),
				array('Item' => array('id' => 1, 'name' => 'Item A', 'ordering' => 2)),
				array('Item' => array('id' => 8, 'name' => 'Item H', 'ordering' => 3)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 4)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 5)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 6)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 7)),
				array('Item' => array('id' => 6, 'name' => 'Item F', 'ordering' => 8)),
				array('Item' => array('id' => 9, 'name' => 'Item I', 'ordering' => 9)),
				array('Item' => array('id' => 10, 'name' => 'Item J', 'ordering' => 10)),
			);
			$this->assertEqual($expected, $this->Item->find('all'));

			/**
			 * test group insert
			 */
			$results = $this->GroupedItem->find('all', array('conditions' => array('GroupedItem.group_field' => '1')));
			$expected = array(
				array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 5)));
			$this->assertEqual($expected, $results);
			
			$this->GroupedItem->create();
			$this->assertTrue($this->GroupedItem->save(array('GroupedItem' => array('name' => 'Group 1 Item F', 'group_field' => '1', 'ordering' => '4'))));
			$results = $this->GroupedItem->find('all', array('conditions' => array('GroupedItem.group_field' => '1')));
			$expected = array(
				array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 16, 'name' => 'Group 1 Item F', 'group_field' => 1, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 5)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 6)));
			$this->assertEqual($expected, $results);
			
			return;

			/**
			 * check the other group is still ordered correctly
			 */
			$results = $this->GroupedItem->find('all', array('conditions' => array('GroupedItem.group_field' => '2')));
			$expected = array(
				array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);

			$this->GroupedItem->create();
			$this->assertTrue($this->GroupedItem->save(array('GroupedItem' => array('name' => 'Group 1 Item G', 'group_field' => '1'))));
			$results = $this->GroupedItem->find('all', array('conditions' => array('group_field' => '1')));
			$expected = array(
				array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 16, 'name' => 'Group 1 Item F', 'group_field' => 1, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 5)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 6)),
				array('GroupedItem' => array('id' => 17, 'name' => 'Group 1 Item G', 'group_field' => 1, 'ordering' => 7)));
			$this->assertEqual($expected, $results);
			
			/**
			 * no group set
			 */
			$this->GroupedItem->create();
			$this->assertTrue($this->GroupedItem->save(array('GroupedItem' => array('name' => 'Group Null Item A'))));
			$expected = array(
				array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 16, 'name' => 'Group 1 Item F', 'group_field' => 1, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 5)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 6)),
				array('GroupedItem' => array('id' => 17, 'name' => 'Group 1 Item G', 'group_field' => 1, 'ordering' => 7)));
			$this->assertEqual($expected, $this->GroupedItem->find('all', array('conditions' => array('group_field' => '1'))));

			$results = $this->GroupedItem->find('all', array('conditions' => array('group_field' => null)));
			$expected = array(array('GroupedItem' => array('id' => 18, 'name' => 'Group Null Item A', 'group_field' => null, 'ordering' => 1)));
			$this->assertEqual($expected, $results);
			
			/**
			 * multi group insert
			 */
			$this->MultiGroupedItem->create();
			$this->MultiGroupedItem->save(array('MultiGroupedItem' => array('name' => 'Group1 1 Group2 2 Item F', 'group_field_1' => '1', 'group_field_2' => '2', 'ordering' => '3')));
			$results = $this->MultiGroupedItem->find('all', array('conditions' => array('group_field_1' => 1, 'group_field_2' => 2)));
			$expected = array(
				array('MultiGroupedItem' => array('id' => 6, 'name' => 'Group1 1 Group2 2 Item A', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 7, 'name' => 'Group1 1 Group2 2 Item B', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 126, 'name' => 'Group1 1 Group2 2 Item F', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 8, 'name' => 'Group1 1 Group2 2 Item C', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 9, 'name' => 'Group1 1 Group2 2 Item D', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 5)),
				array('MultiGroupedItem' => array('id' => 10, 'name' => 'Group1 1 Group2 2 Item E', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 6))
			);
			$this->assertEqual($expected, $results);
			
			/**
			 * no ordering set
			 */
			$this->MultiGroupedItem->create();
			$this->MultiGroupedItem->save(array('MultiGroupedItem' => array('name' => 'Group1 1 Group2 2 Item G', 'group_field_1' => '1', 'group_field_2' => '2')));
			$results = $this->MultiGroupedItem->find('all', array('conditions' => array('group_field_1' => 1, 'group_field_2' => 2)));
			$expected = array(
				array('MultiGroupedItem' => array('id' => 6, 'name' => 'Group1 1 Group2 2 Item A', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 7, 'name' => 'Group1 1 Group2 2 Item B', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 126, 'name' => 'Group1 1 Group2 2 Item F', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 8, 'name' => 'Group1 1 Group2 2 Item C', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 9, 'name' => 'Group1 1 Group2 2 Item D', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 5)),
				array('MultiGroupedItem' => array('id' => 10, 'name' => 'Group1 1 Group2 2 Item E', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 6)),
				array('MultiGroupedItem' => array('id' => 127, 'name' => 'Group1 1 Group2 2 Item G', 'group_field_1' => 1, 'group_field_2' => 2, 'ordering' => 7)),
			);
			$this->assertEqual($expected, $results);
			
			/**
			 * only setting one of the groups 
			 */
			$this->assertEqual(27, $this->MultiGroupedItem->find('count', array('conditions' => array('group_field_1' => 1))));
			$this->MultiGroupedItem->create();
			$this->assertTrue($this->MultiGroupedItem->save(array('MultiGroupedItem' => array('name' => 'Group1 1 Group2 Null Item A', 'group_field_1' => '1'))));	
			$this->assertEqual(28, $this->MultiGroupedItem->find('count', array('conditions' => array('group_field_1' => 1))));
			
			$expected = array(array('MultiGroupedItem' => array('id' => 128, 'name' => 'Group1 1 Group2 Null Item A', 'group_field_1' => 1, 'group_field_2' => null, 'ordering' => 1)));
			$this->assertEqual($expected, $this->MultiGroupedItem->find('all', array('conditions' => array('MultiGroupedItem.group_field_2 IS NULL'))));
		}

		/**
		 * @test editing existing rows
		 */
		public function testEdit() {
			$expected = array(
				array('Item' => array('id' => 1, 'name' => 'Item A', 'ordering' => 1)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 2)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 3)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 4)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));
			
			$this->assertTrue($this->Item->save(array('Item' => array('id' => '1', 'name' => 'Item A - edit'))));
			$expected = array(
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 1)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 2)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 3)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 4)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));

			$this->assertTrue($this->Item->save(array('Item' => array('id' => '1', 'ordering' => '3'))));
			$expected = array(
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 1)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 2)),
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 3)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 4)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));
			return;
			
			$this->assertTrue($this->Item->save(array('Item' => array('id' => '1', 'ordering' => '5'))));
			$expected = array(
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 1)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 2)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 3)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 4)),
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));

			$this->assertTrue($this->Item->save(array('Item' => array('id' => '4', 'ordering' => '5'))));
			$expected = array(
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 1)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 2)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 3)),
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 4)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));
		
			$this->assertTrue($this->Item->save(array('Item' => array('id' => '5', 'ordering' => '1'))));
			$expected = array(
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 1)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 2)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 3)),
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 4)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));

			$this->assertTrue($this->Item->save(array('Item' => array('id' => '1', 'ordering' => '5'))));
			$expected = array(
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 1)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 2)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 3)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 4)),
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));

			$this->assertTrue($this->Item->save(array('Item' => array('id' => '4', 'ordering' => '3'))));
			$expected = array(
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 1)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 2)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 3)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 4)),
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));

			$this->assertTrue($this->Item->save(array('Item' => array('id' => '1', 'ordering' => '1'))));
			$expected = array(
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 1)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 2)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 3)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 4)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));

			$this->assertTrue($this->Item->save(array('Item' => array('id' => '2', 'ordering' => '10'))));
			$expected = array(
				array('Item' => array('id' => 1, 'name' => 'Item A - edit', 'ordering' => 1)),
				array('Item' => array('id' => 5, 'name' => 'Item E', 'ordering' => 2)),
				array('Item' => array('id' => 4, 'name' => 'Item D', 'ordering' => 3)),
				array('Item' => array('id' => 3, 'name' => 'Item C', 'ordering' => 4)),
				array('Item' => array('id' => 2, 'name' => 'Item B', 'ordering' => 5)));
			$this->assertEqual($expected, $this->Item->find('all', array('order' => array('Item.ordering'))));


			/**
			 * change group with specific order
			 */
			$this->GroupedItem->save(array('GroupedItem' => array('id' => '3', 'group_field' => '2', 'ordering' => '3')));
			$results = $this->GroupedItem->find('all', array('conditions' => array('group_field' => array(1, 2)), 'order' => '`GroupedItem`.`group_field`, `GroupedItem`.`ordering`'));
			$expected = array(
				array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 4)),
				
				array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 2, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 2, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'ordering' => 5)),
				array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'ordering' => 6)));
			$this->assertEqual($expected, $results);

			/**
			 * changin the group
			 */
			$this->GroupedItem->save(array('GroupedItem' => array('id' => '8', 'group_field' => '1')));
			$results = $this->GroupedItem->find('all',array(
				'conditions' => array('group_field' => array(1, 2)),
				'order' => '`GroupedItem`.`group_field`, `GroupedItem`.`ordering`'));
			$expected = array(
				array('GroupedItem' => array('id' => 1, 'name' => 'Group 1 Item A', 'group_field' => 1, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 2, 'name' => 'Group 1 Item B', 'group_field' => 1, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 4, 'name' => 'Group 1 Item D', 'group_field' => 1, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 5, 'name' => 'Group 1 Item E', 'group_field' => 1, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 8, 'name' => 'Group 2 Item C', 'group_field' => 1, 'ordering' => 5)),
				
				array('GroupedItem' => array('id' => 6, 'name' => 'Group 2 Item A', 'group_field' => 2, 'ordering' => 1)),
				array('GroupedItem' => array('id' => 7, 'name' => 'Group 2 Item B', 'group_field' => 2, 'ordering' => 2)),
				array('GroupedItem' => array('id' => 3, 'name' => 'Group 1 Item C', 'group_field' => 2, 'ordering' => 3)),
				array('GroupedItem' => array('id' => 9, 'name' => 'Group 2 Item D', 'group_field' => 2, 'ordering' => 4)),
				array('GroupedItem' => array('id' => 10, 'name' => 'Group 2 Item E', 'group_field' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);
			
			
			$results = $this->MultiGroupedItem->find(
				'all', array('conditions' => array('or' => array(
						array('group_field_1' => 1, 'group_field_2' => 1),
						array('group_field_1' => 2, 'group_field_2' => 2))),
					'order' => '`MultiGroupedItem`.`group_field_1`, `MultiGroupedItem`.`group_field_2`, `MultiGroupedItem`.`ordering`'));
			$expected = array(
				array('MultiGroupedItem' => array('id' => 1, 'name' => 'Group1 1 Group2 1 Item A', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 5)),

				array('MultiGroupedItem' => array('id' => 31, 'name' => 'Group1 2 Group2 2 Item A', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 32, 'name' => 'Group1 2 Group2 2 Item B', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 33, 'name' => 'Group1 2 Group2 2 Item C', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 34, 'name' => 'Group1 2 Group2 2 Item D', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 35, 'name' => 'Group1 2 Group2 2 Item E', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);
			
			$this->MultiGroupedItem->save(array('MultiGroupedItem' => array('id' => '3', 'group_field_1' => '2', 'group_field_2' => '2')));
			$results = $this->MultiGroupedItem->find(
				'all', array('conditions' => array('or' => array(
						array('group_field_1' => 1, 'group_field_2' => 1),
						array('group_field_1' => 2, 'group_field_2' => 2))),
					'order' => '`MultiGroupedItem`.`group_field_1`, `MultiGroupedItem`.`group_field_2`, `MultiGroupedItem`.`ordering`'));
			$expected = array(
				array('MultiGroupedItem' => array('id' => 1, 'name' => 'Group1 1 Group2 1 Item A', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 4)),

				array('MultiGroupedItem' => array('id' => 31, 'name' => 'Group1 2 Group2 2 Item A', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 32, 'name' => 'Group1 2 Group2 2 Item B', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 33, 'name' => 'Group1 2 Group2 2 Item C', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 34, 'name' => 'Group1 2 Group2 2 Item D', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 35, 'name' => 'Group1 2 Group2 2 Item E', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 5)),
				array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 6)));
			$this->assertEqual($expected, $results);
			
			/**
			 * move groups with new order specified
			 */
			$this->MultiGroupedItem->save(array('MultiGroupedItem' => array('id' => '33', 'group_field_1' => '1', 'group_field_2' => '1', 'ordering' => 3)));
			$results = $this->MultiGroupedItem->find(
				'all', array('conditions' => array('or' => array(
						array('group_field_1' => 1, 'group_field_2' => 1),
						array('group_field_1' => 2, 'group_field_2' => 2))),
					'order' => '`MultiGroupedItem`.`group_field_1`, `MultiGroupedItem`.`group_field_2`, `MultiGroupedItem`.`ordering`'));
			$expected = array(
				array('MultiGroupedItem' => array('id' => 1, 'name' => 'Group1 1 Group2 1 Item A', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 33, 'name' => 'Group1 2 Group2 2 Item C', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 5)),

				array('MultiGroupedItem' => array('id' => 31, 'name' => 'Group1 2 Group2 2 Item A', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 32, 'name' => 'Group1 2 Group2 2 Item B', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 34, 'name' => 'Group1 2 Group2 2 Item D', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 35, 'name' => 'Group1 2 Group2 2 Item E', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);
			
			/**
			 * no changes, make sure everything stays the same
			 */
			$this->MultiGroupedItem->save(array('MultiGroupedItem' => array('id' => '33', 'group_field_1' => '1', 'group_field_2' => '1', 'ordering' => 3)));
			$results = $this->MultiGroupedItem->find(
				'all', array('conditions' => array('or' => array(
						array('group_field_1' => 1, 'group_field_2' => 1),
						array('group_field_1' => 2, 'group_field_2' => 2))),
					'order' => '`MultiGroupedItem`.`group_field_1`, `MultiGroupedItem`.`group_field_2`, `MultiGroupedItem`.`ordering`'));
			$expected = array(
				array('MultiGroupedItem' => array('id' => 1, 'name' => 'Group1 1 Group2 1 Item A', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 2, 'name' => 'Group1 1 Group2 1 Item B', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 33, 'name' => 'Group1 2 Group2 2 Item C', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 4, 'name' => 'Group1 1 Group2 1 Item D', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 5, 'name' => 'Group1 1 Group2 1 Item E', 'group_field_1' => 1, 'group_field_2' => 1, 'ordering' => 5)),

				array('MultiGroupedItem' => array('id' => 31, 'name' => 'Group1 2 Group2 2 Item A', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 1)),
				array('MultiGroupedItem' => array('id' => 32, 'name' => 'Group1 2 Group2 2 Item B', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 2)),
				array('MultiGroupedItem' => array('id' => 34, 'name' => 'Group1 2 Group2 2 Item D', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 3)),
				array('MultiGroupedItem' => array('id' => 35, 'name' => 'Group1 2 Group2 2 Item E', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 4)),
				array('MultiGroupedItem' => array('id' => 3, 'name' => 'Group1 1 Group2 1 Item C', 'group_field_1' => 2, 'group_field_2' => 2, 'ordering' => 5)));
			$this->assertEqual($expected, $results);
		}
	}