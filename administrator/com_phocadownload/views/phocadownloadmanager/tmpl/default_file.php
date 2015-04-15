<?php defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.filesystem.file' );

$ext 	= PhocaDownloadHelper::getExtension( $this->_tmp_file->path_without_name_relative );
$group 	= PhocaDownloadHelper::getManagerGroup($this->manager);


if ($this->manager == 'filemultiple') {
	$checked 	= JHTML::_('grid.id', $this->filei + count($this->folders), $this->files[$this->filei]->path_with_name_relative_no );
	echo '<div class="pd-admin-file">';
	echo $checked . '&nbsp;';
	echo JHTML::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-file.png', ''). '&nbsp;';
	echo $this->_tmp_file->name;
	echo '</div>';
} else {
	if (($group['i'] == 1) && ($ext == 'png' || $ext == 'jpg' || $ext == 'gif' || $ext == 'jpeg') ) {
		
		echo '<div>';
		echo '<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">';
		echo JHTML::_( 'image', str_replace( '../', '', $this->_tmp_file->path_without_name_relative), JText::_('COM_PHOCADOWNLOAD_INSERT'), array('title' => JText::_('COM_PHOCADOWNLOAD_INSERT_ICON')));
		echo '</a> ';
		echo '<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' . $this->_tmp_file->path_with_name_relative_no.'\')">';
		echo $this->_tmp_file->name;
		echo '</a>';
		echo '</div>';
		
	} else {
		
		echo '<div>';
		echo '<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">';
		echo JHTML::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-file.png', '', array('title' => JText::_('COM_PHOCADOWNLOAD_INSERT_FILENAME'), 'title="'.JText::_('COM_PHOCADOWNLOAD_INSERT')));
		echo '</a> ';
		echo '<a href="#" onclick="if (window.parent) window.parent.'. $this->fce.'(\'' .$this->_tmp_file->path_with_name_relative_no.'\')">';
		echo $this->_tmp_file->name;
		echo '</a>';
		echo '</div>';
	}
}
?>
