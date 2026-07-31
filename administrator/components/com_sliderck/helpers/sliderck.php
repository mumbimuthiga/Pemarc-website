<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Helper Class.
 */
class SliderckHelper {

	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '') {
		$doc = \Sliderck\CKFof::getDocument();
		$doc->addStyleSheet(SLIDERCK_MEDIA_URI . '/assets/sliderck.css');
		$input = \Sliderck\CKFof::getApplication()->input;
		if (! $vName) $vName = $input->get('view', 'about');
		JSubMenuHelper::addEntry(
				\Sliderck\CKText::_('COM_SLIDERCK_MODULES'), 'index.php?option=com_sliderck&view=modules', $vName == 'modules'
		);
		JSubMenuHelper::addEntry(
				\Sliderck\CKText::_('COM_SLIDERCK_STYLES'), 'index.php?option=com_sliderck&view=styles', $vName == 'styles'
		);
		JSubMenuHelper::addEntry(
				\Sliderck\CKText::_('CK_ABOUT') . '<span class="sliderckchecking isbadgeck"></span>', 'index.php?option=com_sliderck&view=about', $vName == 'about'
		);
		// JSubMenuHelper::addEntry(
				// \Sliderck\CKText::_('COM_SLIDERCK_HELP'), 'index.php?option=com_sliderck&view=help', $vName == 'help'
		// );
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	\Sliderck\CKObject
	 * @since	1.6
	 */
	public static function getActions() {
		$user = \Joomla\CMS\Factory::getUser();
		$result = new \Sliderck\CKObject;

		$assetName = 'com_sliderck';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadCkbox() {
		$doc = \Sliderck\CKFof::getDocument();
		$doc->addScript(\Sliderck\CKUri::root(true) . '/media/jui/js/jquery.min.js');
		$doc->addStyleSheet(SLIDERCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/ckbox.js');
	}
	
	/*
	 * Remove special character
	 */
	public static function cleanName($path) {
		return preg_replace('/[^a-z0-9]/i', '_', $path);
	}

	/*
	 * Format the path to use only /
	 */
	public static function formatPath($p) {
			return trim(str_replace("\\", "/", $p), "/");
	}
	
	/**
	 * Get the name of the style
	 */
	public static function getStyleNameById($id) {
		if (! $id) return '';
		// Create a new query object.
		$db = \Sliderck\CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.name');
		$query->from($db->quoteName('#__sliderck_styles') . ' AS a');
		$query->where('(a.state IN (0, 1))');
		$query->where('a.id = ' . (int)$id);

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadResult();

		return $results;
	}
	
	public static function getAjaxToken() {
		// check the token for security
		if (! \Joomla\CMS\Session\Session::checkToken('get')) {
			$msg = \Sliderck\CKText::_('JINVALID_TOKEN');
			echo '{"result": "0", "message": "' . $msg . '"}';
			return false;
		}
		return true;
	}
}
