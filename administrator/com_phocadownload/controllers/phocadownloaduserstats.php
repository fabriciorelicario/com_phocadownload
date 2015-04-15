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
jimport('joomla.application.component.controllerform');

class PhocaDownloadCpControllerPhocaDownloadUserStats extends JControllerForm
{
	protected	$option 		= 'com_phocadownload';
	
	
	
	public function &getModel($name = 'PhocaDownloadUserStats', $prefix = 'PhocaDownloadCpModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	function cancel() {
		$model = $this->getModel( 'phocadownload' );
		$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloadfiles' );
	}

	
	function reset() {
		
		$post					= JRequest::get('post');
		$cid					= JRequest::getVar( 'cid', array(0), 'post', 'array' );
		$idFile					= JRequest::getVar( 'idfile', 0, 'post', 'int' );

		$model = $this->getModel( 'phocadownloaduserstats' );

		if ($model->reset($cid)) {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_SUCCESS_RESET_USER_STAT' );
		} else {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_ERROR_RESET_USER_STAT' );
		}
		
		$link = 'index.php?option=com_phocadownload&view=phocadownloaduserstats&id='.(int)$idFile;
		$this->setRedirect($link, $msg);
	}
}
?>
