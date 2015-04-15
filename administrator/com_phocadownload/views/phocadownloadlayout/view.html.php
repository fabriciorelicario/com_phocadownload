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

class PhocaDownloadCpViewPhocaDownloadLayout extends JView
{
	protected $state;
	protected $item;
	protected $form;
	protected $tmpl;

	public function display($tpl = null) {
		
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		
		JHTML::stylesheet('administrator/components/com_phocadownload/assets/phocadownload.css' );

		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
	
		$this->state		= $this->get('State');
		
		JRequest::setVar('hidemainmenu', 1);
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadlayouts.php';
		//$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo	= PhocaDownloadLayoutsHelper::getActions();
		
		JToolBarHelper::title(   JText::_( 'COM_PHOCADOWNLOAD_LAYOUT' ), 'layout' );
		
		$bar = & JToolBar::getInstance( 'toolbar' );
		//$bar->appendButton( 'Link', 'back', 'COM_PHOCADOWNLOOAD_CONTROL_PANEL', 'index.php?option=com_phocadownload' );
		
		JToolBarHelper::custom('phocadownloadlayout.back', 'back', '', 'COM_PHOCADOWNLOOAD_CONTROL_PANEL', false);
		//JToolBarHelper::cancel('phocadownloadlayout.cancel', 'JTOOLBAR_CANCEL');
		
		
		if ($canDo->get('core.edit')) {
			JToolBarHelper::apply('phocadownloadlayout.apply', 'JTOOLBAR_APPLY');
			//JToolBarHelper::save('phocapdfplugin.save', 'JTOOLBAR_SAVE');
		}
		JToolBarHelper::divider();
		
		JToolBarHelper::help( 'screen.phocadownload', true );
		
	}
}
?>
