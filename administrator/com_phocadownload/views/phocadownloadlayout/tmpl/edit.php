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
JHtml::_('behavior.formvalidation');
?>	
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'phocadownloadlayout.cancel' || document.formvalidator.isValid(document.id('phocadownloadlayout-form'))) {
			Joomla.submitform(task, document.getElementById('phocadownloadlayout-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_phocadownload'); ?>" method="post" name="adminForm" id="phocadownloadlayout-form" class="form-validate">
	<div class="width-60 fltlft">
		<fieldset class="adminform">
			<legend><?php 
			echo JText::_('COM_PHOCADOWNLOAD_LAYOUT'); ?></legend>
		<?php /*
		<ul class="adminformlist">
			<?php 
			$formArray = array ('title', 'alias', 'link_ext', 'link_cat', 'ordering');
			foreach ($formArray as $value) {
				echo '<li>'.$this->form->getLabel($value) . $this->form->getInput($value).'</li>' . "\n";
			} ?>
		</ul> */ ?>
			<?php  echo $this->form->getLabel('categories'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('categories'); ?>
			
			<?php  echo $this->form->getLabel('category'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('category'); ?>
			
			<?php  echo $this->form->getLabel('file'); ?>
			<div class="clr"></div>
			<?php echo $this->form->getInput('file'); ?>
		</fieldset>
	</div>
	
	
	<div class="width-40 fltrt"><?php
	
	echo '<div class="warning">' . JText::_('COM_PHOCADOWNLOAD_LAYOUT_WARNING').'</div>';
	
	
	echo '<div class="pdview"><h4>' . JText::_('COM_PHOCADOWNLOAD_CATEGORIES_VIEW').'</h4>';
	$lP = PhocaDownloadHelper::getLayoutParams('categories');
	echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_PARAMETERS').'</h3></div>';
	if (isset($lP['search'])) {
		foreach ($lP['search'] as $k => $v) {
			echo $v . ' ';
		}
	}
	echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_STYLES').'</h3></div>';
	if (isset($lP['style'])) {
		foreach ($lP['style'] as $k => $v) {
			echo $v . ' ';
		}
	}
	echo '</div>';
	
	echo '<div class="pdview"><h4>' . JText::_('COM_PHOCADOWNLOAD_CATEGORY_VIEW').'</h4>';
	$lP = PhocaDownloadHelper::getLayoutParams('category');
	echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_PARAMETERS').'</h3></div>';
	if (isset($lP['search'])) {
		foreach ($lP['search'] as $k => $v) {
			echo $v . ' ';
		}
	}
	echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_STYLES').'</h3></div>';
	if (isset($lP['style'])) {
		foreach ($lP['style'] as $k => $v) {
			echo $v . ' ';
		}
	}
	echo '</div>';
	
	echo '<div class="pdview"><h4>' . JText::_('COM_PHOCADOWNLOAD_FILE_VIEW').'</h4>';
	$lP = PhocaDownloadHelper::getLayoutParams('file');
	echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_PARAMETERS').'</h3></div>';
	if (isset($lP['search'])) {
		foreach ($lP['search'] as $k => $v) {
			echo $v . ' ';
		}
	}
	echo '<div><h3>' . JText::_('COM_PHOCADOWNLOAD_STYLES').'</h3></div>';
	if (isset($lP['style'])) {
		foreach ($lP['style'] as $k => $v) {
			echo $v . ' ';
		}
	}
	echo '</div>';
	
?>
</div>




<div class="clr"></div>


<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

</div>

	
