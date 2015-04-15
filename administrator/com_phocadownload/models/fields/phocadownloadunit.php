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

class JFormFieldPhocaDownloadUnit extends JFormField
{
	protected $type 		= 'PhocaDownloadUnit';

	protected function getInput() {
		
		$db = &JFactory::getDBO();

		$query = 'SELECT a.short_title AS text, a.title AS long_text, a.id AS value, a.image_unit as image_unit'
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
		
                $javascript = '';
                
                //$unitsO = JHTML::_('select.genericlist', $units, $this->name, 'class="inputbox" size="12" multiple="multiple"'. $javascript, 'value', 'text', '', '');
                //return $unitsO;
                
                $params = $this->form->getValue('params');
                
                if($units){
                    $html[] = '<ul class="checklist checkbox-list unitsgroups">';
                    foreach ($units as $unit){
                        $eid = 'unit_' . $unit->value;
                        if($params){
                            if($params->module){
                                $checked = in_array($unit->value, $params->units) ? ' checked="checked"' : '';
                            }else{
                                $checked = in_array($unit->value, $params) ? ' checked="checked"' : '';
                            }
                        }
                        $rel = ' rel="unitg_"' . $unit->value;

                        $image = ($unit->image_unit)?'../'.$unit->image_unit:"http://placehold.it/30x30&text={$unit->text}";

                        $html[] = '<li>';
                        $html[] = ' <div class="img img-thumbnail img-pull-left"><img src="'.$image.'" /></div>';
                        $html[] = ' <input type="checkbox" name="' . $this->name . '[]" value="' . $unit->value . '" id="' . $eid . '"';
                        $html[] = '     ' . $checked . $rel . ' />';
    //                    $html[] = ' <img class="img img-thumbnail img-to-right" src="'.$image.'" />';
                        $html[] = ' <label for="' . $eid . '">';
                        $html[] = $unit->text;
                        $html[] = ' <p class="smallsub">(<span>Titulo</span>: '.$unit->long_text.')</p>';
                        $html[] = ' </label>';
                        $html[] = '</li>';
                    }
                    $html[] = '</ul>';
                }else{
                    if($params->module){
                        $html[] = '<span class="readonly warning" style="color: #E14C40"><b>Atenção:</b> Sem unidades cadastradas ou publicadas para selecionar.</span>';
                    }else{
                        $html[] = '<span class="readonly warning" style="color: #E14C40"><b>Atenção:</b> Para cadastrar uma localidade e necessário antes possuir Unidades cadastradas ou publicadas.</span>';
                        $html[] = '<input type="hidden" name="'.$this->name.'" value=""/>';
                    }
                }

                return implode("\n", $html);
	}
}
?>