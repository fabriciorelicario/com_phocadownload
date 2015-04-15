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

if (isset($this->tmpl['notapproved']->count) && (int)$this->tmpl['notapproved']->count > 0 ) {
	echo '<div class="notapproved">'.JText::_('COM_PHOCADOWNLOAD_NOT_APPROVED_FILES_COUNT').': '.(int)$this->tmpl['notapproved']->count.'</div>';
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_phocadownload&view=phocadownloadfiles'); ?>" method="post" name="adminForm" id="adminForm">
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
					
					<th class="title" width="25%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_TITLE', 'a.title', $listDirn, $listOrder); ?>
					</th>
					<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_FILENAME', 'a.filename',$listDirn, $listOrder ); ?>
					</th>
					
					<th width="8%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_DOWNLOADS', 'a.hits',$listDirn, $listOrder ); ?>
					</th>
					
					<th width="8%" nowrap="nowrap"><?php echo JText::_('COM_PHOCADOWNLOAD_USER_STATISTICS'); ?>
					</th>
					
					<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_OWNER', 'a.owner_id',$listDirn, $listOrder ); ?>
					</th>
					
					<th width="5%"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_UPLOADED_BY', 'uploadusername',$listDirn, $listOrder ); ?></th>
					
					<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_PHOCADOWNLOAD_PUBLISHED', 'a.published',$listDirn, $listOrder ); ?>
					</th>
					<th width="5%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_PHOCADOWNLOAD_APPROVED', 'a.approved',$listDirn, $listOrder ); ?>
					</th>
					
					<th width="8%" nowrap="nowrap"><?php echo JText::_('COM_PHOCADOWNLOAD_ACTIVE'); ?>
					</th>
					
					<th width="8%"  class="title">
						<?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_CATEGORY', 'category_id',$listDirn, $listOrder ); ?></th>
					
					<th width="13%">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder);
					if ($canOrder && $saveOrder) {
						echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'phocadownloadfiles.saveorder');
					} ?>
					</th>
					
					<th width="7%">
					<?php //echo JHTML::_('grid.sort',   'Access', 'groupname', @$lists['order_Dir'], @$lists['order'] );
					echo JTEXT::_('COM_PHOCADOWNLOAD_ACCESS');

					?>
					</th>
					
					<th width="5%">
			<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
		</th> 
					
					<th width="1%" nowrap="nowrap"><?php echo JHTML::_('grid.sort',  'COM_PHOCADOWNLOAD_ID', 'a.id',$listDirn, $listOrder ); ?>
					</th>
				</tr>
			</thead>
			
			<tbody>
				<?php
				

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
					
$ordering	= ($listOrder == 'a.ordering');			
$canCreate	= $user->authorise('core.create', 'com_phocadownload');
$canEdit	= $user->authorise('core.edit', 'com_phocadownload');
$canCheckin	= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange	= $user->authorise('core.edit.state', 'com_phocadownload') && $canCheckin;
$linkEdit	= JRoute::_( 'index.php?option=com_phocadownload&task=phocadownloadfile.edit&id='.(int) $item->id );
$linkCat	= JRoute::_( 'index.php?option=com_phocadownload&task=phocadownloadcat.edit&id='.(int) $item->category_id );
$canEditCat	= $user->authorise('core.edit', 'com_phocadownload');
$linkUserStatistics = JRoute::_( 'index.php?option=com_phocadownload&view=phocadownloaduserstats&id='.(int)$item->id );
				
echo '<tr class="row'. $i % 2 .'">';
					
echo '<td class="center">'. JHtml::_('grid.id', $i, $item->id) . '</td>';


echo '<td>'; 
if ($item->checked_out) {
	echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'phocadownloadfiles.', $canCheckin);
}

if ($canCreate || $canEdit) {
	echo '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
} else {
	echo $this->escape($item->title);
}
echo '<p class="smallsub">(<span>'.JText::_('COM_PHOCADOWNLOAD_FIELD_ALIAS_LABEL').':</span>'. $this->escape($item->alias).')</p>';
echo '</td>';
				
echo '<td align="center">'. $item->filename.'</td>';
echo '<td align="center">'. $item->hits.'</td>';

echo '<td align="center">';

	if ($item->textonly != 1) {
		echo '<a href="'. $linkUserStatistics.'">'
		. JHTML::_('image', 'administrator/components/com_phocadownload/assets/images/icon-16-user-stat.png', JText::_('COM_PHOCADOWNLOAD_USER_STATISTICS'))
		.'</a>';
	}
