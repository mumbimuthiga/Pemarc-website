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

class Menu
{
	/**
	 * Create the menu, call JS and CSS
	 *
	 * @return  string the mobile menu ID
	 *
	 */
	public static function load($selector, $options = array(), $inline = false) {
		require_once(MOBILEMENUCK_PLATFORM . '/loader.php');
		require_once('Mobile_Detect.php');

		// loads the language files
		$lang	= \Joomla\CMS\Factory::getLanguage();
		$lang->load('plg_system_mobilemenuck', JPATH_SITE . '/plugins/system/mobilemenuck', $lang->getTag(), false);
		$lang->load('plg_system_mobilemenuck', JPATH_ADMINISTRATOR, $lang->getTag(), false);

		// create a unique ID for the menu
		$menuid = 'mobilemenuck-' . (int) (microtime(true) * 100);

		$defaults = self::getDefaultOptions();
		$defaults['menuid'] = $menuid; // unique text identifier

		$options = array_merge($defaults, $options);

		// set the text for the menu bar
		switch ($options['showmobilemenutext']) {
			case 'none':
				$options['mobilemenutext'] = '&nbsp;';
				break;
			case 'default':
			default:
				$options['mobilemenutext'] = \Joomla\CMS\Language\Text::_('PLG_MOBILEMENUCK_MENU');
				break;
			case 'custom':
				$options['mobilemenutext'] = addslashes($options['mobilemenutext']);
				break;
		}

		// B/C for old logo position option
		if ((int)$options['showlogo'] > 0 && !isset($options['logo_where'])) {
			$options['logo_where'] = array($options['showlogo']);
		}

		\Joomla\CMS\HTML\HTMLHelper::_("jquery.framework");
		$file = MOBILEMENUCK_PLUGIN_MEDIA_URI . '/assets/mobilemenuck.js?ver=1.7.1';
		$js = "jQuery(document).ready(function(){"
					. " new MobileMenuCK(jQuery('" . $selector . "'), {";
					foreach ($options as $name => $value) {
						$js .= $name . " : '" . $value . "',";
					}
			$js .= "uriroot : '" . \Joomla\CMS\Uri\Uri::root(true) . "'";
			$js .= "});"
			. " });";
		$css = self::getMediaQueries($selector, $options);
		if ($inline) {
			CKLoader::loadScriptInline($file);
			CKLoader::loadScriptDeclarationInline($js);
			CKLoader::loadStyleDeclarationInline($css);
		} else {
			CKLoader::loadScript($file);
			CKLoader::loadScriptDeclaration($js);
			CKLoader::loadStyleDeclaration($css);
		}

		return $menuid;
	}

	public static function getDefaultOptions() {
		$defaults = [
//			'menuid' => $menuid											// unique text identifier
			'menubarbuttoncontent' => '&#x2261;'						// character to put in the button
			,'topbarbuttoncontent' => '×'								// character to put in the button
			,'showmobilemenutext' => 'default'							// default, custom, none
			,'mobilemenutext' => \Joomla\CMS\Language\Text::_('PLG_MOBILEMENUCK_MENU')		// text to use if showmobilemenutext = custom
			,'container' => 'body'										// body, topfixed, menu
			,'detectiontype' => 'resolution'							// resolution, phone, tablet
			,'resolution' => '640'										// value in px
			,'usemodules' => '0'										// 0, 1
			,'useimages' => '0'											// 0, 1
			,'showlogo' => '1'											// 0 (no), 1 (yes), 2 (in menu bar), 3 (in top bar)
			,'showdesc' => '0'											// 0, 1
			,'displaytype' => 'accordion'								// flat, accordion, fade, push
			,'displayeffect' => 'normal'								// normal, slideleft, slideright, slideleftover, sliderightover, topfixed, open
			,'menuwidth' => '300'										// value in px
			,'openedonactiveitem' => '0'								// 0, 1
			,'mobilebackbuttontext' => \Joomla\CMS\Language\Text::_('PLG_MOBILEMENUCK_MOBILEBACKBUTTON')		// text
			,'menuselector' => 'ul'		// text
			,'uriroot' => \Joomla\CMS\Uri\Uri::root(true)								// base uri of the website
			,'tooglebarevent' => 'click'								// event used to open the menu
			,'tooglebaron' => 'all'										// target used to open the menu
			// Logo options
			,'logo_source' => 'maximenuck'								// maximenuck, custom
			,'logo_image' => ''											// the image src
			,'logo_link' => ''											// the link url
			,'logo_alt' => ''											// the alt tag
			,'logo_position' => 'left'									// left, center, right
			,'logo_width' => ''											// image width
			,'logo_height' => ''										// image height
			,'logo_margintop' => ''										// margin top
			,'logo_marginright' => ''									// margin right
			,'logo_marginbottom' => ''									// margin bototm
			,'logo_marginleft' => ''									// margin left
			,'topfixedeffect' => 'always'								// always or onscroll
			,'lock_button' => '0'
			,'lock_forced' => '0'
			,'accordion_use_effects' => '0'
			,'accordion_toggle' => '0'
			,'show_icons' => '1'
			,'counter' => '0'
			,'hide_desktop' => '1'
			,'overlay' => '0'
		];

		return $defaults;
	}

