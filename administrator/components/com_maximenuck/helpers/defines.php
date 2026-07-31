<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/ckloader.php';

// set variables
define('MAXIMENUCK_PLATFORM', 'joomla');
define('MAXIMENUCK_PATH', JPATH_SITE . '/administrator/components/com_maximenuck');
define('MAXIMENUCK_ADMIN_PATH', MAXIMENUCK_PATH);
define('MAXIMENUCK_BASE_PATH', JPATH_BASE . '/components/com_maximenuck');
define('MAXIMENUCK_FRONT_PATH', JPATH_SITE . '/components/com_maximenuck');
define('MAXIMENUCK_PROJECTS_PATH', JPATH_SITE . '/administrator/components/com_maximenuck/projects');
define('MAXIMENUCK_ADMIN_URL', \Maximenuck\CKUri::root(true) . '/administrator/index.php?option=com_maximenuck');
define('MAXIMENUCK_URL', \Maximenuck\CKUri::base(true) . '/index.php?option=com_maximenuck');
define('MAXIMENUCK_ADMIN_GENERAL_URL', \Maximenuck\CKUri::root(true) . '/administrator/index.php?option=com_maximenuck&view=templates');
define('MAXIMENUCK_MEDIA_URI', \Maximenuck\CKUri::root(true) . '/media/com_maximenuck');
define('MAXIMENUCK_MEDIA_URL', MAXIMENUCK_MEDIA_URI);
define('MAXIMENUCK_MEDIA_PATH', JPATH_ROOT . '/media/com_maximenuck');
define('MAXIMENUCK_PLUGIN_URL', MAXIMENUCK_MEDIA_URI);
define('MAXIMENUCK_TEMPLATES_PATH', JPATH_SITE . '/templates');
define('MAXIMENUCK_SITE_ROOT', JPATH_ROOT);
define('MAXIMENUCK_URI', \Maximenuck\CKUri::root(true) . '/administrator/components/com_maximenuck');
define('MAXIMENUCK_URI_ROOT', \Maximenuck\CKUri::root(true));
define('MAXIMENUCK_URI_BASE', \Maximenuck\CKUri::base(true));
define('MAXIMENUCK_PLUGINS_PATH', JPATH_SITE . '/plugins/maximenuck');
define('MAXIMENUCK_ISJ4', version_compare(JVERSION, "4") >= 0);
