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

if (! class_exists('PhocaDownloadHelper')) {
    require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocadownload'.DS.'helpers'.DS.'phocadownload.php');
}

class JFormFieldPhocaDownloadUnitSelect extends JFormField
{
	protected $type 		= 'PhocaDownloadUnitSelect';

	protected function getInput() {
		
		$db = &JFactory::getDBO();

		$query = 'SELECT a.title AS text, a.short_title AS short_text, a.id AS value'
		. ' FROM #__phocadownload_units AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
                
		if (!$db->query()) {
			$this->setError('Database Error - Getting All Tags');
			return false;
		}
                
		$units = $db->loadObjectList();
                
                $required	= ((string) $this->element['required'] == 'true') ? TRUE : FALSE;
		
                $html[] = JHtml::_('select.option', '', JText::_('JSELECT'));
                foreach($units as $u){
                    $html[] = JHtml::_('select.option', $u->value, $u->short_text . ' - ( '.$u->text.' )');
                }
                
                $javascript = '';
                
                $unitsO = JHTML::_('select.genericlist', $html, $this->name, 'class="inputbox required" '. $javascript, 'value', 'text', $this->value, $this->id);
                
                return $unitsO;
	}
}
?>