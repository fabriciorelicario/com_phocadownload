<?php 
defined('_JEXEC') or die('Restricted access');
echo '<div id="phocadownload-upload">';
echo '<div style="font-size:1px;height:1px;margin:0px;padding:0px;">&nbsp;</div>';
echo '<form action="'. $this->tmpl['su_url'] .'" id="uploadFormU" method="post" enctype="multipart/form-data">';
if ($this->tmpl['ftp']) { echo PhocaDownloadFileUpload::renderFTPaccess();}  
echo '<fieldset class="actions">'
	.' <legend>'; 
echo JText::_( 'COM_PHOCADOWNLOAD_UPLOAD_FILE' ).' [ '. JText::_( 'COM_PHOCADOWNLOAD_MAX_SIZE' ).':&nbsp;'.$this->tmpl['uploadmaxsizeread'].']';
echo ' </legend>';
echo $this->tmpl['su_output']
	.'</fieldset>';
echo '</form>';	 
echo PhocaDownloadFileUpload::renderCreateFolder($this->session->getName(), $this->session->getId(), $this->currentFolder, 'phocadownloadmanager', 'manager='.$this->manager.'&amp;tab='.$this->tmpl['currenttab']['upload'].'&amp;field='. $this->field );
echo '</div>';