<?php
	$menuToLoad  = (isset($config['menu'])?$config['menu']:'core_admin');
	$type = (isset($config['type'])?$config['type']:'horizontal');

	echo $this->Infinitas->generateMenu(ClassRegistry::init('Management.MenuItem')->getMenu($menuToLoad), $type);
?>