<?php
class FixtureTask extends Shell {
	public $connection = 'default';
	
	public function generate($models, $plugin) {
		$fixtures = array();
		foreach($models as $model => $options) {
			if(isset($options['core'])) {
				$modelName = $plugin . '.' . $model;
				$conditions = 'WHERE ' . $options['core']['where'];
				if($options['core']['limit'] != 0) {
					$conditions .= ' LIMIT ' . $options['core']['limit'];
				}
				$fixtures['core'][$modelName] = $this->_getRecordsFromTable($modelName, $conditions);
			}
		}

		return $this->_makeRecordString($fixtures);
	}

/**
 * Interact with the user to get a custom SQL condition and use that to extract data
 * to build a fixture.
 *
 * @param string $modelName name of the model to take records from.
 * @param string $useTable Name of table to use.
 * @return array Array of records.
 * @access protected
 */
	function _getRecordsFromTable($modelName, $condition, $useTable = null) {
		$modelObject = ClassRegistry::init($modelName);
		$records = $modelObject->find('all', array(
			'conditions' => $condition,
			'recursive' => -1
		));
		$db =& ConnectionManager::getDataSource($modelObject->useDbConfig);
		$schema = $modelObject->schema(true);
		$out = array();
		foreach ($records as $record) {
			$row = array();
			foreach ($record[$modelObject->alias] as $field => $value) {
				$row[$field] = $db->value($value, $schema[$field]['type']);
			}
			$out[] = $row;
		}
		return $out;
	}

/**
 * Convert a $records array into a a string.
 *
 * @param array $records Array of records to be converted to string
 * @return string A string value of the $records array.
 * @access protected
 */
	function _makeRecordString($records) {
		$out = '';
		foreach($records as $type => $models) {
			$out .= "\t'$type' => array(\n";
			foreach($models as $model => $modelRecords) {
				$out .= "\t\t'$model' => array(\n";
				foreach ($modelRecords as $record) {
					$values = array();
					foreach ($record as $field => $value) {
						$values[] = "\t\t\t\t'$field' => $value";
					}
					$out .= "\t\t\tarray(\n";
					$out .= implode(",\n", $values);
					$out .= "\n\t\t\t),\n";
				}
				$out .= "\t\t),\n";
			}
			$out .= "\t\t),\n";
		}
		return $out;
	}
}