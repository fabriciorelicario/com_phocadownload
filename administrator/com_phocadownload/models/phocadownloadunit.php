<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.modeladmin');

class PhocaDownloadCpModelPhocaDownloadUnit extends JModelAdmin
{
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if ($record->id) {
			return $user->authorise('core.delete', 'com_phocadownload.phocadownloadunit.'.(int) $record->id);
		} else {
			return parent::canDelete($record);
		}
	}
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if ($record->id) {
			return $user->authorise('core.edit.state', 'com_phocadownload.phocadownloadunit.'.(int) $record->id);
		} else {
			return parent::canEditState($record);
		}
	}
	
	public function getTable($type = 'PhocaDownloadUnits', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloadunit', 'phocadownloadunit', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownload.edit.phocadownloadunit.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}
	
		public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadJSON($item->metadata);
			$item->metadata = $registry->toArray();
		}

		return $item;
	}
	
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->title);
		}

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
                                $db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_units');
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
			//$table->modified	= $date->toMySQL();
			//$table->modified_by	= $user->get('id');
		}
	}
	

	
	function save($data) {
		
		if ($data['alias'] == '') {
			$data['alias'] = $data['title'];
		}
		
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table		= $this->getTable();
		$pk			= (!empty($data['id'])) ? $data['id'] : (int)$this->getState($this->getName().'.id');
		$isNew		= true;

		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');

		// Load the row if saving an existing record.
		if ($pk > 0) {
			$table->load($pk);
			$isNew = false;
		}

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}
		
		// Date - - - - - 
		$nullDate	= $this->_db->getNullDate();
		$config 	= &JFactory::getConfig();
		$tzoffset 	= $config->getValue('config.offset');
		
		if ($this->id) {

		} else {
			if (!intval($table->date)) {
				$date	= JFactory::getDate();
				$table->date = $date->toSql();
			}
		}
		
		if(intval($table->publish_up) == 0) {
			$table->publish_up = JFactory::getDate()->toMySQL();
		}
		
		// Handle never unpublish date
		if (trim($table->publish_down) == JText::_('Never') || trim( $table->publish_down ) == '') {
			$table->publish_down = $nullDate;
		} else {
			if (strlen(trim( $table->publish_down )) <= 10) {
				$table->publish_down .= ' 00:00:00';
			}
			//$date =& JFactory::getDate($table->publish_down, $tzoffset);
			$date =& JFactory::getDate($table->publish_down);
			$table->publish_down = $date->toMySQL();
		}
		// - - - - - -
		
		if ($table->publish_down > $nullDate && $table->publish_down < $table->publish_up) {
			$this->setError(JText::_('JGLOBAL_START_PUBLISH_AFTER_FINISH'));
			return false;
		}
		

		// if new item, order last in appropriate group
		if (!$table->id) {
			$table->ordering = $table->getNextOrder();
		}
		

		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}


		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}
		
		// Store to ref table
		if (!isset($data['tags'])) {
			$data['tags'] = array();
		}
		if ((int)$table->id > 0) {
			PhocaDownloadTagHelper::storeTags($data['tags'], (int)$table->id);
		}

		// Clean the cache.
		$cache = JFactory::getCache($this->option);
		$cache->clean();


		$pkName = $table->getKeyName();
		if (isset($table->$pkName)) {
			$this->setState($this->getName().'.id', $table->$pkName);
		}
		$this->setState($this->getName().'.new', $isNew);
		

		
		return true;
	}
	
		

	function delete($cid = array()) {
		
		$result 			= false;

		$paramsC 		= JComponentHelper::getParams('com_phocadownload');
		$deleteExistingFiles 	= $paramsC->get( 'delete_existing_files', 0 );
		
		if (count( $cid )) {
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );
			
			//Delete it from DB
			$query = 'DELETE FROM #__phocadownload_units'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg);
				return false;
			}
		}
		return true;
	}

        public function increaseOrdering($categoryId) {
		
		$ordering = 1;
		$this->_db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_units');
		$max = $this->_db->loadResult();
		$ordering = $max + 1;
                return $ordering;
	}
}
?>