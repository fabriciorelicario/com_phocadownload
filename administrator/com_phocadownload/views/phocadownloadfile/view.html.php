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

class PhocaDownloadCpViewPhocaDownloadFile extends JView
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
		
		if (isset($this->item->textonly) && (int)$this->item->textonly == 1 && JRequest::getVar('layout') != 'edit_text') {
			$tpl = 'text';
		}
		
		/*$this->item->selectedtags = array();
		//$this->item->alltags = PhocaDownloadTagHelper::getAllTags();
		if (isset($this->item->id) && (int)$this->item->id > 0) {
			$this->item->selectedtags = PhocaDownloadTagHelper::getTags((int)$this->item->id);
		}
		*/

		$this->addToolbar();
		parent::display($tpl);
	}
	
	
	
	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadfiles.php';
		JRequest::setVar('hidemainmenu', true);
		$bar 		= JToolBar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= PhocaDownloadFilesHelper::getActions($this->state->get('filter.file_id'), $this->item->id);
		$paramsC 	= JComponentHelper::getParams('com_phocadownload');

		

		$text = $isNew ? JText::_( 'COM_PHOCADOWNLOAD_NEW' ) : JText::_('COM_PHOCADOWNLOAD_EDIT');
		JToolBarHelper::title(   JText::_( 'COM_PHOCADOWNLOAD_FILE' ).': <small><small>[ ' . $text.' ]</small></small>' , 'file');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolBarHelper::apply('phocadownloadfile.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('phocadownloadfile.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::addNew('phocadownloadfile.save2new', 'JTOOLBAR_SAVE_AND_NEW');
		
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			//JToolBarHelper::custom('phocadownloadc.save2copy', 'copy.png', 'copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('phocadownloadfile.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('phocadownloadfile.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocadownload', true );
	}
}
?>
