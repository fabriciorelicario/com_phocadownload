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
defined('_JEXEC') or die;

class PhocaDownloadEntitleHelper
{
	public static function getActions($entitleId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($entitleId)) {
			$assetName = 'com_phocadownload';
		} else {
			$assetName = 'com_phocadownload.phocadownloadentitles.'.(int) $entitleId;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}