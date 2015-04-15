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
defined('_JEXEC') or die();
jimport( 'joomla.html.pane' );
jimport( 'joomla.application.component.view' );

class PhocaDownloadCpViewPhocaDownloadLayouts extends JView
{
	protected $items;
	
	function display($tpl = null) {
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadlayouts.php';
		$idString 	= PhocaDownloadLayoutsHelper::getTableId();
		$app		= JFactory::getApplication();
		$app->redirect(JRoute::_('index.php?option=com_phocadownload&view=phocadownloadlayout&task=phocadownloadlayout.edit'.$idString, false));
		return;
	}
}
?>