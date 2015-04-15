<?php defined('_JEXEC') or die('Restricted access');
$group 	= PhocaDownloadHelper::getManagerGroup($this->manager);
echo '<div>';
echo '<a style="text-decoration:none" alt=".." href="index.php?option=com_phocadownload&amp;view=phocadownloadmanager&amp;manager='
	.$this->manager . $group['c'] .'&amp;folder='.$this->folderstate->parent .'&amp;field='. $this->field.'" >';
echo JHTML::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-up.png', JText::_('COM_PHOCADOWNLOAD_UP'));
echo '..</a></div>';