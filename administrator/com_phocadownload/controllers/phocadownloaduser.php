<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Download
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();

class PhocaDownloadCpControllerPhocaDownloadUser extends PhocaDownloadCpController
{
	function __construct() {
		parent::__construct();
		$this->registerTask( 'approveall', 'approveall');		
	}
	
	function approveall() {

		$model = $this->getModel('phocadownloaduser');
		if(!$model->approveall()) {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_ERROR_APPROVE_ALL' );
		} else {
			$msg = JText::_( 'COM_PHOCADOWNLOAD_ALL_APPROVED' );
		}

		$this->setRedirect( 'index.php?option=com_phocadownload&view=phocadownloadusers' , $msg);
	}
}
?>
