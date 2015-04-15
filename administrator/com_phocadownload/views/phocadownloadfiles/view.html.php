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
defined( '_JEXEC' ) or die();
jimport( 'joomla.application.component.view' );
 
class PhocaDownloadCpViewPhocaDownloadFiles extends JView
{

	protected $items;
	protected $pagination;
	protected $state;
	protected $tmpl;
	
	function display($tpl = null) {
		
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		
		JHTML::stylesheet('administrator/components/com_phocadownload/assets/phocadownload.css' );

		$this->tmpl['notapproved'] 	= & $this->get( 'NotApprovedFile' );
	
		$this->addToolbar();
		parent::display($tpl);
	}
	

	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.DS.'helpers'.DS.'phocadownloadfiles.php';

		$state	= $this->get('State');
		$canDo	= PhocaDownloadFilesHelper::getActions($state->get('filter.file_id'));
		
		JToolBarHelper::title( JText::_('COM_PHOCADOWNLOAD_FILES'), 'file.png' );
		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew( 'phocadownloadfile.add','JTOOLBAR_NEW');
			JToolBarHelper::addNew( 'phocadownloadfile.addtext','COM_PHOCADOWNLOAD_ADD_TEXT');
			JToolBarHelper::custom( 'phocadownloadm.edit', 'multiple.png', '', 'COM_PHOCADOWNLOAD_MULTIPLE_ADD' , false);
		}
		if ($canDo->get('core.edit')) {
			JToolBarHelper::editList('phocadownloadfile.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.create')) {
			//JToolBarHelper::divider();
			//JToolBarHelper::custom( 'phocadownloadfile.copyquick','copy.png', '', 'COM_PHOCADOWNLOAD_QUICK_COPY', true);
			//JToolBarHelper::custom( 'phocadownloadfile.copy','copy.png', '', 'COM_PHOCADOWNLOAD_COPY', true);
		}
		
		if ($canDo->get('core.edit.state')) {

			JToolBarHelper::divider();
			JToolBarHelper::custom('phocadownloadfiles.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('phocadownloadfiles.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::custom( 'phocadownloadfiles.approve', 'approve.png', '',  'COM_PHOCADOWNLOAD_APPROVE' , true);
			JToolBarHelper::custom( 'phocadownloadfiles.disapprove', 'disapprove.png', '',  'COM_PHOCADOWNLOAD_NOT_APPROVE' , true);
		}

		if ($canDo->get('core.delete')) {
			JToolBarHelper::deleteList( JText::_( 'COM_PHOCADOWNLOAD_WARNING_DELETE_ITEMS' ), 'phocadownloadfiles.delete', 'COM_PHOCADOWNLOAD_DELETE');
		}
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocadownload', true );
	}
	
}
?>