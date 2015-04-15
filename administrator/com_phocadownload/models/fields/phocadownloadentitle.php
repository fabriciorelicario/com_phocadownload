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

class JFormFieldPhocaDownloadEntitle extends JFormField
{
	protected $type 		= 'PhocaDownloadEntitle';

	protected function getInput() {
		
		$db = &JFactory::getDBO();

		$query = 'SELECT a.title AS text, a.id AS value'
		. ' FROM #__phocadownload_entitle AS a'
		. ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();
	
		$options = array();
                if($data){
                    foreach ($data as $d){
                        $options[] = JHtml::_('select.option', $d->value, $d->text);
                    }
                }
		
                $javascript = 'onchange="document.id(\'jform_title\').value = this.options[this.selectedIndex].text"';
                
		array_unshift($options, JHTML::_('select.option', '', '- '.JText::_('COM_PHOCADOWNLOAD_SELECT_ENTITLE').' -', 'value', 'text'));
                return JHTML::_('select.genericlist',  $options,  $this->name, 'class="inputbox required"' . $javascript, 'value', 'text', $this->value, $this->id );
	}
}
?>