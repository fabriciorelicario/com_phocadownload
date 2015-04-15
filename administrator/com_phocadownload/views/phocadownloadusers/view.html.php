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

jimport( 'joomla.filesystem.file' ); 
class PhocaDownloadCpViewPhocaDownloadUsers extends JView
{

	protected $items;
	protected $pagination;
	protected $state;
	protected $tmpl;
	
	
	function display($tpl = null) {
		
		$this->items			= $this->get('Items');
		$this->pagination		= $this->get('Pagination');
		$this->state			= $this->get('State');

		JHTML::stylesheet('administrator/components/com_phocadownload/assets/phocadownload.css' );

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/phocadownloadusers.php';
		$state	= $this->get('State');
		$canDo	= PhocaDownloadUsersHelper::getActions();
		
		JToolBarHelper::title( JText::_( 'COM_PHOCADOWNLOAD_USERS' ), 'users.png' );
		
		
		if ($canDo->get('core.admin')) {
			$bar = & JToolBar::getInstance('toolbar');
		$bar->appendButton( 'Custom', '<a href="#" onclick="javascript:if(confirm(\''.addslashes(JText::_('COM_PHOCADOWNLOAD_WARNING_AUTHORIZE_ALL')).'\')){submitbutton(\'phocadownloaduser.approveall\');}" class="toolbar"><span class="icon-32-authorizeall" title="'.JText::_('COM_PHOCADOWNLOAD_APPROVE_ALL').'" type="Custom"></span>'.JText::_('COM_PHOCADOWNLOAD_APPROVE_ALL').'</a>');	
			JToolBarHelper::divider();
		}
	
		
		JToolBarHelper::help( 'screen.phocadownload', true );
	}
}
?>