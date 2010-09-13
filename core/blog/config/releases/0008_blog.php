<?php
class R4c8e68c07dc04f0cb89f38ba6318cd70 extends CakeRelease {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = 'Migration for Blog version 0.8';

/**
 * Plugin name
 *
 * @var string
 * @access public
 */
	public $plugin = 'Blog';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'posts' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
					'slug' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
					'body' => array('type' => 'text', 'null' => true, 'default' => NULL),
					'comment_count' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'views' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'rating' => array('type' => 'float', 'null' => false, 'default' => '0'),
					'rating_count' => array('type' => 'integer', 'null' => false, 'default' => '0'),
					'parent_id' => array('type' => 'integer', 'null' => true, 'default' => '0'),
					'ordering' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'locked' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'locked_by' => array('type' => 'integer', 'null' => true, 'default' => NULL),
					'locked_since' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'deleted' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'deleted_date' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'tags' => array('type' => 'string', 'null' => false, 'default' => NULL),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'category_id' => array('column' => 'category_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'posts'
			),
		),
	);

/**
 * Fixtures for data
 *
 * @var array $fixtures
 * @access public
 */
	public $fixtures = array(
	'core' => array(
		'Blog.Post' => array(
			array(
				'id' => 13,
				'title' => 'What happend to 0.7',
				'slug' => 'what-happend-to-0-7',
				'body' => '<p>\r\n	That is right. There was a 0.7alpha and it was cool. But we were so focused on coding nobody bothered to make a post about it. Below is a link to what was fixed and changed (mostly complete list)</p>\r\n<p>\r\n	<a href=\"http://github.com/infinitas/infinitas/blob/v0.7alpha/versions.txt\" target=\"_blank\">What changed<br />\r\n	</a></p>\r\n<p>\r\n	Infinitas 0.7alpha was cool, but not even close to what 0.8 has on offer. and yes 0.8alpha is just about done.</p>\r\n',
				'comment_count' => 0,
				'active' => 1,
				'views' => 57,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 7,
				'locked' => 1,
				'locked_by' => 1,
				'locked_since' => '2010-09-13 13:48:56',
				'created' => '2010-05-21 02:02:25',
				'modified' => '2010-05-21 02:02:25',
				'deleted' => 0,
				'deleted_date' => NULL,
				'category_id' => 1,
				'tags' => ''
			),
			array(
				'id' => 12,
				'title' => 'Limiting access to registered users',
				'slug' => 'limiting-access-to-registered-users',
				'body' => '<p>\r\n	So you have some content that everyone can see, and now you want to create some content that is only available to registered users? This is simple to achive. Any time you are creating content or editing content on your Infinitas powered site, if you see a dropdown with a list of the groups you can limit access. If you only want it available to registered users just select that group and save. Only want it for admins? Select admin from the list of groups.</p>\r\n<p>\r\n	The defaul is &quot;Public&quot; which means that anyone that accesses your site will be able to see the content (when its active). Not every thing has this option, but you will see it all around the site. Categories, posts and CMS pages etc will all allow you to manage who can access what on the site.</p>\r\n',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 6,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-05-21 01:54:48',
				'modified' => '2010-05-21 01:54:48',
				'deleted' => 0,
				'deleted_date' => NULL,
				'category_id' => 11,
				'tags' => ''
			),
			array(
				'id' => 11,
				'title' => ' I want to change the order of records',
				'slug' => 'i-want-to-change-the-order-of-records',
				'body' => '<p>\r\n	Infinitas has two different ways of ordering records. The first one is for tree data like categories and the second is a normal number sequence. There are some records that can not be modified, like the blog as they are hard coded to always be in date order, newest to oldest.</p>\r\n<p>\r\n	<br />\r\n	The main difference between tree data ordering and sequentialy ordered records it that tree data items will only beable to order items within the same area of the tree. Have a look at the following structure for more clarity:</p>\r\n<ul>\r\n	<li>\r\n		Level One\r\n		<ul>\r\n			<li>\r\n				Sub 1\r\n				<ul>\r\n					<li>\r\n						1.1\r\n						<ul>\r\n							<li>\r\n								1.1.1</li>\r\n							<li>\r\n								1.1.2</li>\r\n						</ul>\r\n					</li>\r\n					<li>\r\n						1.2</li>\r\n					<li>\r\n						1.3</li>\r\n				</ul>\r\n			</li>\r\n			<li>\r\n				Sub 2</li>\r\n			<li>\r\n				Sub 3</li>\r\n		</ul>\r\n	</li>\r\n	<li>\r\n		Level Two\r\n		<ul>\r\n			<li>\r\n				Sub a\r\n				<ul>\r\n					<li>\r\n						a1</li>\r\n					<li>\r\n						a2\r\n						<ul>\r\n							<li>\r\n								a2.1</li>\r\n						</ul>\r\n					</li>\r\n				</ul>\r\n			</li>\r\n			<li>\r\n				Sub b</li>\r\n			<li>\r\n				Sub c</li>\r\n		</ul>\r\n	</li>\r\n	<li>\r\n		Level Three</li>\r\n</ul>\r\n<p>\r\n	You cannot for example move &quot;Sub c&quot; above &quot;Sub 2&quot; as they are not in the same part of the tree, to do that you would need to edit &quot;Sub c&quot; and move it to another part of the tree. But you can move &quot;Sub c&quot; above &quot;Sub a&quot; if you like.</p>\r\n<p>\r\n	The sequentialy ordered records are simmilar except there is not realy a tree structure, they are tipicaly flat lists. See below for an example.</p>\r\n<ul>\r\n	<li>\r\n		Category 1\r\n		<ul>\r\n			<li>\r\n				Item 1</li>\r\n			<li>\r\n				Item 2</li>\r\n			<li>\r\n				Item 3</li>\r\n			<li>\r\n				Item 4</li>\r\n			<li>\r\n				Item 5</li>\r\n		</ul>\r\n	</li>\r\n	<li>\r\n		Category 2\r\n		<ul>\r\n			<li>\r\n				Item 1</li>\r\n			<li>\r\n				Item 2</li>\r\n			<li>\r\n				Item 3</li>\r\n			<li>\r\n				Item 4</li>\r\n			<li>\r\n				Item 5</li>\r\n		</ul>\r\n	</li>\r\n</ul>\r\n<p>\r\n	Again you can not move items in category 1 under items in category 2, but that is where the similaries end. Unlike Tree structures, you can not move the order of the Categories in this list. they are fixed, you can only move the child items. Also you should note the structure is much simpler. You will not often have nested items like the tree. All the items can only move up and down inside the group they are in.</p>\r\n',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 5,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-05-21 01:47:07',
				'modified' => '2010-05-21 01:47:07',
				'deleted' => 0,
				'deleted_date' => NULL,
				'category_id' => 11,
				'tags' => ''
			),
			array(
				'id' => 10,
				'title' => 'Why cant I edit some record',
				'slug' => 'why-cant-i-edit-some-record',
				'body' => '<p>\r\n	Normaly the reason that you will not be able to edit a record is because it is locked. Another reason is because you do not have access to that record. To check if the record is locked simply check on the index page for the section of the site you are working on and see if there is a locked icon. If there is one, you need to find out from the person that locked it (hover over the lock) and see if they are done.</p>\r\n<p>\r\n	When a user edits a record in Infinitas it gets locked. This stops people fom editing the same record and saving over the changes. It is all controlled automaticaly and when users save and cancel properly (not using the browsers back and forward buttons) it will not give you any problems.</p>\r\n<p>\r\n	If the record was locked long ago it may be safe to unlock it, you can do this by navigating to Site-&gt;Records-&gt;Global Unlock, you will see a list of all the records that are locked there and have the option to unlock them. If there is someone else that is busy with the record, just give them a bit of time to finnish what they are doing and when they are done it will become unlocked.</p>\r\n<p>\r\n	There are plans in future versions to email users waiting to edit a record once it become available.</p>\r\n<p>\r\n	If this is not the case, you do not have access to the record you are trying to access. To fix this issue speak to the site admin and ask for access or pass the work to someone else.</p>\r\n',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 4,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-05-21 01:34:24',
				'modified' => '2010-05-21 01:34:24',
				'deleted' => 0,
				'deleted_date' => NULL,
				'category_id' => 11,
				'tags' => ''
			),
			array(
				'id' => 9,
				'title' => 'What happens when you delete something',
				'slug' => 'what-happens-when-you-delete-something',
				'body' => '<p>\r\n	Infinitas for the most part does not delete things directly. The are moved to a &quot;trash can&quot; of sorts, just like when you delete files or folders on your computer. There are times when they are deleted directly, you will just need to see what the plugin you are using says about that.</p>\r\n<p>\r\n	When you delete something and it is moved to the trash you will no longer see it in the admin section and users will no longer be able to see it on the frontend... regardles of its status befor it was deleted. If you have deleted something and would like it back for some reason (maybe you deleted the wrong thing by accident) just navigate to Site-&gt;Records-&gt;Trash. There you will be presented with everything that is in the trash. Select the item you want to restore and click &quot;Restore&quot; near the top of the page.</p>\r\n<p>\r\n	<br />\r\n	Deleting something permanently (cleaning up to make space) follow the directions for restoring something, but insted of clicking Restore, click &quot;Delete&quot;. You will be given a confirmation dialogue. Remeber once you delete something from the trash it is gone... There is no getting it back, so be careful.</p>\r\n',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 3,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-05-21 01:25:13',
				'modified' => '2010-05-21 01:25:13',
				'deleted' => 0,
				'deleted_date' => NULL,
				'category_id' => 11,
				'tags' => ''
			),
			array(
				'id' => 8,
				'title' => 'Creating an introduction for your blog post',
				'slug' => 'creating-an-introduction-for-your-blog-post',
				'body' => '<p>\r\n	The introduction to your blog post is generated dynamicaly from the main body. There is nothing special you need to do. In future versions we will come up with something that is a little bit more cleaver that extracts text out of the body based on keywords from the category and the blog post its self.</p>\r\n<div firebugversion=\"1.5.4\" id=\"_firebugConsole\" style=\"display: none;\">\r\n	&nbsp;</div>',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 2,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-05-21 01:15:51',
				'modified' => '2010-05-21 01:15:51',
				'deleted' => 0,
				'deleted_date' => NULL,
				'category_id' => 10,
				'tags' => ''
			),
			array(
				'id' => 7,
				'title' => 'Creating multi-page blog posts part 3',
				'slug' => 'creating-multi-page-blog-posts-part-3',
				'body' => '<p>\r\n	To create your multi-page blog post is simple, just create the first page like any other blog post. Once that is done, add a new page. Before you save it select the parent from the dropdown. Hit save and you are done. You now have a blog post that is available as a sub page of the parent you just selected.</p>\r\n<p>\r\n	Some things to note about the blog posts:</p>\r\n<ul>\r\n	<li>\r\n		You can only have 1 level deep, so you can not select a post that is a sub post of another post.</li>\r\n	<li>\r\n		The sub pages of multi page blog posts will not display anywere on the site except on the pages that are part of the same multi-page post</li>\r\n	<li>\r\n		There is no limit to the number of pages that can go in a multi-page post.</li>\r\n	<li>\r\n		If the main post is not active, none of the sub pages will be available.</li>\r\n	<li>\r\n		If you disable a sub page of a multi-page blog post only that page will not be available, the main page and other sub pages (if any) will be available.</li>\r\n</ul>\r\n<p>\r\n	Told you it was pretty simple.</p>\r\n<p>\r\n	&nbsp;</p>',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => 5,
				'ordering' => 1,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-05-21 01:11:57',
				'modified' => '2010-05-21 01:12:13',
				'deleted' => 0,
				'deleted_date' => NULL,
				'category_id' => 10,
				'tags' => ''
			),
			array(
				'id' => 6,
				'title' => 'Creating multi-page blog posts part 2',
				'slug' => 'creating-multi-page-blog-posts-part-2',
				'body' => '<p>\r\n	Just as easy as it was to navigate to this page, it is just as easy to create you multi page post. Skip to part 3 of this 3 part multi-page blog post to see how its done.</p>',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => 5,
				'ordering' => 0,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-02-10 14:21:06',
				'modified' => '2010-05-21 01:05:42',
				'deleted' => 0,
				'deleted_date' => '0000-00-00 00:00:00',
				'category_id' => 10,
				'tags' => ''
			),
			array(
				'id' => 5,
				'title' => 'Creating multi-page blog posts part 1',
				'slug' => 'creating-multi-page-blog-posts-part-1',
				'body' => '<p>\r\n	Sometimes you need to create a blog post that is quite long and not suited to a single page. When you have found this to be a problem click the navigational links on this page for the multi-page posts to find out the secret. It may not be as hard as you think.</p>',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 5,
				'rating_count' => 1,
				'parent_id' => NULL,
				'ordering' => 0,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-02-10 14:19:32',
				'modified' => '2010-05-21 00:56:17',
				'deleted' => 0,
				'deleted_date' => '0000-00-00 00:00:00',
				'category_id' => 10,
				'tags' => ''
			),
			array(
				'id' => 4,
				'title' => 'Infinitas is now at v0.6 alpha',
				'slug' => 'infinitas-is-now-at-v0-6-alpha',
				'body' => '<p>\r\n	0.6 has bought us some neat new functionality and a lot more stable code. Most notably is the addition of the <a href=\"http://cakedc.com/eng/downloads/view/cakephp_migrations_plugin\">CakePHP migrations plug-in</a> that is used for the installer, along with helping keep track of the database additions made during development.</p>\r\n<p>\r\n	Some of the other functionality made available is the use of modules to insert small bits of code in the site. This can be anything from things like <a href=\"https://www.google.com/analytics\">Google analytics</a> code to popular posts. Those familiar with CakePHP will see modules are just elements, but are managed from the backend allowing you to toggle them and move them around your site with ease.</p>\r\n<p>\r\n	Another thing Infinitas 0.6alpha brings is menu management. The menus are loaded by the module loader and allows you to create any number of menus for your site. Creating a new menu is as simple as creating the menu in the menu manager and then adding menu items to it. Once that is done just create a new module in the position of your choice and save.</p>\r\n<p>\r\n	Our focus now is getting 0.7alpha out the door, this will be mostly focused around authentication and access control. The plans are for a full row based access control system which will include building more code into the existing code base allowing administrators to manage everything from the backend. Current plan is to go with <a href=\"http://jmcneese.wordpress.com/2010/01/28/rmac-is-dead-long-live-rmac/\">jmcneese</a>&#39;s implementation.</p>\r\n<p>\r\n	Things like setting moderators for comments, and editors for content will become available. This will become a very fine grained system allowing administrators to set the roles based on categories and tags. For example you could set up Bob as the editor for all content in Category-A, and let Sam handle Category-B. Leaving the &quot;Super Admin&#39;s&quot; with full access to everything in the site.</p>\r\n<p>\r\n	The other main focus of 0.7alpha will be getting the code that is in the repo more stable, and hopefully a lot more work on the themes in the theme repo. Mostly updating them to use the module loader and setting up some more examples so you can see how routes, modules and themes all work together.</p>\r\n<p>\r\n	Should there be some free time available, some more content pages will be created focusing on using Infinitas and how to set up your site, so check back soon.</p>',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 0,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-02-01 18:01:36',
				'modified' => '2010-05-21 01:01:59',
				'deleted' => 0,
				'deleted_date' => '0000-00-00 00:00:00',
				'category_id' => 1,
				'tags' => ''
			),
			array(
				'id' => 2,
				'title' => 'Infinitas Cms is live',
				'slug' => 'infinitas-cms-is-live',
				'body' => '<p>Everything is up and running, so feel free to browse the site, just try not to break it.&nbsp; If you want to have a look at the admin section click <a href=\"http://infinitas-cms.org/admin\">here</a></p>',
				'comment_count' => 0,
				'active' => 1,
				'views' => 0,
				'rating' => 0,
				'rating_count' => 0,
				'parent_id' => NULL,
				'ordering' => 0,
				'locked' => 0,
				'locked_by' => NULL,
				'locked_since' => NULL,
				'created' => '2010-01-20 17:48:26',
				'modified' => '2010-01-21 20:41:18',
				'deleted' => 0,
				'deleted_date' => '0000-00-00 00:00:00',
				'category_id' => 1,
				'tags' => ''
			),
		),
		),
	);
	
/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
?>