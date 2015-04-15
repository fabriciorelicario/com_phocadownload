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

defined('JPATH_PLATFORM') or die;

abstract class PhocaDownloadUnits
{
	
	public static function item($published, $category = 0)
	{
		// Create the copy/move options.
		$options = array(
			JHtml::_('select.option', 'a', JText::_('JLIB_HTML_BATCH_ALTER')),
//			JHtml::_('select.option', 'r', JText::_('JLIB_HTML_BATCH_REMOVE'))
		);
		
		$db = &JFactory::getDBO();

       //build the list of categories
		$query = 'SELECT a.title AS text, a.id AS value, a.short_title AS short_text'
		. ' FROM #__phocadownload_units AS a'
                . ' WHERE a.published = 1'
		. ' ORDER BY a.ordering';
		$db->setQuery( $query );
		$data = $db->loadObjectList();
		
                if($data){
                    foreach($data as $d){
                        $html[] = JHtml::_('select.option', $d->value, $d->short_text . ' - ( '.$d->text.' )');
                    }

                    // Create the batch selector to change select the category by which to move or copy.
                    $lines = array(
                            '<label id="batch-choose-action-lbl" for="batch-choose-action">',
                            JText::_('JLIB_HTML_BATCH_UNITS_LABEL'),
                            '</label>',
                            '<fieldset id="batch-choose-action" class="combo">',
                                    '<select name="batch[unit_id][]" class="inputbox" id="batch-unit-id" multiple="multiple" size="6">',
                                            JHTML::_('select.options',  $html ),
                                    '</select>',
                                    JHTML::_( 'select.radiolist', $options, 'batch[alter]', '', 'value', 'text', 'a'),
                            '</fieldset>'
                    );
                }else{
                    $lines = array(
                            '<label id="batch-choose-action-lbl" for="batch-choose-action">',
                            JText::_('JLIB_HTML_BATCH_UNITS_LABEL'),
                            '</label>',
                            '<fieldset id="batch-choose-action" class="combo">',
                                    '<select name="batch[unit_id][]" class="inputbox" id="batch-unit-id" multiple="multiple" size="6">',
                                            
                                    '</select>',
                            '</fieldset>'
                    );
                }
                
                

		return implode("\n", $lines);
	}
        
        public static function getListItem($items = array()){
            $db = &JFactory::getDBO();
            if($items){
                $item = implode(',', $items);
                $query = 'SELECT a.title AS text, a.id AS value, a.short_title AS short_text, a.published AS published'
                . ' FROM #__phocadownload_units AS a'
                . ' WHERE id in ('.$item.')'
                . ' ORDER BY a.ordering';
            }else{
                $query = 'SELECT a.title AS text, a.id AS value, a.short_title AS short_text'
                . ' FROM #__phocadownload_units AS a'
                . ' ORDER BY a.ordering';
            }

            $db->setQuery( $query );
            $units = $db->loadObjectList();
            
            if($items){
                return $units;
            }else{
                $html = array();
                foreach($units as $unit){
                    $html[] = JHtml::_('select.option', $unit->value, $unit->short_text . ' - ( '.$unit->text.' )');
                }

                return $html;
            }
        }
        
        public static function getList(){
            $db = &JFactory::getDBO();
            
            $query = 'SELECT a.title AS text, a.id AS value, a.short_title AS short_text'
            . ' FROM #__phocadownload_units AS a'
            . ' ORDER BY a.ordering';
                        
            $db->setQuery( $query );
            $units = $db->loadObjectList();
                        
            return $units;
        }
        
        public static function getItemTitle($id){
            $db = &JFactory::getDBO();
            
            $query = 'SELECT a.title AS text, a.id AS value, a.short_title AS short_text'
            . ' FROM #__phocadownload_units AS a'
            . ' WHERE id = '.$id
            . ' ORDER BY a.ordering';
            
            $db->setQuery( $query );
            $units = $db->loadObject();
            $html = $units->short_text;
//            $html = $units->short_text . ' - ( '.$units->text.' )';

            
            return $html;
        }
}
