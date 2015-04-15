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
$id 		= JRequest::getVar( 'id', '', '', 'int');
?>

<form action="<?php echo JRoute::_('index.php?option=com_phocadownload&view=phocadownloaduserstats&id='.(int)$id); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_PHOCADOWNLOAD_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			
			

		</div>
	</fieldset>
	<div class="clr"> </div>

	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
				
					
					<th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
					
					<th class="title" width="10%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_USER', 'usernameno', $listDirn, $listOrder); ?>
					</th>
					
					<th class="title" width="10%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_USERNAME', 'username', $listDirn, $listOrder); ?>
					</th>
					
					<th class="title" width="30%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_TITLE', 'd.title', $listDirn, $listOrder); ?>
					</th>
					<th width="30%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_FILENAME', 'd.filename',$listDirn, $listOrder ); ?>
					</th>
					
					<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_DOWNLOADS', 'a.count',$listDirn, $listOrder ); ?>
					</th>
					
					<th width="20%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_DATE', 'a.date',$listDirn, $listOrder ); ?>
					</th>

					
					<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_ID', 'a.id',$listDirn, $listOrder ); ?>
					</th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
					

				
echo '<tr class="row'. $i % 2 .'">';
					
echo '<td class="center">'. JHtml::_('grid.id', $i, $item->id) . '</td>';


echo '<td>';
echo $item->usernameno ? ' ('.$item->usernameno.')' : JText::_('COM_PHOCADOWNLOAD_GUEST');
echo '</td>';
echo '<td>';
echo $item->username ? ' ('.$item->username.')' : JText::_('COM_PHOCADOWNLOAD_GUEST');
echo '</td>';


echo '<td >'.$this->escape($item->filetitle).'</td>';

				
echo '<td>'. $item->filename.'</td>';

echo '<td align="center">'. $item->count.'</td>';


echo '<td align="center">'. JHTML::Date($item->date, JText::_('DATE_FORMAT_LC3')) .'</td>';
echo '<td align="center">'. $item->id .'</td>';

echo '</tr>';

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
