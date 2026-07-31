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

class CKLoader
{

	public static function loadScriptDeclaration($js) {
		$doc = \Joomla\CMS\Factory::getDocument();
		$doc->addScriptDeclaration($js);
	}

	public static function loadScriptDeclarationInline($js) {
		echo '<script>' . $js . '</script>';
	}

	public static function loadScript($file) {
		$doc = \Joomla\CMS\Factory::getDocument();
		$doc->addScript($file);
	}

	public static function loadScriptInline($file) {
		echo '<script src="' . $file . '"></script>';
	}

	public static function loadStyleDeclaration($css) {
		$doc = \Joomla\CMS\Factory::getDocument();
		$doc->addStyleDeclaration($css);
	}

	public static function loadStyleDeclarationInline($css) {
		echo '<style>' . $css . '</style>';
	}

	public static function loadStylesheet($file) {
		$doc = \Joomla\CMS\Factory::getDocument();
		$doc->addStylesheet($file);
	}

	public static function loadStylesheetInline($file) {
		echo '<link href="' . $file . '"" rel="stylesheet" />';
	}
}