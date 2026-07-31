<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
namespace Mobilemenuck;
// No direct access
defined('_JEXEC') or die;

class Helper
{

	/**
	 * Render a html message
	 *
	 * @return  string
	 *
	 */
	public static function renderProMessage() {
		$html = '<div><a href="https://www.joomlack.fr/en/joomla-extensions/mobile-menu-ck" target="_blank">Not available in the free version</a></div>';
		return $html;
	}

	/**
	 * List the replacement between the tags and the real final CSS rules
	 */
	public static function getCssReplacement() {
		$cssreplacements = Array(
			'[menu-bar]' => ' .mobilemenuck-bar-title'
			,'[menu-bar-button]' => ' .mobilemenuck-bar-button'
			,'[menu]' => '.mobilemenuck'
			,'[menu-topbar]' => ' .mobilemenuck-title'
			,'[menu-topbar-button]' => ' .mobilemenuck-button'
			,'[level1menuitem]' => ' .mobilemenuck-item > .level1'
			,'[level1menuitemhover] a' => ' .mobilemenuck-item > .level1:not(.headingck):hover > *, |ID| .mobilemenuck-item > .level1.open > *'
			,'[level1menuitemhover]' => ' .mobilemenuck-item > .level1:not(.headingck):hover, |ID| .mobilemenuck-item > .level1.open'
			,'[level1menuitemactive]' => ' .mobilemenuck-item > .level1.active'
			,'[level1submenu]' => ' .mobilemenuck-item > .level1 + .mobilemenuck-submenu'
			,'[level2menuitem]' => ' .mobilemenuck-item > .level2'
			,'[level2menuitemhover] a' => ' .mobilemenuck-item > .level2:not(.headingck):hover > *, |ID| .mobilemenuck-item > .level2.open > *'
			,'[level2menuitemhover]' => ' .mobilemenuck-item > .level2:not(.headingck):hover, |ID| .mobilemenuck-item > .level2.open'
			,'[level2menuitemactive]' => ' .mobilemenuck-item > .level2.active'
			,'[level2submenu]' => ' .mobilemenuck-item > .level2 + .mobilemenuck-submenu'
			,'[level3menuitem]' => ' .level2 + .mobilemenuck-submenu .mobilemenuck-item > div:not(.mobilemenuck-submenu)'
			,'[level3menuitemhover] a' => ' .level2 + .mobilemenuck-submenu .mobilemenuck-item > div:not(.headingck):not(.mobilemenuck-submenu):hover > *, |ID| .mobilemenuck-item > .level2 + .mobilemenuck-submenu .mobilemenuck-item > div.open:not(.mobilemenuck-submenu) >* '
			,'[level3menuitemhover]' => ' .level2 + .mobilemenuck-submenu .mobilemenuck-item > div:not(.headingck):not(.mobilemenuck-submenu):hover, |ID| .mobilemenuck-item > .level2 + .mobilemenuck-submenu .mobilemenuck-item > div.open:not(.mobilemenuck-submenu)'
			,'[level3menuitemactive]' => ' .level2 + .mobilemenuck-submenu .mobilemenuck-item > div.active'
			,'[level3submenu]' => ' .mobilemenuck-item > .level2 .mobilemenuck-submenu'
			,'[togglericon]' => ' .mobilemenuck-togglericon:after'
			,'[heading]' => ' .mobilemenuck-item > .headingck'
			,'[PRESETS_URI]' => MOBILEMENUCK_MEDIA_URI . '/presets'
		);

		return $cssreplacements;
	}

	/**
	 * Do the replacement between the tags and the real final CSS rules
	 */
	public static function makeCssReplacement(&$css) {
		$cssreplacementlist = self::getCssReplacement();
		foreach ($cssreplacementlist as $tag => $rep) {
			$css = str_replace($tag, $rep, $css);
		}
	}

	/**
	 * Get the name of the style
	 */
	public static function getStyleNameById($id) {
		if (! $id) return '';
		// Create a new query object.
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.name');
		$query->from($db->quoteName('#__mobilemenuck_styles') . ' AS a');
		$query->where('(a.state IN (0, 1))');
		$query->where('a.id = ' . (int)$id);

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$result = $db->loadResult();

		return $result;
	}

	/**
	 * Get the name of the style
	 */
	public static function getStyleById($id, $select = '*', $type = 'result') {
		if (! $id) return '';
		// Create a new query object.
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($select);
		$query->from($db->quoteName('#__mobilemenuck_styles') . ' AS a');
		$query->where('(a.state IN (0, 1))');
		$query->where('a.id = ' . (int)$id);

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		switch($type) {
			default :
			case "result" :
				$result = $db->loadResult();
			break;
			case "object" :
				$result = $db->loadObject();
			break;
		}

		return $result;
	}

	/**
	 * Get the name of the style
	 */
	public static function getStyles($select = '*') {
		// Create a new query object.
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($select);
		$query->from($db->quoteName('#__mobilemenuck_styles') . ' AS a');
		$query->where('(a.state IN (0, 1))');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}
	/**
	 * Get the name of the style
	 */
	public static function getModuleById($id, $select = '*') {
		if (! $id) return '';
		// Create a new query object.
		$db = \Joomla\CMS\Factory::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select($select);
		$query->from($db->quoteName('#__modules') . ' AS a');
		// $query->where('(a.published IN (0, 1))');
		$query->where('a.id = ' . (int)$id);

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$result = $db->loadObject();

		return $result;
	}

	/**
	 * Look if the pro version is installed
	 * 
	 * @return  boolean
	 */
	public static function checkIsProVersion() {
		return self::searchTable('mobilemenuck_styles') && file_exists(JPATH_ROOT . '/administrator/components/com_mobilemenuck/mobilemenuck.php');
	}

	/**
	 * Look if the table exists, if not then create it
	 * 
	 * @param type $tableName
	 * @return  boolean
	 */
	private static function searchTable($tableName) {
		$db = \Joomla\CMS\Factory::getDbo();

		$tablesList = $db->getTableList();
		$tableExists = in_array($db->getPrefix() . $tableName, $tablesList);

		return $tableExists;
	}

	public static function createIdForModule($module) {
		if ($module->module == 'mod_maximenuck') {
			$params = new \Joomla\Registry\Registry($module->params);
			if ($params->get('menuid', '') === '' || is_numeric($params->get('menuid', ''))) {
				$id = 'maximenuck' . $module->id;
			} else {
				$id = $params->get('menuid', '');
			}
		} else if ($module->module == 'mod_accordeonmenuck') {
			$params = new \Joomla\Registry\Registry($module->params);
			if ($params->get('menuid', '') === '' || is_numeric($params->get('menuid', ''))) {
				$id = 'accordeonck' . $module->id;
			} else {
				$id = $params->get('menuid', '');
			}
		} else {
			$id = 'mobilemenuck-' . $module->id;
		}
		return $id;
	}

	public static function getLayoutCss() {
		$doc = \Joomla\CMS\Factory::getDocument();
		$overrideSrc = JPATH_ROOT . '/templates/' . $doc->template . '/css/mobilemenuck.css';
		$overrideSrc2 = JPATH_ROOT . '/media/templates/site/' . $template . '/css/mobilemenuck.css';
		if (file_exists($overrideSrc)) {
			$layoutcss = file_get_contents($overrideSrc);
		} else if (file_exists($overrideSrc2)) {
			$layoutcss = file_get_contents($overrideSrc2);
		} else {
			$layoutcss = file_get_contents(MOBILEMENUCK_PATH . '/default.txt');
		}

		return $layoutcss;
	}
}