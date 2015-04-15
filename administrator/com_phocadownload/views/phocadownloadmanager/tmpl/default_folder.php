<?php defined('_JEXEC') or die('Restricted access');
$group 	= PhocaDownloadHelper::getManagerGroup($this->manager);

if ($this->manager == 'filemultiple') {
	$checked 	= JHTML::_('grid.id', $this->folderi, $this->folders[$this->folderi]->path_with_name_relative_no, 0, 'foldercid' );
	echo '<div class="pd-admin-file">';
	echo $checked . '&nbsp;';
	echo JHTML::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-folder.png', ''). '&nbsp;';
	echo '<a href="index.php?option=com_phocadownload&amp;view=phocadownloadmanager'
		 .'&amp;manager='.$this->manager
		 .$group['c']
		 .'&amp;folder='.$this->_tmp_folder->path_with_name_relative_no
		 .'&amp;field='. $this->field.'">';
	echo $this->_tmp_folder->name;
	echo '</a>';
	echo '</div>';
} else {
	echo '<div>';
	echo '<a href="index.php?option=com_phocadownload&amp;view=phocadownloadmanager'
		 .'&amp;manager='. $this->manager
		 . $group['c']
		 .'&amp;folder='.$this->_tmp_folder->path_with_name_relative_no
		 .'&amp;field='. $this->field.'">';
		 
	echo JHTML::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-folder.png', JText::_('COM_PHOCADOWNLOAD_OPEN'));
	 
	echo '</a> ';
	echo '<a href="index.php?option=com_phocadownload&amp;view=phocadownloadmanager'
		 .'&amp;manager='.$this->manager
		 .$group['c']
		 .'&amp;folder='.$this->_tmp_folder->path_with_name_relative_no
		 .'&amp;field='. $this->field.'">';
	echo $this->_tmp_folder->name;
	echo '</a>';
	echo '</div>'."\n";
}
