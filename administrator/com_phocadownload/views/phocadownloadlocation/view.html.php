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

class PhocaDownloadCpViewPhocaDownloadLocation extends JView
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
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadlocations.php';
		JRequest::setVar('hidemainmenu', true);
		$bar 		= JToolBar::getInstance('toolbar');
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));

		$canDo		= PhocaDownloadLocationsHelper::getActions($this->state->get('filter.id'), $this->item->id);
                $paramsC 	= JComponentHelper::getParams('com_phocadownload');

		

		$text = $isNew ? JText::_( 'COM_PHOCADOWNLOAD_NEW' ) : JText::_('COM_PHOCADOWNLOAD_EDIT');
		JToolBarHelper::title(   JText::_( 'COM_PHOCADOWNLOAD_LOCATION' ).': <small><small>[ ' . $text.' ]</small></small>' , 'globe');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolBarHelper::apply('phocadownloadlocation.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('phocadownloadlocation.save', 'JTOOLBAR_SAVE');
			JToolBarHelper::addNew('phocadownloadlocation.save2new', 'JTOOLBAR_SAVE_AND_NEW');
		
		}
		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			//JToolBarHelper::custom('phocadownloadc.save2copy', 'copy.png', 'copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		}
		if (empty($this->item->id))  {
			JToolBarHelper::cancel('phocadownloadlocation.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolBarHelper::cancel('phocadownloadlocation.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocadownload', true );
	}
}
?>
