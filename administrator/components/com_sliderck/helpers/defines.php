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

include_once JPATH_ROOT . '/administrator/components/com_sliderck/helpers/ckloader.php';

// set variables
define('SLIDERCK_PLATFORM', 'joomla');
define('SLIDERCK_PATH', JPATH_SITE . '/administrator/components/com_sliderck');
define('SLIDERCK_ADMIN_PATH', SLIDERCK_PATH);
define('SLIDERCK_FRONT_PATH', JPATH_SITE . '/components/com_sliderck');
define('SLIDERCK_PROJECTS_PATH', JPATH_SITE . '/administrator/components/com_sliderck/projects');
define('SLIDERCK_ADMIN_URL', \Sliderck\CKUri::root(true) . '/administrator/index.php?option=com_sliderck');
define('SLIDERCK_URL', \Sliderck\CKUri::base(true) . '/index.php?option=com_sliderck');
define('SLIDERCK_ADMIN_GENERAL_URL', \Sliderck\CKUri::root(true) . '/administrator/index.php?option=com_sliderck&view=templates');
define('SLIDERCK_MEDIA_URI', \Sliderck\CKUri::root(true) . '/media/com_sliderck');
define('SLIDERCK_MEDIA_URL', SLIDERCK_MEDIA_URI);
define('SLIDERCK_MEDIA_PATH', JPATH_ROOT . '/media/com_sliderck');
define('SLIDERCK_PLUGIN_URL', SLIDERCK_MEDIA_URI);
define('SLIDERCK_TEMPLATES_PATH', JPATH_SITE . '/templates');
define('SLIDERCK_SITE_ROOT', JPATH_ROOT);
define('SLIDERCK_URI', \Sliderck\CKUri::root(true) . '/administrator/components/com_sliderck');
define('SLIDERCK_URI_ROOT', \Sliderck\CKUri::root(true));
define('SLIDERCK_URI_BASE', \Sliderck\CKUri::base(true));
define('SLIDERCK_PLUGINS_PATH', JPATH_SITE . '/plugins/sliderck');

