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

class PhocaDownloadCpModelPhocaDownloadLocation extends JModelAdmin
{
	protected	$option 		= 'com_phocadownload';
	protected 	$text_prefix	= 'com_phocadownload';
	
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		if ($record->catid) {
			return $user->authorise('core.delete', 'com_phocadownload.phocadownloadlocation.'.(int) $record->catid);
		} else {
			return parent::canDelete($record);
		}
	}
	
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		if ($record->catid) {
			return $user->authorise('core.edit.state', 'com_phocadownload.phocadownloadlocation.'.(int) $record->catid);
		} else {
			return parent::canEditState($record);
		}
	}
	
	public function getTable($type = 'PhocaDownloadLocations', $prefix = 'Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) {
		
		$app	= JFactory::getApplication();
		$form 	= $this->loadForm('com_phocadownload.phocadownloadlocation', 'phocadownloadlocation', array('control' => 'jform', 'load_data' => $loadData));
		
		if (empty($form)) {
			return false;
		}
		return $form;
	}
	
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_phocadownload.edit.phocadownloadlocation.data', array());

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
                                $db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_locations');
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
		
                if($data['params']){
                    $registry = new JRegistry();
                    $registry->loadArray($data['params']);
                    $table->params = $registry->toString();
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
			$query = 'DELETE FROM #__phocadownload_locations'
				. ' WHERE id IN ( '.$cids.' )';
			$this->_db->setQuery( $query );
			if(!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg);
				return false;
			}
		}
		return true;
	}
	
	protected function batchAlter($value, $pks)
	{

		$table	= $this->getTable();
		$db	= $this->getDbo();

		// Check that the category exists
		if ($value) {
                    $unitsTable = JTable::getInstance('PhocaDownloadUnits', 'Table');
                    foreach ($value as $v){
			if (!$unitsTable->load($v)) {
				if ($error = $unitsTable->getError()) {
					// Fatal error
					$this->setError($error);
					return false;
				}
				else {
					$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_MOVE_CATEGORY_NOT_FOUND'));
					return false;
				}
			}
                    }
		}
                
                $registry = new JRegistry();
                $registry->loadArray($value);
                $unitsString = $registry->toString();
                
                
		// Check that the user has create permission for the component
		$extension	= JRequest::getCmd('option');
		$user		= JFactory::getUser();
		if (!$user->authorise('core.create', $extension)) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
			return false;
		}
		
                foreach($pks as $pk)
                {

                        // Check that the row actually exists
                        if (!$table->load($pk)) {
                                if ($error = $table->getError()) {
                                        // Fatal error
                                        $this->setError($error);
                                        return false;
                                }
                                else {
                                        // Not fatal error
                                        $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
                                        continue;
                                }
                        }

                        $table->params = $unitsString;

                        // Check the row.
                        if (!$table->check()) {
                                $this->setError($table->getError());
                                return false;
                        }

                        // Store the row.
                        if (!$table->store()) {
                                $this->setError($table->getError());
                                return false;
                        }

                }
		
                // Clean the cache
		$this->cleanCache();

		return true;
	}
	
        public function batch($commands, $pks)
	{

		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true)) {
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks)) {
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		if (!empty($commands['assetgroup_id'])) {
			if (!$this->batchAccess($commands['assetgroup_id'], $pks)) {
				return false;
			}

			$done = true;
		}

		if (isset($commands['unit_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'alter', 'a');
                        
			if ($cmd == 'a')
			{
				$result = $this->batchAlter($commands['unit_id'], $pks, $contexts);
                                
				if (is_array($result))
				{
					$pks = $result;
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'r' && !$this->batchRemove($commands['unit_id'], $pks, $contexts))
			{
				return false;
			}
			$done = true;
		}
		
		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done) {
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
	
	public function increaseOrdering($locationId) {
		
		$ordering = 1;
		$this->_db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_locations');
                $this->_db->setQuery('SELECT MAX(ordering) FROM #__phocadownload_locations WHERE id='.(int)$locationId);
		$max = $this->_db->loadResult();
		$ordering = $max + 1;
                var_dump($ordering);exit;
                return $ordering;
	}
}
?>