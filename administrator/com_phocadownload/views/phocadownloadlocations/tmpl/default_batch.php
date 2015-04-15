<?php
/**
 * @version		$Id: default_batch.php 21020 2011-03-27 06:52:01Z infograf768 $
 * @package		Joomla.Administrator
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

$published = $this->state->get('filter.state');
?>
<fieldset class="batch">
	<legend><?php echo JText::_('COM_PHOCADOWNLOAD_BATCH_OPTIONS_LOCATIONS');?></legend>
	<?php  echo JHtml::_('batch.access');  ?>
	<?php echo JHtml::_('batch.language');?>

        <?php echo PhocaDownloadUnits::item($published); ?>
        
        <button type="submit" onclick="Joomla.submitbutton('phocadownloadlocation.batch');">
                <?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
        </button>
        <button type="button" onclick="clearMultiSelect('batch-unit-id');document.id('batch-access').value=''">
                <?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>
        </button>

</fieldset>

<script type="text/javascript">
    
    function clearMultiSelect(id){
        elements = document.id(id).options;

        for(var i = 0; i < elements.length; i++){
              elements[i].selected = false;
            }
    }
</script>