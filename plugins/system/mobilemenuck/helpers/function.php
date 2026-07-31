<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

/*
 * Global method that can be called easily
 */
function loadMobileMenuCK($selector, $options = array()) {
	return \Mobilemenuck\Menu::load($selector, $options);
}

/*
 * Global method that can be called easily, direct injection
 */
function loadMobileMenuCKInline($selector, $options = array()) {
	return \Mobilemenuck\Menu::load($selector, $options, true);
}

/*
 * Global method that can be called easily
 */
function loadThemeMobileMenuCK($id, $themeid = null) {
	return \Mobilemenuck\Menu::loadTheme($id, false, $themeid);
}

/*
 * Global method that can be called easily
 */
function loadThemeMobileMenuCKInline($id, $themeid = null) {
	return \Mobilemenuck\Menu::loadTheme($id, true, $themeid);
}

