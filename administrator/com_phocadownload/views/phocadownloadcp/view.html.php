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
jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.pane' );

class PhocaDownloadCpViewPhocaDownloadcp extends JView
{
	public $tmpl;
	function display($tpl = null) {
		
		JHtml::stylesheet( 'administrator/components/com_phocadownload/assets/phocadownload.css' );
		JHTML::_('behavior.tooltip');
		$this->tmpl['version'] = PhocaDownloadHelper::getPhocaVersion();
		$this->assignRef('version',	$version);
		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	protected function addToolbar() {
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadcp.php';

		$state	= $this->get('State');
		$canDo	= PhocaDownloadCpHelper::getActions();
		JToolBarHelper::title( JText::_( 'COM_PHOCADOWNLOAD_PD_CONTROL_PANEL' ), 'phocadownload.png' );
		
		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_phocadownload');
			JToolBarHelper::divider();
		}
		
		JToolBarHelper::help( 'screen.phocadownload', true );
	}
}
?>