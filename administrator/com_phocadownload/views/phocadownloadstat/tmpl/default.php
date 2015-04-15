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
JHtml::_('behavior.tooltip');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');


?>

<form action="<?php echo JRoute::_('index.php?option=com_phocadownload&view=phocadownloadstat'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_PHOCADOWNLOAD_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => 0, 'trash' => 0)), 'value', 'text', $this->state->get('filter.state'), true);?>
			</select>

			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', PhocaDownloadCategory::options('com_phocadownload'), 'value', 'text', $this->state->get('filter.category_id'));?>
			</select>
			
		

		</div>
	</fieldset>
	<div class="clr"> </div>

	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					
					
					<th class="title" width="10%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_FILENAME', 'a.filename',$listDirn, $listOrder ); ?>
					</th>
					
					<th width="80%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_DOWNLOADS', 'a.hits',$listDirn, $listOrder ); ?>
					</th>
					
				</tr>
			</thead>
			
			<tbody>
				<?php
				

$color 	= 0;
$colors = array (
'#FF8080','#FF9980','#FFB380','#FFC080','#FFCC80','#FFD980','#FFE680','#FFF280','#FFFF80','#E6FF80',
'#CCFF80','#99FF80','#80FF80','#80FFC9','#80FFFF','#80C9FF','#809FFF','#9191FF','#AA80FF','#B580FF',
'#D580FF','#FF80FF','#FF80DF','#FF80B8');

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
		if ($item->textonly == 0) {	// Only text (description - no file)		
			
			echo '<tr><td>';
			echo $this->escape($item->title) .' ('.$this->escape($item->category_title).')';
			echo '</td>';

			echo '<td >'. $item->filename.'</td>';

			
			if ((int)$this->maxandsum->maxhit == 0) {
				$per = 0;
				$perOutput = 0;
			} else {
				$per 		= round((int)$item->hits / (int)$this->maxandsum->maxhit * 700);
				$perOutput 	= round((int)$item->hits / (int)$this->maxandsum->sumhit * 100);
			}
				
			echo '<td>';
			echo '<div style="background:'.$colors[$color].' url(\''. JURI::base(true).'/components/com_phocadownload/assets/images/white-space.png'.'\') '.$per.'px 0px no-repeat;width:700px;padding:5px 0px;margin:5px 0px;border:1px solid #ccc;">';
		//	echo '<small style="color:#666666">['. $row->id .']</small>';
			echo '<div> &nbsp;'.$item->hits.' ('.$perOutput .' %) &nbsp;</div>';
			echo '</div>';
			echo '</td></tr>';
				
			$color++;
			if ($color > 23) {
				$color = 0;
			}
			

		}

	}
}
echo '</tbody>';		
?>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="14"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
		</table>
	</div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>