	/**
	 * Search for the CSS styles from default theme, or in the template
	 *
	 * @return  void
	 *
	 */
	public static function loadTheme($id, $inline = false, $themeid = null) {
		require_once(MOBILEMENUCK_PLATFORM . '/loader.php');

		$layoutcss = '';
		if ((int) $themeid) {
			$layoutcss = Helper::getStyleById($themeid, $select = 'layoutcss', $type = 'result');
		}
		// if we don't have a layout according to the ID, then use the default one
		if (!(int) $themeid || ! $layoutcss){
			$layoutcss = Helper::getLayoutCss();
		}
		Helper::makeCssReplacement($layoutcss);
		$css = str_replace('|ID|', '[data-id="' . $id . '"]', $layoutcss);

		if ($inline) {
			CKLoader::loadStyleDeclarationInline($css);
		} else {
			CKLoader::loadStyleDeclaration($css);
		}
	}

	/**
	 * Set the mediaqueries to hide - show the module and mobile bar
	 *
	 * @return  string - the css to load in the page
	 *
	 */
	private static function getMediaQueries($selector, $options) {
		$detect_type = $options['detectiontype'];
		$detect = new Mobile_Detect;
		$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
		$bodypadding = ($options['container'] == 'body' || $options['container'] == 'topfixed') ? 'body { padding-top: 40px !important; }' : '';
		if ($detect_type == 'resolution' || $detect_type == 'resolutionanddevices' && !$detect->isMobile()) {
			$css = "#" . $options['menuid'] . "-mobile-bar, #" . $options['menuid'] . "-mobile-bar-wrap-topfixed { display: none; }
	@media only screen and (max-width:" . str_replace('px', '', $options['resolution']) . "px){
	" . ($options['hide_desktop'] === '0' ? '' : $selector . ", #" . $options['menuid'] . "-wrap button.navbar-toggler { display: none !important; }") . "
	#" . $options['menuid'] . "-mobile-bar, #" . $options['menuid'] . "-mobile-bar-wrap-topfixed { display: block; flex: 1;}
	.mobilemenuck-hide {display: none !important;}
    " . $bodypadding . " }";
		} else if (($detect_type == 'tablet' && $detect->isMobile()) 
				|| ($detect_type == 'phone' && $detect->isMobile() && !$detect->isTablet())
				|| ($detect_type == 'resolutionanddevices' && $detect->isMobile())
				) {
			$css = $selector . ", #" . $options['menuid'] . "-wrap button.navbar-toggler { display: none !important; }
	#" . $options['menuid'] . "-mobile-bar, #" . $options['menuid'] . "-mobile-bar-wrap-topfixed { display: block; flex: 1;}
	.mobilemenuck-hide {display: none !important;}
    " . $bodypadding;
		} else if ($detect_type == 'all') {
			$css = "#" . $options['menuid'] . "-mobile-bar, #" . $options['menuid'] . "-mobile-bar-wrap-topfixed { display: block; flex: 1;}
	" . $selector . ", #" . $options['menuid'] . "-wrap button.navbar-toggler { display: none !important; }
	.mobilemenuck-hide {display: none !important;}
    " . $bodypadding;
		} else {
			$css = '';
		}

		return $css;
	}

	/**
	 * Determines what to use as character
	 *
	 * @return  string - the html value
	 *
	 */
	static function getButtonContent($value, $styleParams) {
		switch ($value) {
			case 'hamburger':
				$content = '&#x2261;';
				break;
			case 'close':
				$content = '×';
				break;
			case 'custom' :
				$content = $styleParams->menubarbuttoncontentcustomtext;
				break;
			default :
			case 'none':
				$content = '';
				break;
		}
		return $content;
	}
}