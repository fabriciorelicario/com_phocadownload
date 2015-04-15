<?php defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

if ($this->manager == 'filemultiple') {

	?><script language="javascript" type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		
		if (task == 'phocadownloadm.cancel') {
			submitform(task);
		}

		if (task == 'phocadownloadm.save') {
			phocadownloadmform = document.getElementById('adminForm');
			if (phocadownloadmform.boxchecked.value==0) {
				alert( "<?php echo JText::_( 'COM_PHOCADOWNLOAD_WARNING_SELECT_FILENAME_OR_FOLDER', true ); ?>" );
			} else  {
				var f = phocadownloadmform;
				var nSelectedImages = 0;
				var nSelectedFolders = 0;
				var i=0;
				cb = eval( 'f.cb' + i );
				while (cb) {
					if (cb.checked == false) {
						// Do nothing
					}
					else if (cb.name == "cid[]") {
						nSelectedImages++;
					}
					else {
						nSelectedFolders++;
					}
					// Get next
					i++;
					cb = eval( 'f.cb' + i );
				}
				
				if (phocadownloadmform.jform_catid.value == "" && nSelectedImages > 0){
					alert( "<?php echo JText::_( 'COM_PHOCADOWNLOAD_WARNING_FILE_SELECTED_SELECT_CATEGORY', true ); ?>" );
				} else {
					submitform(task);
				}
			}
		}
		//submitform(task);
	}
	</script><?php
}

echo '<div id="phocadownloadmanager">';

if ($this->manager == 'filemultiple') {
	echo '<form action="'.JRoute::_('index.php?option=com_phocadownload').'" method="post" name="adminForm" id="adminForm" class="form-validate">';
	echo '<div class="width-100 fltlft">';
	echo '<fieldset class="adminform">';
	echo '<legend>'. JText::_('COM_PHOCADOWNLOAD_MULTIPLE_ADD').'</legend>';
	echo '<ul class="adminformlist">';
	$formArray = array ('title', 'alias','published', 'approved', 'ordering', 'catid', 'language');
		foreach ($formArray as $value) {
			echo '<li>'.$this->form->getLabel($value) . $this->form->getInput($value).'</li>' . "\n";
		}
	echo '</ul></fieldset></div>';
	echo '<div class="clr"></div>';
}


echo '<div class="pd-admin-path">' . JText::_('COM_PHOCADOWNLOAD_PATH'). ': '.JPath::clean($this->tmpl['path']['orig_abs_ds']. $this->folderstate->folder) .'</div>';

echo '<div class="pd-admin-files">';

if ($this->manager == 'filemultiple' && (count($this->files) > 0 || count($this->folders) > 0)) {
	echo '<div class="pd-admin-file-checkbox">';
	$fileFolders = count($this->files) + count($this->folders);
	echo '<input type="checkbox" name="toggle" value="" onclick="checkAll('.$fileFolders.');" />';
	echo '&nbsp;&nbsp;'. JText::_('COM_PHOCADOWNLOAD_CHECK_ALL');
	echo '</div>';
}

echo $this->loadTemplate('up');
if (count($this->files) > 0 || count($this->folders) > 0) { ?>
<div>

	<?php for ($i=0,$n=count($this->folders); $i<$n; $i++) :
		$this->setFolder($i);
		$this->folderi = $i;
		echo $this->loadTemplate('folder');
	endfor; ?>

	<?php for ($i=0,$n=count($this->files); $i<$n; $i++) :
		$this->setFile($i);
		$this->filei = $i;
		echo $this->loadTemplate('file');
	endfor; ?>

</div>
<?php } else { ?>
<div>
	<center style="clear:both;font-size:large;font-weight:bold;color:#b3b3b3;font-family: Helvetica, sans-serif;">
		<?php echo JText::_( 'COM_PHOCADOWNLOAD_THERE_IS_NO_FILE' ); ?>
	</center>
</div>
<?php }
echo '</div>';

if ($this->manager == 'filemultiple') {
	?>
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="layout" value="edit" />
	<?php echo JHtml::_('form.token'); ?>
	</form>
	<?php
} ?>

<div style="border-bottom:1px solid #cccccc;margin-bottom: 10px">&nbsp;</div>

<?php
if ($this->tmpl['displaytabs'] > 0) {
	echo '<div id="phocadownload-pane">';
	//$pane =& J Pane::getInstance('Tabs', array('startOffset'=> $this->tmpl['tab']));
	//echo $pane->startPane( 'pane' );
	echo JHtml::_('tabs.start', 'config-tabs-com_phocadownload-manager', array('useCookie'=>1, 'startOffset'=> $this->tmpl['tab']));

	//echo $pane->startPanel( JHTML::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-16-upload.png','') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'upload' );
	//echo $this->loadTemplate('upload');
	//echo $pane->endPanel();
	
	echo JHtml::_('tabs.panel', JHtml::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-16-upload.png', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_UPLOAD'), 'upload' );
	echo $this->loadTemplate('upload');
	
	
	if((int)$this->tmpl['enablemultiple']  >= 0) {
		//echo $pane->startPanel( JHTML::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-16-upload-multiple.png','') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_MULTIPLE_UPLOAD'), 'multipleupload' );
		//echo $this->loadTemplate('multipleupload');
		//echo $pane->endPanel();
		echo JHtml::_('tabs.panel', JHtml::_( 'image', 'administrator/components/com_phocadownload/assets/images/icon-16-upload-multiple.png', '') . '&nbsp;'.JText::_('COM_PHOCADOWNLOAD_MULTIPLE_UPLOAD'), 'multipleupload' );
		echo $this->loadTemplate('multipleupload');
	}


	//echo $pane->endPane();
	echo JHtml::_('tabs.end');
	echo '</div>';// end phocadownload-pane
}
echo '</div>';
?>
