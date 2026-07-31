<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Helper Class.
 */
class MobilemenuckHelper {

	static $keepMessages = false;

	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '') {
		// don't add submenu in J4 as we do it in the admin menu in the database
		if (version_compare(JVERSION, '4') >= 0) return;

		$doc = \Mobilemenuck\CKFof::getDocument();
		$doc->addStyleSheet(MOBILEMENUCK_MEDIA_URI . '/assets/admin.css');
		$input = \Mobilemenuck\CKFof::getApplication()->input;
		if (! $vName) $vName = $input->get('view', 'items');
		JHtmlSidebar::addEntry(
				\Mobilemenuck\CKText::_('COM_MOBILEMENUCK_ITEMS'), 'index.php?option=com_mobilemenuck&view=items', $vName == 'items'
		);
		JHtmlSidebar::addEntry(
				\Mobilemenuck\CKText::_('COM_MOBILEMENUCK_STYLES'), 'index.php?option=com_mobilemenuck&view=styles', $vName == 'styles'
		);
		JHtmlSidebar::addEntry(
				\Mobilemenuck\CKText::_('CK_ABOUT') . '<span class="mobilemenuckchecking isbadgeck"></span>', 'index.php?option=com_mobilemenuck&view=about', $vName == 'about'
		);
		if ($input->get('tmpl', '') != 'component') echo '<div class="ckadminsidebar">' . JHtmlSidebar::render() . '</div>';
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	\Mobilemenuck\CKObject
	 * @since	1.6
	 */
	public static function getActions() {
		$user = \Joomla\CMS\Factory::getUser();
		$result = new \Mobilemenuck\CKObject;

		$assetName = 'com_mobilemenuck';

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
	public static function loadJqueryck() {
		$doc = \Mobilemenuck\CKFof::getDocument();
		$doc->addScript(MOBILEMENUCK_MEDIA_URI . '/assets/jqueryck.min.js');
	}

	/*
	 * Load the JS and CSS files needed to use CKBox
	 *
	 * Return void
	 */
	public static function loadCkbox() {
		$doc = \Mobilemenuck\CKFof::getDocument();
		self::loadJqueryck();
		// $doc->addScript(\Mobilemenuck\CKUri::root(true) . '/media/jui/js/jquery.min.js');
		$doc->addStyleSheet(MOBILEMENUCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(MOBILEMENUCK_MEDIA_URI . '/assets/ckbox.js');
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

	public static function getAjaxToken() {
		self::checkAjaxToken(); // TODO : remove calls in component
	}

	/**
	 * Check the token for security reason
	 * @return boolean
	 */
	public static function checkAjaxToken() {
		if (! \Joomla\CMS\Session\Session::checkToken('get')) {
			$msg = TCK_Text::_('CK_INVALID_TOKEN');
			echo '{"status": "0", "message": "' . $msg . '"}';
			exit();
		}
		return true;
	}

	public static function getToken() {
		return \Joomla\CMS\Session\Session::getFormToken();
	}

	public static function checkToken() {
		// Check for request forgeries.
		\Joomla\CMS\Session\Session::checkToken() or jexit(TCK_Text::_('JINVALID_TOKEN'));
	}

	public static function redirect($url, $msg = '', $type = '') {
		if ($msg) {
			self::enqueueMessage($msg, $type);
		}
		// If the headers have been sent, then we cannot send an additional location header
		// so we will output a javascript redirect statement.
		if (headers_sent())
		{
			self::$keepMessages = true;
			echo "<script>document.location.href='" . str_replace("'", '&apos;', $url) . "';</script>\n";
		}
		else
		{
			self::$keepMessages = true;
			// All other browsers, use the more efficient HTTP header method
			header('HTTP/1.1 303 See other');
			header('Location: ' . $url);
			header('Content-Type: text/html; charset=UTF-8');
		}
	}

	public static function enqueueMessage($msg, $type = 'message') {
		// add the information message
		$transient[] = Array("text" => CKText::_($msg), "type" => $type);
		set_transient( 'mobilemenuck_message', $transient, 60 );
	}

	public static function getStylesList() {
		$db = \Mobilemenuck\CKFof::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__mobilemenuck_styles')
			->where('state = 1');

		$db->setQuery($query);
		$list = $db->loadObjectList();
		$styles = array('0' => \Mobilemenuck\CKText::_('CK_NONE'));
		foreach ($list as $s) {
			$styles[$s->id] = $s->name;
		}

		return $styles;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	public static function testUnit($value, $defaultunit = "px") {
		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%')) OR $value == 'auto')
			return $value;

		return $value . $defaultunit;
	}
}
