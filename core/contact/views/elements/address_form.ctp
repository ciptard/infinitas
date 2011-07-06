<?php		
	if(!isset($plugin) || !$plugin || $plugin == 'contact'){
		$plugin = $this->plugin;
	}
	if(!isset($model) || !$model){
		$model = $this->params['models'][0];
	}

	if(!isset($countries) || !$countries){
		$countries = ClassRegistry::init('Address')->Country->find('list');
	}

	$addressSelect = (isset($addressSelect)) ?(bool)$addressSelect : false;
?>
<fieldset>
	<h1><?php echo __('Address', true); ?></h1><?php
	echo $this->Form->hidden('Address.id');

	$options = ClassRegistry::init('Address')->getAddressesByRelated(
		array(
			'Address.plugin' => $plugin,
			'Address.model' => $model
		)
	);

	if($addressSelect){
		echo $this->Form->input($model . '.address_id', array('options' => $options, 'label' => __('Use existing', true), 'empty' => Configure::read('Website.empty_select')));
	}
	
	echo $this->Form->hidden('Address.plugin', array('value' => $plugin));
	echo $this->Form->hidden('Address.model', array('value' => $model));
	echo $this->Form->hidden('Address.foreign_key');

	echo $this->Form->input('Address.name');
	echo $this->Form->input('Address.street');
	echo $this->Form->input('Address.city');
	echo $this->Form->input('Address.province');
	echo $this->Form->input('Address.postal');
	echo $this->Form->input('Address.continent_id', array('empty' => Configure::read('Website.empty_select'), 'options' => Configure::read('Contact.continents')));
	echo $this->Form->input('Address.country_id', array('empty' => Configure::read('Website.empty_select'), 'options' => $countries)); ?>
</fieldset>