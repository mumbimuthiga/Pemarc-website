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

require_once JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/defines.php';

use Sliderck\CKInput;
use Sliderck\CKFof;
use Sliderck\CKText;
use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\Component\Content\Site\Helper\AssciationHelper;

/**
 * Helper Class.
 */
class SliderckHelper {

	static $releaseNotes;

	static $currentVersion;

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

	public static function addSidebar($vName = '') {
		$input = CKFof::getInput();
		if (!$vName) $vName = $input->get('view', 'modules', 'cmd');

		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
				CKText::_('COM_SLIDERCK_MODULES'), SLIDERCK_ADMIN_URL . '&view=modules', $vName == 'modules'
		);
		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
				CKText::_('CK_STYLES'), SLIDERCK_ADMIN_URL . '&view=styles', $vName == 'styles'
		);
		\Joomla\CMS\HTML\Helpers\Sidebar::addEntry(
				CKText::_('CK_ABOUT'), SLIDERCK_ADMIN_URL . '&view=about', $vName == 'about'
		);
		echo '<style>.ckadminarea {
	float: left;
	width: calc(100% - 230px);

}
.ckadminsidebar {
	float: left;
	width: 220px;
	margin-right: 10px;
}
@media only screen and (max-width:640px) {
	.ckadminsidebar, .ckadminarea {
		float: none; width: inherit;
	}
}
</style>
<div class="ckadminsidebar">' . \Joomla\CMS\HTML\Helpers\Sidebar::render() . '</div>';
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
		\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework', true);
		// $doc->addScript(\Sliderck\CKUri::root(true) . '/media/jui/js/jquery.min.js');
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

	/**
	 * Convert a hexa decimal color code to its RGB equivalent
	 *
	 * @param string $hexStr (hexadecimal color value)
	 * @param boolean $returnAsString (if set true, returns the value separated by the separator character. Otherwise returns associative array)
	 * @param string $seperator (to separate RGB values. Applicable only if second parameter is true.)
	 * @return array or string (depending on second parameter. Returns False if invalid hex color value)
	 */
	static function hex2RGB($hexStr, $opacity) {
		$hexStr = preg_replace("/[^0-9A-Fa-f]/", '', $hexStr); // Gets a proper hex string
		$rgbArray = array();
		if (strlen($hexStr) == 6) { //If a proper hex code, convert using bitwise operation. No overhead... faster
			$colorVal = hexdec($hexStr);
			$rgbArray['red'] = 0xFF & ($colorVal >> 0x10);
			$rgbArray['green'] = 0xFF & ($colorVal >> 0x8);
			$rgbArray['blue'] = 0xFF & $colorVal;
		} elseif (strlen($hexStr) == 3) { //if shorthand notation, need some string manipulations
			$rgbArray['red'] = hexdec(str_repeat(substr($hexStr, 0, 1), 2));
			$rgbArray['green'] = hexdec(str_repeat(substr($hexStr, 1, 1), 2));
			$rgbArray['blue'] = hexdec(str_repeat(substr($hexStr, 2, 1), 2));
		} else {
			return false; //Invalid hex color code
		}
		$rgbacolor = "rgba(" . $rgbArray['red'] . "," . $rgbArray['green'] . "," . $rgbArray['blue'] . "," . ($opacity / 100) . ")";

		return $rgbacolor;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	public static function testUnit($value, $defaultunit = "px") {
		if (
			(stristr($value, 'px')) 
			OR (stristr($value, 'em')) 
			OR (stristr($value, 'rem')) 
			OR (stristr($value, '%')) 
			OR (stristr($value, 'vh')) 
			OR (stristr($value, 'vw')) 
			OR (stristr($value, 'vmin')) 
			OR (stristr($value, 'vmax')) 
			OR (stristr($value, 'mm')) 
			OR (stristr($value, 'in')) 
			OR (stristr($value, 'pt')) 
			OR (stristr($value, 'pc')) 
			OR $value == 'auto'
			)
			return $value;

		if ($value == '') {
			$value = 0;
		}

		return $value . $defaultunit;
	}

	/**
	 * Get a subtring with the max length setting.
	 *
	 * @param string $text;
	 * @param int $length limit characters showing;
	 * @param string $replacer;
	 * @return tring;
	 */
	public static function substring($text, $length = 100, $replacer = '...', $isStrips = true, $stringtags = '') {
	
		if($isStrips){
			$text = preg_replace('/\<p.*\>/Us','',$text);
			$text = str_replace('</p>','<br/>',$text);
			$text = strip_tags($text, $stringtags);
		}
		
		if(function_exists('mb_strlen')){
			if (mb_strlen($text) < $length)	return $text;
			$text = mb_substr($text, 0, $length);
		}else{
			if (strlen($text) < $length)	return $text;
			$text = substr($text, 0, $length);
		}
		
		return $text . $replacer;
	}

	/*
	 * Make empty slide object
	 */
	public static function initItem() {
		$item = new stdClass();
		$item->image = null;
		$item->link = null;
		$item->title = null;
		$item->text = null;
		$item->more = array();
		$item->alignment = null;
		$item->time = null;
		$item->target = 'default';
		$item->video = null;
		$item->texttype = null;
		$item->articleid = null;

		// legacy values
		$item->path = $item->image;
		$item->desc = $item->text;

		return $item;
	}

	/*
	 * Convert an old item to the new convention
	 */
	public static function legacyUpdateItem(&$item) {
		$newItem = self::initItem();
		foreach ($newItem as $key => $value) {
			if (!isset($item->$key)) $item->$key = $value;
		}
		$item->image = $item->path;
		$item->text = strip_tags($item->desc);
	}

	/**
	 * Set the correct video link
	 *
	 * $videolink string the video path
	 *
	 * @return string the new video path
	 */
	static function setVideolink($videolink) {
		// youtube
		if (stristr($videolink, 'youtu.be')) {
			$videolink = str_replace('youtu.be', 'www.youtube.com/embed', $videolink);
		} else if (stristr($videolink, 'www.youtube.com') AND !stristr($videolink, 'embed')) {
			$videolink = str_replace('youtube.com', 'youtube.com/embed', $videolink);
		}

		$videolink .= ( stristr($videolink, '?')) ? '&wmode=transparent' : '?wmode=transparent';

		return $videolink;
	}

	/**
	 * Set the correct video link
	 *
	 * $videolink string the video path
	 *
	 * @return string the new video path
	 */
	static function setImageUrl($url) {
		if (strpos($url, 'http') !== 0) {
			$url = \Sliderck\CKUri::root(true) . '/' . trim($url, '/');
		}

		return $url;
	}

	/**
	 * Truncates text blocks over the specified character limit and closes
	 * all open HTML tags. The method will optionally not truncate an individual
	 * word, it will find the first space that is within the limit and
	 * truncate at that point. This method is UTF-8 safe.
	 *
	 * @param   string   $text       The text to truncate.
	 * @param   integer  $length     The maximum length of the text.
	 * @param   boolean  $noSplit    Don't split a word if that is where the cutoff occurs (default: true).
	 * @param   boolean  $allowHtml  Allow HTML tags in the output, and close any open tags (default: true).
	 *
	 * @return  string   The truncated text.
	 *
	 * @since   11.1
	 */
	public static function truncate($text, $length = 0, $noSplit = true, $allowHtml = true) {
		if ($length == 0) return '';
		// Check if HTML tags are allowed.
		if (!$allowHtml) {
			// Deal with spacing issues in the input.
			$text = str_replace('>', '> ', $text);
			$text = str_replace(array('&nbsp;', '&#160;'), ' ', $text);
			$text = JString::trim(preg_replace('#\s+#mui', ' ', $text));

			// Strip the tags from the input and decode entities.
			$text = strip_tags($text);
			$text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');

			// Remove remaining extra spaces.
			$text = str_replace('&nbsp;', ' ', $text);
			$text = JString::trim(preg_replace('#\s+#mui', ' ', $text));
		}

		// Truncate the item text if it is too long.
		if ($length > 0 && JString::strlen($text) > $length) {
			// Find the first space within the allowed length.
			$tmp = JString::substr($text, 0, $length);

			if ($noSplit) {
				$offset = JString::strrpos($tmp, ' ');
				if (JString::strrpos($tmp, '<') > JString::strrpos($tmp, '>')) {
					$offset = JString::strrpos($tmp, '<');
				}
				$tmp = JString::substr($tmp, 0, $offset);

				// If we don't have 3 characters of room, go to the second space within the limit.
				if (JString::strlen($tmp) > $length - 3) {
					$tmp = JString::substr($tmp, 0, JString::strrpos($tmp, ' '));
				}
			}

			if ($allowHtml) {
				// Put all opened tags into an array
				preg_match_all("#<([a-z][a-z0-9]*)\b.*?(?!/)>#i", $tmp, $result);
				$openedTags = $result[1];
				$openedTags = array_diff($openedTags, array("img", "hr", "br"));
				$openedTags = array_values($openedTags);

				// Put all closed tags into an array
				preg_match_all("#</([a-z]+)>#iU", $tmp, $result);
				$closedTags = $result[1];

				$numOpened = count($openedTags);

				// All tags are closed
				if (count($closedTags) == $numOpened) {
					return $tmp . '...';
				}
				$tmp .= '...';
				$openedTags = array_reverse($openedTags);

				// Close tags
				for ($i = 0; $i < $numOpened; $i++) {
					if (!in_array($openedTags[$i], $closedTags)) {
						$tmp .= "</" . $openedTags[$i] . ">";
					} else {
						unset($closedTags[array_search($openedTags[$i], $closedTags)]);
					}
				}
			}

			$text = $tmp;
		}

		return $text;
	}

	static function getArticle($item) {
		$app     = Factory::getApplication();
		if (version_compare(JVERSION, '4') >= 0) {
			$factory = $app->bootComponent('com_content')->getMVCFactory();

			// Get an instance of the generic articles model
			$articles = $factory->createModel('Articles', 'Site', ['ignore_request' => true]);
		} else {
			// load the content articles file
			$com_path = JPATH_SITE . '/components/com_content/';
			include_once $com_path . 'router.php';
			include_once $com_path . 'helpers/route.php';
			\Joomla\CMS\MVC\Model\BaseDatabaseModel::addIncludePath($com_path . '/models', 'ContentModel');

			// Get an instance of the generic articles model
			$articles = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		}		
		// Access filter
		$access = !\Joomla\CMS\Component\ComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = \Joomla\CMS\Access\Access::getAuthorisedViewLevels(\Joomla\CMS\Factory::getUser()->get('id'));
		// Get an instance of the generic articles model
		// Set application parameters in model
//		$appParams = $app->getParams();
		$articles->setState('params', \Joomla\CMS\Component\ComponentHelper::getParams('com_content'));
//		$articles->setState('params', $appParams);
		$articles->setState('filter.published', 1);
		$item->slidearticleid = isset($item->slidearticleid) ? $item->slidearticleid : $item->articleid;
		$articles->setState('filter.article_id', $item->slidearticleid);
		$items2 = $articles->getItems();
		$item->article = $items2[0];
		$item->text = \Joomla\CMS\HTML\HTMLHelper::_('content.prepare', $item->article->introtext);
		$item->title = $item->article->title;
		// set the item link to the article depending on the user rights
		if ($access || in_array($item->article->access, $authorised)) {
			// We know that user has the privilege to view the article
			$item->slug = $item->article->id . ':' . $item->article->alias;
			$item->catslug = $item->article->catid ? $item->article->catid . ':' . $item->article->category_alias : $item->article->catid;
			$item->link = \Joomla\CMS\Router\Route::_(\Joomla\Component\Content\Site\Helper\RouteHelper::getArticleRoute($item->slug, $item->catslug));
		} else {
			$app = \Sliderck\CKFof::getApplication();
			$menu = $app->getMenu();
			$menuitems = $menu->getItems('link', 'index.php?option=com_users&view=login');
			if (isset($menuitems[0])) {
				$Itemid = $menuitems[0]->id;
			} elseif ($app->input->get('Itemid', 0, 'int') > 0) {
				$Itemid = $app->input->get('Itemid', 0, 'int');
			}
			$item->link = \Joomla\CMS\Router\Route::_('index.php?option=com_users&view=login&Itemid=' . $Itemid);
		}
		return $item;
	}

	static function getPagebuilderck($item) {
		$item->slidepageid = isset($item->slidepageid) ? $item->slidepageid : 0;
		$item->article = '';
		$item->text = '';
		$item->title = '';
		$item->link = '';

		if (! $item->slidepageid) return $item;
		
		include_once JPATH_ROOT . '/components/com_pagebuilderck/models/page.php';
		$model = \Joomla\CMS\MVC\Model\BaseDatabaseModel::getInstance('Page', 'PagebuilderckModel');
		$page = $model->getItem($item->slidepageid);
		if (isset($page->htmlcode) && $page->htmlcode) {
			$item->text = '<div class="pagebuilderck">' .  $page->htmlcode . '</div>';
		}

		return $item;
	}

	/**
	 * Get the CSS of sliderck style
	 */
	public static function getStyleLayoutcss($id) {
		if (! $id) return '';

		// Create a new query object.
		$db = \Sliderck\CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.layoutcss');
		$query->from($db->quoteName('#__sliderck_styles') . ' AS a');
		$query->where('(a.state IN (0, 1))');
		$query->where('a.id = ' . (int)$id);

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadResult();

		return $results;
	}

	/**
	* Check updates for the component, module, or plugins
	*/
	public static function checkUpdate() {
		$input = \Sliderck\CKFof::getApplication()->input;
		$isBadge = $input->get('isbadge', 0, 'int');
		$latest_version = self::getLatestVersion();
		$update_status = '';
		if (self::isOutdated()) {
			if ($isBadge) {
				$update_status = '<span class="badge-alertck">' . \Sliderck\CKText::_('CK_UPDATE_NOTIFICATION') . '</span>';
			} else {
				$update_status = '<p class="alert alert-warning">' . \Sliderck\CKText::_('CK_IS_OUTDATED') . ' : <b>' . $latest_version . '</b></p>';
			}
		} else {
			// $update_status = '<p class="alert alert-success">' . \Sliderck\CKText::_('CK_IS_UPTODATE') . '</p>';
		}

		echo $update_status;
		exit();
	}

	/**
	 * Check if a new version is available
	 * 
	 * @return false, or the latest version
	 */
	public static function getLatestVersion() {
		$releaseNotes = self::getReleaseNotes();
		$latest_version = false;
		if ($releaseNotes) {
			// $test_version = preg_match('/\*(.*?)\n/', $releaseNotes, $results);
			// $latest_version = trim($results[1]);
			$latest_version = $releaseNotes->version;
		}

		return $latest_version;
	}
	
	/*
	 * Get a variable from the manifest file.
	 * 
	 * @return the current version
	 */
	public static function getCurrentVersion() {
		if (! self::$currentVersion) {
			// get the version installed
			self::$currentVersion = false;
			$file_url = JPATH_SITE .'/administrator/components/com_sliderck/sliderck.xml';
			if (! $xml_installed = simplexml_load_file($file_url)) {
				// die;
			} else {
				self::$currentVersion = (string)$xml_installed->version;
			}
		}

		return self::$currentVersion;
	}

	/**
	 * Get the release notes content
	 * 
	 * @return false or the file content
	 */
	public static function getReleaseNotes() { 
		if (! self::$releaseNotes) {
			// $url = 'http://update.joomlack.fr/sliderck_update.txt';
			$url = 'http://update.joomlack.fr/com_sliderck_notes.json';
			$releaseNotes = @file_get_contents($url);
			self::$releaseNotes = json_decode($releaseNotes);
		}
		
		return self::$releaseNotes;
	}

	/**
	 * Format the release notes in html
	 */
	public static function displayReleaseNotes() {
		$releaseNotes = self::getReleaseNotes();
		if (! isset($releaseNotes->releasenotes)) return;

		if (self::isOutdated()) {
			echo '<br /><p style="text-transform:uppercase;text-decoration: underline;">Release notes :</p><br />';
		}
		foreach ($releaseNotes->releasenotes as $i => $v) {
			// stop at the current version notes
			if (version_compare($i, self::getCurrentVersion() ) <= 0) break;

			echo '<h4>VERSION : ' . $i . ' - ' . $v->date . '</h4>';
			echo '<ul>';
				foreach ($v->notes as $n) {
					echo '<li>' . htmlspecialchars($n) . '</li>';
				}
			echo '</ul>';
		}
	}

	/**
	 * Check if you have the latest version
	 * 
	 * @return boolean, true if outdated
	 */
	public static function isOutdated() {
		return version_compare(self::getLatestVersion(), self::getCurrentVersion() ) > 0;
	}

	public static function getProMessage() {
		$html = '<div class="ckinfo"><i class="fas fa-info"></i><a href="https://www.joomlack.fr/en/joomla-extensions/slider-ck" target="_blank">' . \Sliderck\CKText::_('SLIDERCK_ONLY_PRO') . '</a></div>';
	
		return $html;
	}
}
