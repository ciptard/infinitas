<?php
/* NewsletterNewsletter Fixture generated on: 2010-08-17 14:08:14 : 1282055174 */
class NewsletterFixture extends CakeTestFixture {
	var $name = 'Newsletter';

	var $table = 'newsletter_newsletters';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
		'campaign_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'key' => 'index'),
		'template_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'from' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'reply_to' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 150),
		'subject' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'html' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'text' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'active' => array('type' => 'boolean', 'null' => false, 'default' => NULL),
		'sent' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'sends' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'last_sent' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'created_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'modified_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1), 'campaign_id' => array('column' => 'campaign_id', 'unique' => 0)),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 7,
			'campaign_id' => 3,
			'template_id' => 0,
			'from' => 'dogmatic69@gmail.com',
			'reply_to' => 'dogmatic69@gmail.com',
			'subject' => 'asdf',
			'html' => '<p>asd</p>',
			'text' => '<p>asd</p>',
			'active' => 0,
			'sent' => 1,
			'views' => 0,
			'sends' => 0,
			'last_sent' => '0000-00-00 00:00:00',
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2010-01-04 03:14:15',
			'modified' => '2010-01-04 03:14:15',
			'created_by' => 0,
			'modified_by' => 0
		),
		array(
			'id' => 9,
			'campaign_id' => 3,
			'template_id' => 0,
			'from' => 'dogmatic69@gmail.com',
			'reply_to' => 'dogmatic69@gmail.com',
			'subject' => 'asdf- copy ( 2010-01-04 )',
			'html' => '<p>asd</p>',
			'text' => '<p>asd</p>',
			'active' => 0,
			'sent' => 1,
			'views' => 0,
			'sends' => 0,
			'last_sent' => '0000-00-00 00:00:00',
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2010-01-04 03:14:15',
			'modified' => '2010-01-04 03:14:15',
			'created_by' => 0,
			'modified_by' => 0
		),
		array(
			'id' => 10,
			'campaign_id' => 6,
			'template_id' => 0,
			'from' => 'gsdfgd@dssd.com',
			'reply_to' => 'gsdfgd@dssd.com',
			'subject' => 'dsfgsdf',
			'html' => '<p>dfgdsfgsd</p>',
			'text' => '<p>sdfgdsfsfsfsfsfsf</p>',
			'active' => 0,
			'sent' => 0,
			'views' => 0,
			'sends' => 0,
			'last_sent' => '0000-00-00 00:00:00',
			'locked' => 0,
			'locked_by' => 0,
			'locked_since' => '0000-00-00 00:00:00',
			'created' => '2010-01-12 14:19:31',
			'modified' => '2010-01-12 14:19:31',
			'created_by' => 0,
			'modified_by' => 0
		),
	);
}
?>