echo '</td>';




echo '<td>';
echo $item->usernameno;
echo $item->username ? ' ('.$item->username.')' : '';
echo '</td>';

echo '<td>';
echo $item->uploadname;
echo $item->uploadusername ? ' ('.$item->uploadusername.')' : '';
echo '</td>';

echo '<td class="center">'. JHtml::_('jgrid.published', $item->published, $i, 'phocadownloadfiles.', $canChange) . '</td>';
echo '<td class="center">'. PhocaDownloadGrid::approved( $item->approved, $i, 'phocadownloadfiles.', $canChange) . '</td>';

$db			= &JFactory::getDBO();
$nullDate 	= $db->getNullDate();
$now		= &JFactory::getDate();
$config		= &JFactory::getConfig();
$publish_up 	= &JFactory::getDate($item->publish_up);
$publish_down 	= &JFactory::getDate($item->publish_down);
$publish_up->setOffset($config->getValue('config.offset'));
$publish_down->setOffset($config->getValue('config.offset'));
if ( $now->toUnix() <= $publish_up->toUnix() ) {
	$text = JText::_( 'COM_PHOCADOWNLOAD_PENDING' );
} else if ( ( $now->toUnix() <= $publish_down->toUnix() || $item->publish_down == $nullDate ) ) {
	$text = JText::_( 'COM_PHOCADOWNLOAD_ACTIVE' );
} else if ( $now->toUnix() > $publish_down->toUnix() ) {
	$text = JText::_( 'COM_PHOCADOWNLOAD_EXPIRED' );
}

$times = '';
if (isset($item->publish_up)) {
	if ($item->publish_up == $nullDate) {
		$times .= JText::_( 'COM_PHOCADOWNLOAD_START') . ': '.JText::_( 'COM_PHOCADOWNLOAD_ALWAYS' );
	} else {
		$times .= JText::_( 'COM_PHOCADOWNLOAD_START') .": ". $publish_up->toFormat();
	}
}
if (isset($item->publish_down)) {
	if ($item->publish_down == $nullDate) {
		$times .= "<br />". JText::_( 'COM_PHOCADOWNLOAD_FINISH'). ': '. JText::_('COM_PHOCADOWNLOAD_NO_EXPIRY' );
	} else {
		$times .= "<br />". JText::_( 'COM_PHOCADOWNLOAD_FINISH') .": ". $publish_down->toFormat();
	}
}

if ( $times ) {
	echo '<td align="center">'
		.'<span class="editlinktip hasTip" title="'. JText::_( 'COM_PHOCADOWNLOAD_PUBLISH_INFORMATION' ).'::'. $times.'">'
		.'<a href="javascript:void(0);" >'. $text.'</a></span>'
		.'</td>';
}




?>
<td class="center">
	<?php if ($canEditCat) {
		echo '<a href="'. JRoute::_($linkCat).'">'. $this->escape($item->category_title).'</a>';
	} else {
		echo $this->escape($item->category_title);
	} ?>
</td>
<?php			

$cntx = 'phocadownloadfiles';
echo '<td class="order">';
if ($canChange) {
	if ($saveOrder) {
		if ($listDirn == 'asc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, ($item->category_id == @$this->items[$i-1]->category_id), $cntx.'.orderup', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, ($item->category_id == @$this->items[$i+1]->category_id), $cntx.'.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		} else if ($listDirn == 'desc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, ($item->category_id == @$this->items[$i-1]->category_id), $cntx.'.orderdown', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, ($item->category_id == @$this->items[$i+1]->category_id), $cntx.'.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		}
	}
	$disabled = $saveOrder ?  '' : 'disabled="disabled"';
	echo '<input type="text" name="order[]" size="5" value="'.$item->ordering.'" '.$disabled.' class="text-area-order" />';
} else {
	echo $item->ordering;
}
echo '</td>';


echo '<td align="center">' . $this->escape($item->access_level) .'</td>';


?>
<td class="center">
	<?php
	if ($item->language=='*') {
		echo JText::_('JALL');
	} else {
		echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED');
	}
	?>
</td>
<?php
echo '<td align="center">'. $item->id .'</td>';

echo '</tr>';

		}
	}
echo '</tbody>';		
?>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="15"><?php echo $this->pagination->getListFooter(); ?></td>
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