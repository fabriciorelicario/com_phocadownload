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

jimport('joomla.application.component.controller');


$l['cp']		= array('COM_PHOCADOWNLOAD_CONTROL_PANEL', '');
$l['f']			= array('COM_PHOCADOWNLOAD_FILES', 'phocadownloadfiles');
$l['c']			= array('COM_PHOCADOWNLOAD_CATEGORIES', 'phocadownloadcats');
$l['l']			= array('COM_PHOCADOWNLOAD_LICENSES', 'phocadownloadlics');
$l['st']		= array('COM_PHOCADOWNLOAD_STATISTICS', 'phocadownloadstat');
$l['u']			= array('COM_PHOCADOWNLOAD_USERS', 'phocadownloadusers');
$l['fr']		= array('COM_PHOCADOWNLOAD_FILE_RATING', 'phocadownloadrafile');
$l['t']			= array('COM_PHOCADOWNLOAD_TAGS', 'phocadownloadtags');
$l['ly']		= array('COM_PHOCADOWNLOAD_LAYOUT', 'phocadownloadlayouts');
$l['in']		= array('COM_PHOCADOWNLOAD_INFO', 'phocadownloadinfo');

// Submenu view
$view	= JRequest::getVar( 'view', '', '', 'string', JREQUEST_ALLOWRAW );

foreach ($l as $k => $v) {
	
	if ($v[1] == '') {
		$link = 'index.php?option=com_phocadownload';
	} else {
		$link = 'index.php?option=com_phocadownload&view=';
	}

	if ($view == $v[1]) {
		JSubMenuHelper::addEntry(JText::_($v[0]), $link.$v[1], true );
	} else {
		JSubMenuHelper::addEntry(JText::_($v[0]), $link.$v[1]);
	}

}

class PhocadownloadCpController extends JController {
	function display() {
		parent::display();
	}
}
?>
