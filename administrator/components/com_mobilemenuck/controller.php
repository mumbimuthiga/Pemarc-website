<?php
/**
 * @name		Mobile Menu CK
 * @package		com_mobilemenuck
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Mobilemenuck\CKController;

class MobilemenuckController extends CKController
{
	static $releaseNotes;

	static $currentVersion;

	public function display($cachable = false, $urlparams = false)
	{
		$input	= \Mobilemenuck\CKFof::getApplication()->input;
		$view	= $input->get('view', 'items');
		$input->set('view', $view);

		parent::display();

		return $this;
	}

	function loadUpdatecheckJs() {
		$js_checking = 'jQuery(document).ready(function (){
				jQuery(\'.mobilemenuckchecking\').each(function(i ,el){
					var isbadge = jQuery(el).hasClass(\'isbadgeck\') ? 1 : 0;
					jQuery.ajax({
						type: "POST",
						url: \'' . \Mobilemenuck\CKUri::root(true) . '/administrator/index.php?option=com_mobilemenuck&task=checkUpdate\',
						data: {
							isbadge : isbadge
						}
					}).done(function(response) {
						response = response.trim();
						if ( response.substring(0,7).toLowerCase() == \'error\' ) {
							// alert(response);
							// show_ckmodal(response);
						} else {
							jQuery(el).append(response);
						}
					}).fail(function() {
						// alert(Joomla.JText._(\'CK_FAILED\', \'Failed\'));
					});
				});
			});';
		$doc = \Mobilemenuck\CKFof::getDocument();
		$doc->addScriptDeclaration($js_checking);
	}

	/**
	* Check updates for the component, module, or plugins
	*/
	public function checkUpdate() {
		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$isBadge = $input->get('isbadge', 0, 'int');
		$latest_version = self::getLatestVersion();
		$update_status = '';
		if (self::isOutdated()) {
			if ($isBadge) {
				$update_status = '<span class="badge-alertck">' . \Mobilemenuck\CKText::_('CK_UPDATE_NOTIFICATION') . '</span>';
			} else {
				$update_status = '<p class="alert alert-warning">' . \Mobilemenuck\CKText::_('CK_IS_OUTDATED') . ' : <b>' . $latest_version . '</b></p>';
			}
		} else {
			// $update_status = '<p class="alert alert-success">' . \Mobilemenuck\CKText::_('CK_IS_UPTODATE') . '</p>';
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
			$file_url = JPATH_SITE .'/administrator/components/com_mobilemenuck/mobilemenuck.xml';
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
			// $url = 'http://update.joomlack.fr/mobilemenuck_update.txt';
			$url = 'https://update.joomlack.fr/com_mobilemenuck_notes.json';
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
	
	/*
	 * Generate the CSS styles from the settings
	 */
	public function ajaxRenderCss() {
		$input	= \Mobilemenuck\CKFof::getApplication()->input;
		$fields = $input->get('fields', '', 'raw');
		$fields = json_decode($fields);
		$customstyles = stripslashes( $input->get('customstyles', '', 'string'));
		$customstyles = json_decode($customstyles);
		$customcss = $input->get('customcss', '', 'html');
		// $customcss = str_replace('../..', MOBILEMENUCK_MEDIA_URI, $customcss);

		$css = $this->renderCss($fields, $customstyles);
		$css .= $customcss;

		echo $css;
		exit();
	}

	/*
	 * Render the CSS from the settings
	 */
	public function renderCss($fields, $customstyles) {
		include_once MOBILEMENUCK_ADMIN_PATH . '/helpers/ckstyles.php';
		$ckstyles = new CKStyles();
		$css = $this->getDefaultCss($fields);
		$css .= $ckstyles->create($fields, $customstyles);

		return $css;
	}

	/*
	 * Render the CSS from the settings
	 */
	public function getDefaultCss($fields) {
		$menubarbuttonposition = isset($fields->menubarbuttonposition) ? $fields->menubarbuttonposition : 'right';
		$menubarbuttonwidth = isset($fields->menubarbuttonwidth) ? MobilemenuckHelper::testUnit($fields->menubarbuttonwidth) : '43px';
		$css = '';
		$css .= "/* Mobile Menu CK - https://www.joomlack.fr */\n";
		$css .= "/* Automatic styles */\n\n";

		// styles for the collapsing bar
		$css .= ".mobilemenuck-bar {display:none;position:relative;left:0;top:0;right:0;z-index:100;}\n";
		$css .= ".mobilemenuck-bar-title {display: block;padding-" . $menubarbuttonposition . ":" . $menubarbuttonwidth . ";}\n";
		$css .= ".mobilemenuck-logo {margin-" . $menubarbuttonposition . ":" . $menubarbuttonwidth . ";}\n";
		$css .= ".mobilemenuck-bar-button {cursor:pointer;box-sizing: border-box;position:absolute; top: 0; " . $menubarbuttonposition . ": 0;line-height:0.8em;font-family:Verdana;text-align: center;}\n";

		// styles for the menu
		$css .= ".mobilemenuck {box-sizing: border-box;width: 100%;}\n";
		$css .= ".mobilemenuck-topbar {position:relative;}\n";
		$css .= ".mobilemenuck-title {display: block;}\n";
		$css .= ".mobilemenuck-button {cursor:pointer;box-sizing: border-box;position:absolute; top: 0; right: 0;line-height:0.8em;font-family:Verdana;text-align: center;}\n";
		// for the links
		$css .= ".mobilemenuck a {display:block;text-decoration: none;}\n";
		$css .= ".mobilemenuck a:hover {text-decoration: none;}\n";

		// styles for the menu items
		$css .= ".mobilemenuck .mobilemenuck-item > div {position:relative;}\n";
		// $css .= ".mobilemenuck div.level1 > a {" . implode($styles_css->level1menuitem) . "}";
		// $css .= ".mobilemenuck div.level2 > a {" . implode($styles_css->level2menuitem) . "}";
		// $css .= ".mobilemenuck div.level2 + .mobilemenuck-submenu div.mobilemenuck-item a {" . implode($styles_css->level3menuitem) . "}";
		$css .= ".mobilemenuck-lock-button.mobilemenuck-button {right:45px}\n";
		$css .= ".mobilemenuck-lock-button.mobilemenuck-button svg {max-height:50%;}\n";
		$css .= ".mobilemenuck-lock-button.mobilemenuck-button::after {display: block;content: \"\";height: 100%;width: 100%;z-index: 1;position: absolute;top: 0;left: 0;}\n";
		$css .= ".mobilemenuck[data-display=\"flyout\"] {overflow: initial !important;}\n";
		$css .= ".mobilemenuck[data-display=\"flyout\"] .level1 + .mobilemenuck-submenu {position:absolute;top:0;left:auto;display:none;height:100vh;left:100%;}\n";
		$css .= ".mobilemenuck[data-display=\"flyout\"] .level2 + .mobilemenuck-submenu {position:absolute;top:0;left:auto;display:none;height:100vh;left:100%;}\n";
		$css .= ".mobilemenuck[data-display=\"flyout\"][data-effect*=\"slideright\"] .level1 + .mobilemenuck-submenu {right:100%;left:auto;}\n";
		$css .= ".mobilemenuck[data-display=\"flyout\"][data-effect*=\"slideright\"] .level2 + .mobilemenuck-submenu {right:100%;left:auto;}\n";
		$css .= "/* RTL support */
.rtl .mobilemenuck-bar-button {left: 0;right: auto;}
.rtl .mobilemenuck-button {left: 0;right: auto;}
.rtl .mobilemenuck-togglericon::after {left: 0;right: auto;}";
		$css .= "@media screen and (max-width: 640px) {
.mobilemenuck[data-display=\"flyout\"] .level1 + .mobilemenuck-submenu {position:static;width: initial !important;height: initial;}
}\n";
		$css .= "@media screen and (max-width: 1000px) {
.mobilemenuck[data-display=\"flyout\"] .level2 + .mobilemenuck-submenu {position:static;width: initial !important;height: initial;}
}\n";
		$css .= ".mobilemenuck-backbutton { cursor: pointer; }";
		$css .= ".mobilemenuck-backbutton:hover { opacity: 0.7; }";

		// styles for the accordion icons
		$css .= "/* for accordion */\n";
		// $css .= ".mobilemenuck .mobilemenuck-togglericon:after {cursor:pointer;text-align:center;}\n";
		if (isset($fields->togglericoncontentclosed)) {
			$togglericonclosed = $fields->togglericoncontentclosed == 'custom' ? $fields->togglericoncontentclosedcustomtext : $fields->togglericoncontentclosed;
		} else {
			$togglericonclosed = '+';
		}
		if (isset($fields->togglericoncontentopened)) {
			$togglericonopened = $fields->togglericoncontentopened == 'custom' ? $fields->togglericoncontentopenedcustomtext : $fields->togglericoncontentopened;
		} else {
			$togglericonopened = '-';
		}
		$css .= ".mobilemenuck-togglericon:after {cursor:pointer;text-align:center;display:block;position: absolute;right: 0;top: 0;content:\"" . $togglericonclosed . "\";}\n";
		$css .= ".mobilemenuck .open .mobilemenuck-togglericon:after {content:\"" . $togglericonopened . "\";}\n";

		// add google font
		// $css .= "\n\n/* Google Font stylesheets */\n\n";
		// $css .= implode("\n", $gfontcalls);
		// replace the path for correct image rendering
		// $customcss = $input->get('customcss', '', 'raw');
		// if ($input->get('action')) {
			// $customcss = str_replace('../..', \Mobilemenuck\CKUri::root(true) . '/plugins/system/maximenuckmobile', $customcss);
		// }
		// $css .= "\n\n/* Custom CSS generated from the plugin options */\n\n";
		// $css .= $customcss;

		return $css;
	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxAddPicture() {
		require_once MOBILEMENUCK_ADMIN_PATH . '/helpers/ckbrowse.php';
		CKBrowse::ajaxAddPicture();
	}
	
	/*
	 * Generate the CSS styles from the settings
	 */
	public function ajaxSaveStyles() {
		// security check
		if (! MobilemenuckHelper::checkAjaxToken()) {
			exit();
		}
		// Import Table
		\Joomla\CMS\Table\Table::addIncludePath(MOBILEMENUCK_ADMIN_PATH . '/tables');
		$row = \Joomla\CMS\Table\Table::getInstance('Styles', 'MobilemenuckTable');

		$input	= \Mobilemenuck\CKFof::getApplication()->input;

		$fields = $input->get('fields', '', 'raw');
		$id = $input->get('id', 0, 'int');
		$name = $input->get('name', '', 'string');
		if (! $name) $name = 'style' . $id;
		$layoutcss = trim($input->get('layoutcss', '', 'raw'));

		// load the module
		$row->load( (int) $id ); 
		$row->params = $fields;
		$row->name = $name;
		$row->layoutcss = $layoutcss;
		$row->state = 1;
		$result = Mobilemenuck\CKFof::dbStore('#__mobilemenuck_styles', $row);
		if (!$result) {
			echo "{'result': '0', 'id': '" . $row->id . "', 'message': 'Error : Can not save the Styles !'}";
			// echo($this->_db->getErrorMsg());
			die;
		}
		echo '{"result": "1", "id": "' . $result . '", "message": "Styles saved successfully"}';
		exit();
	}

	/**
	 * Ajax method to save the json data into the .mmck file
	 *
	 * @return  boolean - true on success for the file creation
	 *
	 */
	function exportParams() {
		$input = \Mobilemenuck\CKFof::getApplication()->input;
		// create a backup file with all fields stored in it
		$fields = $input->get('jsonfields', '', 'string');
		$backupfile_path = MOBILEMENUCK_ADMIN_PATH . '/export/exportParamsMobilemenuckStyle'. $input->get('styleid',0,'int') .'.mmck';
		if (\Mobilemenuck\CKFile::write($backupfile_path, $fields)) {
			echo '1';
		} else {
			echo '0';
		}

		exit();
	}
	
	/**
	 * Ajax method to import the .mmck file into the interface
	 *
	 * @return  boolean - true on success for the file creation
	 *
	 */
	function uploadParamsFile() {
		$app = \Mobilemenuck\CKFof::getApplication();
		$input = $app->input;
		$file = $input->files->get('file', '', 'array');
		if (!is_array($file))
			exit();

		$filename = \Mobilemenuck\CKFile::makeSafe($file['name']);

		// check if the file exists
		if (\Mobilemenuck\CKFile::getExt($filename) != 'mmck') {
			$msg = \Mobilemenuck\CKText::_('CK_NOT_MMCK_FILE', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		//Set up the source and destination of the file
		$src = $file['tmp_name'];

		// check if the file exists
		if (!$src || !is_file($src)) {
			$msg = \Mobilemenuck\CKText::_('CK_FILE_NOT_EXISTS', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		// read the file
		if (!$filecontent = file_get_contents($src)) {
			$msg = \Mobilemenuck\CKText::_('CK_UNABLE_READ_FILE', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		// replace vars to allow data to be moved from another server
		$filecontent = str_replace("|URIROOT|", \Mobilemenuck\CKUri::root(true), $filecontent);
//		$filecontent = str_replace("|qq|", '"', $filecontent);

//		echo $filecontent;
		echo json_encode(array('data'=> $filecontent));
		exit();
	}
	
	/**
	 * Ajax method to read the fields values from the selected preset
	 *
	 * @return  json - 
	 *
	 */
	function loadPresetFields() {
		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$preset = $input->get('preset', '', 'string');
		$folder_path = MOBILEMENUCK_MEDIA_PATH . '/presets/';
		// load the fields
		$fields = '{}';
		// if ( file_exists($folder_path . $preset. '.mmck') ) {
			// $fields = @file_get_contents($folder_path . $preset. '.mmck');
			// $fields = str_replace("\n", "", $fields);
		// } else {
			// echo '{"result" : 0, "message" : "File Not found : '.$folder_path . $preset. '.mmck'.'"}';
			// exit();
		// }
		$fields = '{}';
		if ( file_exists($folder_path . $preset. '/styles.json') ) {
			$fields = @file_get_contents($folder_path . $preset. '/styles.json');
			$fields = str_replace("\n", "", $fields);
		} else {
			echo '{"result" : 0, "message" : "File Not found : '.$folder_path . $preset. '/styles.json'.'"}';
			exit();
		}
		// load the custom css
//		$customcss = '';
//		if ( file_exists($folder_path . $preset. '/custom.css') ) {
//			$customcss = @file_get_contents($folder_path . $preset. '/custom.css');
//		} else {
//			echo '{"result" : 0, "message" : "File Not found : '.$folder_path . $preset. '/custom.css'.'"}';
//			exit();
//		}

		echo '{"result" : 1, "fields" : "'.$fields.'", "customcss" : ""}';
		exit();
	}

	/**
	 * Ajax method to read the custom css from the selected preset
	 *
	 * @return  string - the custom CSS on success, error message on failure
	 *
	 */
	function loadPresetCustomcss() {
		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$preset = $input->get('folder', '', 'string');
		$folder_path = MOBILEMENUCK_MEDIA_PATH . '/presets/';

		// load the custom css
		$customcss = '';
		if ( file_exists($folder_path . $preset. '/custom.css') ) {
			$customcss = @file_get_contents($folder_path . $preset. '/custom.css');
		} else {
			echo '|ERROR| File Not found : '.$folder_path . $preset. '/custom.css';
			exit();
		}

		echo $customcss;
		exit();
	}

	/**
	 * Ajax method to clean the name of the google font
	 */
	public function cleanGfontName() {
		$input = new \Joomla\CMS\Input\Input();
		$gfont = $input->get('gfont', '', 'string');

		// <link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
		// Open+Sans+Condensed:300
		// Open Sans
		if ( preg_match( '/family=(.*?) /', $gfont . ' ', $matches) ) {
			if ( isset($matches[1]) ) {
				$gfont = $matches[1];
			}
		}

		$gfont = str_replace(' ', '+', ucwords (trim($gfont)));
		echo trim(trim($gfont, "'"));
		die;
	}

	public function ajaxSetStyle() {
		// security check
		MobilemenuckHelper::checkAjaxToken();

		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$styleid = $input->get('styleid', 0, 'int');

		// Import table
		// JLoader::register('FieldsTableField', JPATH_ADMINISTRATOR . '/components/com_fields/tables/field.php');

		$table = \Joomla\CMS\Table\Table::getInstance('Module', '\\Joomla\\CMS\\Table\\');

		// Get the data.
		if (!$table->load($id))
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}
		
		$params = new \Mobilemenuck\CKRegistry($table->params);
		// check if we need to activate the feature
		if ($styleid > 0) {
			// $params->set('mobilemenuck_enable', '1');
			$params->set('mobilemenuck_styles', $styleid);
		} else {
			// $params->set('mobilemenuck_enable', '0');
			$params->set('mobilemenuck_styles', '');
		}

		$table->params = $params->toString();

		// Store the data.
		if (!$table->store())
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}

		// load the style name
		$db = \Mobilemenuck\CKFof::getDbo();
		$query = $db->getQuery(true)
			->select('name')
			->from('#__mobilemenuck_styles')
			->where('id = ' . (int)$styleid);

		$db->setQuery($query);
		$name = $db->loadResult();
		// success
		echo '{"result" : "1", "styleid" : "'.$styleid.'", "fieldid" : "'.$id.'", "name": "' . $name . '"}';
		exit;
	}

	public function ajaxSetCustomMenuStyle() {
		// security check
		MobilemenuckHelper::checkAjaxToken();

		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$styleid = $input->get('styleid', 0, 'int');

		// Import table
		 JLoader::register('MobilemenuckTableMenus', JPATH_ADMINISTRATOR . '/components/com_mobilemenuck/tables/menus.php');

		$table = \Joomla\CMS\Table\Table::getInstance('Menus', 'MobilemenuckTable');

		// Get the data.
		if (!$table->load($id))
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}
		
		$params = new \Mobilemenuck\CKRegistry($table->params);
		// check if we need to activate the feature
		if ($styleid > 0) {
			// $params->set('mobilemenuck_enable', '1');
			$table->style = $styleid;
		} else {
			// $params->set('mobilemenuck_enable', '0');
			$table->style = '';
		}

		// Store the data.
		if (!$table->store())
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}

		// load the style name
		$db = \Mobilemenuck\CKFof::getDbo();
		$query = $db->getQuery(true)
			->select('name')
			->from('#__mobilemenuck_styles')
			->where('id = ' . (int)$styleid);

		$db->setQuery($query);
		$name = $db->loadResult();
		// success
		echo '{"result" : "1", "styleid" : "'.$styleid.'", "fieldid" : "'.$id.'", "name": "' . $name . '"}';
		exit;
	}

	public function ajaxSetMobileState() {
		// security check
		MobilemenuckHelper::checkAjaxToken();

		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$state = $input->get('state', 0, 'int');

		$table = \Joomla\CMS\Table\Table::getInstance('Module', '\\Joomla\\CMS\\Table\\');

		// Get the data.
		if (!$table->load($id))
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}
		
		$params = new \Mobilemenuck\CKRegistry($table->params);
		// check if we need to activate the feature
		if ($state == 1) {
			$params->set('mobilemenuck_enable', '1');
			// $params->set('mobilemenuck_styles', $styleid);
		} else {
			$params->set('mobilemenuck_enable', '0');
			// $params->set('mobilemenuck_styles', '');
		}

		$table->params = $params->toString();

		// Store the data.
		if (!$table->store())
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}

		// success
		echo '{"result" : "1", "state" : "'.$state.'", "id" : "'.$id.'"}';
		exit;
	}

	public function ajaxSetMerge() {
		// security check
		MobilemenuckHelper::checkAjaxToken();

		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$mergeid = $input->get('mergeid', 0, 'int');

		// Import table
		// JLoader::register('FieldsTableField', JPATH_ADMINISTRATOR . '/components/com_fields/tables/field.php');

		$table = \Joomla\CMS\Table\Table::getInstance('Module', '\\Joomla\\CMS\\Table\\');

		// Get the data.
		if (!$table->load($id))
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "' . $msg . '"}';
			exit;
		}

		$params = new \Mobilemenuck\CKRegistry($table->params);
		// check if we need to activate the feature
		if ($mergeid > 0) {
			// $params->set('mobilemenuck_enable', '1');
			$params->set('mobilemenuck_merge', $mergeid);
		} else {
			// $params->set('mobilemenuck_enable', '0');
			$params->set('mobilemenuck_merge', '');
		}

		$table->params = $params->toString();

		// Store the data.
		if (!$table->store())
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}

		// load the style name
//		$db = \Mobilemenuck\CKFof::getDbo();
//		$query = $db->getQuery(true)
//			->select('title')
//			->from('#__modules')
//			->where('id = ' . (int)$mergeid);
//
//		$db->setQuery($query);
//		$mergename = $db->loadResult();
		// success
		echo '{"result" : "1"}';
		exit;
	}

	public function ajaxSetMergeOrder() {
		// security check
		MobilemenuckHelper::checkAjaxToken();

		$input = \Mobilemenuck\CKFof::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$mergeorder = $input->get('mergeorder', 0, 'int');

		// Import table
		// JLoader::register('FieldsTableField', JPATH_ADMINISTRATOR . '/components/com_fields/tables/field.php');

		$table = \Joomla\CMS\Table\Table::getInstance('Module', '\\Joomla\\CMS\\Table\\');

		// Get the data.
		if (!$table->load($id))
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "' . $msg . '"}';
			exit;
		}

		$params = new \Mobilemenuck\CKRegistry($table->params);
		// check if we need to activate the feature
		if ($mergeorder > 0) {
			// $params->set('mobilemenuck_enable', '1');
			$params->set('mobilemenuck_mergeorder', $mergeorder);
		} else {
			// $params->set('mobilemenuck_enable', '0');
			$params->set('mobilemenuck_mergeorder', '');
		}

		$table->params = $params->toString();

		// Store the data.
		if (!$table->store())
		{
			$msg = $table->getError();
			echo '{"result" : 0, "message" : "'.$msg.'"}';
			exit;
		}

		// success
		echo '{"result" : "1"}';
		exit;
	}
}