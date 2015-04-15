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
		if (task == 'phocadownloadunit.cancel' || document.formvalidator.isValid(document.id('phocadownloadunit-form'))) {
			Joomla.submitform(task, document.getElementById('phocadownloadunit-form'));
		}
		else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<form action="<?php JRoute::_('index.php?option=com_phocadownload'); ?>" method="post" name="adminForm" id="phocadownloadunit-form" class="form-validate">
	<div class="width-60 fltlft">
		
		<fieldset class="adminform">
			<legend><?php echo empty($this->item->id) ? JText::_('COM_PHOCADOWNLOAD_NEW_FILE') : JText::sprintf('COM_PHOCADOWNLOAD_EDIT_FILE', $this->item->id); ?></legend>
			
						
		<ul class="adminformlist">
			<?php
			// Extid is hidden - only for info if this is an external image (the filename field will be not required)
			$formArray = array ('title', 'short_title', 'alias', 'ordering','access','image_unit','id');
			foreach ($formArray as $value) {
				echo '<li>'.$this->form->getLabel($value) . $this->form->getInput($value).'</li>' . "\n";
			} ?>
                    
                    <li>
                        <label title="" class="hasTip" for="jform_preview" id="jform_preview-lbl">Imagem Visualização</label>
                        <div id="jform_preview">
                            <img src="<?php echo ($this->item->image_unit != '')?'../'.$this->item->image_unit:'http://placehold.it/138x71&text=Imagem';?>" alt="Imagem da Unidade">
                        </div>
                    </li>
		</ul>
		
		<div class="clr"></div>
		</fieldset>
	</div>

<div class="width-40 fltrt">
	<div style="text-align:right;margin:5px;"><?php echo $this->tmpl['enablethumbcreationstatus']; ?></div>
	<?php echo JHtml::_('sliders.start','phocadownloadx-sliders-'.$this->item->id, array('useCookie'=>1)); ?>

	<?php echo JHtml::_('sliders.panel',JText::_('COM_PHOCADOWNLOAD_GROUP_LABEL_PUBLISHING_DETAILS'), 'publishing-details'); ?>
		<fieldset class="adminform">
		<ul class="adminformlist">
			<?php foreach($this->form->getFieldset('publish') as $field) {
				echo '<li>';
				if (!$field->hidden) {
					echo $field->label;
				}
				echo $field->input;
				echo '</li>';
			} ?>
			</ul>
		</fieldset>
		
		<?php echo $this->loadTemplate('metadata'); ?>

		<?php echo JHtml::_('sliders.end'); ?>
</div>



<div class="clr"></div>

<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>

	
