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
defined('_JEXEC') or die;

class PhocaDownloadLayoutsHelper
{
	public static function getActions($categoryId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_phocadownload';
		
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
	
	public function getTableId() {
		
		$idString 	= '';
		
		$db 	=& JFactory::getDBO();
		$query .= ' SELECT a.id'
				 .' FROM #__phocadownload_layout AS a';
		$db->setQuery($query, 0,1);
		
		if (!$db->query()) {
			$this->setError('Database Error - Getting Layout ID');
			return false;
		}
		
		$idO 		= $db->loadObject();
		
		
		//First autoincrement line can be different
		if (isset($idO->id) && $idO->id > 0) {
			$idString 	= '&id='.(int)$idO->id;
		}
		
		return $idString;
	}
}
?>