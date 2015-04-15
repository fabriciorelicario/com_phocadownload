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
if (!JFactory::getUser()->authorise('core.manage', 'com_phocadownload')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocadownload.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadtag.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'html'.DS.'category.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'html'.DS.'grid.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'html'.DS.'batch.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadcp.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'fileupload.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'fileuploadmultiple.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'fileuploadsingle.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadrate.php' );
jimport('joomla.application.component.controller');
$controller	= JController::getInstance('PhocaDownloadCp');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();
?>