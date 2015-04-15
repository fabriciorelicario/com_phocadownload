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
 
class PhocaDownloadCpViewPhocaDownloadStat extends JView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $maxandsum;
	
	function display($tpl = null) {
		
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->maxandsum	= $this->get('MaxAndSum');
		

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
	
		require_once JPATH_COMPONENT.'/helpers/phocadownloadstat.php';
	
		$state	= $this->get('State');
		$canDo	= PhocaDownloadStatHelper::getActions();
	
		JToolBarHelper::title( JText::_( 'COM_PHOCADOWNLOAD_STATISTICS' ), 'license.png' );
	
		JToolBarHelper::cancel('phocadownloadlic.cancel', 'JTOOLBAR_CLOSE');
		
		JToolBarHelper::divider();
		JToolBarHelper::help( 'screen.phocadownload', true );
	}
}
?>