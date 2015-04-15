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
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport( 'joomla.filesystem.folder' );

function com_uninstall() {
	$folder[0][0]	=	'phocadownload'  ;
	$folder[0][1]	= 	JPATH_ROOT . DS .  $folder[0][0];
	
	$folder[1][0]	=	'images' . DS . 'phocadownload'  ;
	$folder[1][1]	= 	JPATH_ROOT . DS .  $folder[1][0];
	
	$folder[2][0]	=	'phocadownload' . DS .'userupload';
	$folder[2][1]	= 	JPATH_ROOT . DS .  $folder[2][0];
	
	$folder[3][0]	=	'phocadownloadpap';
	$folder[3][1]	= 	JPATH_ROOT . DS .  $folder[3][0];
	
	$message = '';
	$error	 = array();
	foreach ($folder as $key => $value) {
		if (JFolder::exists( $value[1])) {
			$message .= '<p><b><span style="color:#009933">Folder</span> ' . $value[0] 
					   .' <span style="color:#009933">still exists!</span></b></p>';
		}
	}
	if ($message !='') {
		$message .= '<p>Please delete it (them) manually, if you want.</p>';
	}
	echo $message;
}
?>