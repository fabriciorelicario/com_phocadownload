<?php defined('_JEXEC') or die('Restricted access');?>

<form action="index.php" method="post" name="adminForm">
<div class="adminform">
<div class="cpanel-left">
	<div id="cpanel">
		<?php

		
		$link = 'index.php?option=com_phocadownload&view=phocadownloadfiles';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-file.png', JText::_( 'COM_PHOCADOWNLOAD_FILES' ) );

		$link = 'index.php?option=com_phocadownload&view=phocadownloadcats';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-cat.png', JText::_( 'COM_PHOCADOWNLOAD_CATEGORIES' ) );
		$link = 'index.php?option=com_phocadownload&view=phocadownloadlics';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-lic.png', JText::_( 'COM_PHOCADOWNLOAD_LICENSES' ) );

		$link = 'index.php?option=com_phocadownload&view=phocadownloadstat';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-stat.png', JText::_( 'COM_PHOCADOWNLOAD_STATISTICS' ) );
		$link = 'index.php?option=com_phocadownload&view=phocadownloadusers';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-users.png', JText::_( 'COM_PHOCADOWNLOAD_USERS' ) );
		$link = 'index.php?option=com_phocadownload&view=phocadownloadrafile';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-vote-file.png', JText::_( 'COM_PHOCADOWNLOAD_FILE_RATING' ) );
		
		$link = 'index.php?option=com_phocadownload&view=phocadownloadtags';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-tags.png', JText::_( 'COM_PHOCADOWNLOAD_TAGS' ) );
		$link = 'index.php?option=com_phocadownload&view=phocadownloadlayouts';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-layout.png', JText::_( 'COM_PHOCADOWNLOAD_LAYOUT' ) );
		
		$link = 'index.php?option=com_phocadownload&view=phocadownloadinfo';
		echo PhocaDownloadCpHelper::quickIconButton( $link, 'icon-48-info.png', JText::_( 'COM_PHOCADOWNLOAD_INFO' ) );
		?>
				
		<div style="clear:both">&nbsp;</div>
		<p>&nbsp;</p>
		<div style="text-align:center;padding:0;margin:0;border:0">
			<iframe style="padding:0;margin:0;border:0" src="http://www.phoca.cz/adv/phocadownload" noresize="noresize" frameborder="0" border="0" cellspacing="0" scrolling="no" width="500" marginwidth="0" marginheight="0" height="125">
			<a href="http://www.phoca.cz/adv/phocadownload" target="_blank">Phoca Download</a>
			</iframe> 
		</div>
	</div>
</div>
		
<div class="cpanel-right">
	<div style="border:1px solid #ccc;background:#fff;margin:15px;padding:15px">
		<div style="float:right;margin:10px;">
			<?php echo JHTML::_('image', 'administrator/components/com_phocadownload/assets/images/logo-phoca.png', 'Phoca.cz' );?>
		</div>
			
		<?php
		echo '<h3>'.  JText::_('COM_PHOCADOWNLOAD_VERSION').'</h3>'
		.'<p>'.  $this->tmpl['version'] .'</p>';

		echo '<h3>'.  JText::_('COM_PHOCADOWNLOAD_COPYRIGHT').'</h3>'
		.'<p>© 2007 - '.  date("Y"). ' Jan Pavelka</p>'
		.'<p><a href="http://www.phoca.cz/" target="_blank">www.phoca.cz</a></p>';

		echo '<h3>'.  JText::_('COM_PHOCADOWNLOAD_LICENSE').'</h3>'
		.'<p><a href="http://www.gnu.org/licenses/gpl-2.0.html" target="_blank">GPLv2</a></p>';
		
		echo '<h3>'.  JText::_('COM_PHOCADOWNLOAD_TRANSLATION').': '. JText::_('COM_PHOCADOWNLOAD_TRANSLATION_LANGUAGE_TAG').'</h3>'
        .'<p>© 2007 - '.  date("Y"). ' '. JText::_('COM_PHOCADOWNLOAD_TRANSLATER'). '</p>'
        .'<p>'.JText::_('COM_PHOCADOWNLOAD_TRANSLATION_SUPPORT_URL').'</p>';
		
		echo '<div style="border-top:1px solid #c2c2c2"></div>'
.'<div id="pg-update"><a href="http://www.phoca.cz/version/index.php?phocadownload='.  $this->tmpl['version'] .'" target="_blank">'.  JText::_('COM_PHOCADOWNLOAD_CHECK_FOR_UPDATE') .'</a></div>';
		
	
		?>
		
	</div>
</div>

</div>

<input type="hidden" name="option" value="com_phocadownload" />
<input type="hidden" name="view" value="phocadownloadcp" />
<input type="hidden" name="<?php echo JUtility::getToken(); ?>" value="1" />
</form>
