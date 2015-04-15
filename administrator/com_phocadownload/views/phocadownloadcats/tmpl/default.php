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
$canOrder	= $user->authorise('core.edit.state', 'com_phocadownload');
$saveOrder	= 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_phocadownload&view=phocadownloadcats'); ?>" method="post" name="adminForm" id="adminForm">
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
<?php /*
			<select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
				<?php echo JHtml::_('select.options', PhocaDownloadCategory::options('com_phocadownload'), 'value', 'text', $this->state->get('filter.parent_id'));?>
			</select> */ ?>
			
			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>

		</div>
	</fieldset>
	<div class="clr"> </div>
	

	<div id="editcell">
		<table class="adminlist">
			<thead>
				<tr>
					
					<th width="5"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
					
					<th class="title" width="50%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_TITLE', 'a.title', $listDirn, $listOrder ); ?>
					</th>
					
					<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_PUBLISHED', 'a.published', $listDirn, $listOrder ); ?>
			
					
					<th width="15%"  class="title">
						<?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_PARENT_CATEGORY', 'parentcat_title', $listDirn, $listOrder ); ?></th>
					
					<th width="13%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder);
					if ($canOrder && $saveOrder) {
						echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'phocadownloadcats.saveorder');
					} ?>
					</th>
					
					
					<th width="7%">
					<?php //echo JHTML::_('grid.sort',   'Access', 'groupname', @$lists['order_Dir'], @$lists['order'] );
					echo JTEXT::_('COM_PHOCADOWNLOAD_ACCESS');

					?>
					</th>
				
					

					<?php /*
					<th width="5%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_HITS', 'a.hits', $listDirn, $listOrder ); ?>
					</th>	*/ ?>

<th width="5%">
			<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
		</th>					
						
					<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_ID', 'a.id', $listDirn, $listOrder ); ?>
					</th>
				</tr>
			</thead>
			
			<tbody><?php
				
$j = 0;
if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
	
	//We can unset the values in array or filter here
	if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
		$j++;
		
$ordering	= ($listOrder == 'a.ordering');			
$canCreate	= $user->authorise('core.create', 'com_phocadownload');
$canEdit	= $user->authorise('core.edit', 'com_phocadownload');
$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange	= $user->authorise('core.edit.state', 'com_phocadownload') && $canCheckin;
$linkEdit 	= JRoute::_( 'index.php?option=com_phocadownload&task=phocadownloadcat.edit&id='. $item->id );

$linkParent	= JRoute::_( 'index.php?option=com_phocadownload&task=phocadownloadcat.edit&id='.(int) $item->parent_id );
$canEditParent	= $user->authorise('core.edit', 'com_phocadownload');
					
echo '<tr class="row'. $i % 2 .'">';
					
echo '<td class="center">'. JHtml::_('grid.id', $i, $item->id) . '</td>';
					
echo '<td>'; 
if ($item->checked_out) {
	echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'phocadownloadcats.', $canCheckin);
}

if ($canCreate || $canEdit) {
	echo '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
} else {
	echo $this->escape($item->title);
}
echo '<p class="smallsub">(<span>'.JText::_('COM_PHOCADOWNLOAD_FIELD_ALIAS_LABEL').':</span>'. $this->escape($item->alias).')</p>';
echo '</td>';

echo '<td class="center">'. JHtml::_('jgrid.published', $item->published, $i, 'phocadownloadcats.', $canChange) . '</td>';


?>
<td class="center">
	<?php if ($canEditParent) {
		echo '<a href="'. JRoute::_($linkParent).'">'. $this->escape($item->parentcat_title).'</a>';
	} else {
		echo $this->escape($item->parentcat_title);
	} ?>
</td>
<?php 
$cntx = 'phocadownloadcats';
echo '<td class="order">';
if ($canChange) {
	if ($saveOrder) {
		
		/*if ($listDirn == 'asc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, ($item->parentcat_id == @$this->items[$i-1]->parentcat_id), $cntx.'.orderup', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, ($item->parentcat_id == @$this->items[$i+1]->parentcat_id), $cntx.'.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		} else if ($listDirn == 'desc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, ($item->parentcat_id == @$this->items[$i-1]->parentcat_id), $cntx.'.orderdown', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, ($item->parentcat_id == @$this->items[$i+1]->parentcat_id), $cntx.'.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		}*/
		
		if ($listDirn == 'asc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, ($item->orderup == 1), $cntx.'.orderup', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, ($item->orderdown == 1), $cntx.'.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		} else if ($listDirn == 'desc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, ($item->orderup == 1), $cntx.'.orderdown', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, ($item->orderdown == 1), $cntx.'.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		}
	}
	$disabled = $saveOrder ?  '' : 'disabled="disabled"';
	echo '<input type="text" name="order[]" size="5" value="'.$item->ordering.'" '.$disabled.' class="text-area-order" />';
} else {
	echo $item->ordering;
}
echo '</td>';

echo '<td align="center">' . $this->escape($item->access_level) .'</td>';
					

//echo '<td align="center">'. $item->hits.'</td>';

echo '<td class="center">';
if ($item->language=='*') {
	echo JText::_('JALL');
} else {
	echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED');
}
echo '</td>'; 

echo '<td align="center">'. $item->id .'</td>';

echo '</tr>';						
		}
	}
}
echo '</tbody>';
?>			
			<tfoot>
				<tr>
					<td colspan="10"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
		</table>
		<?php echo $this->loadTemplate('batch'); ?>
	</div>

<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>