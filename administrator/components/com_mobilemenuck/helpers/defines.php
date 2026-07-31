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

include_once JPATH_ROOT . '/administrator/components/com_mobilemenuck/helpers/ckloader.php';

// set variables
	define('MOBILEMENUCK_BASE_PATH', JPATH_BASE . '/components/com_mobilemenuck');
	define('MOBILEMENUCK_ADMIN_GENERAL_URL', \Mobilemenuck\CKUri::root(true) . '/administrator/index.php?option=com_mobilemenuck');
// for the plugin needs
define('MOBILEMENUCK_PLATFORM', 'joomla');
define('MOBILEMENUCK_PATH', JPATH_ROOT . '/plugins/system/mobilemenuck');
define('MOBILEMENUCK_MEDIA_URI', \Mobilemenuck\CKUri::root(true) . '/media/com_mobilemenuck');
define('MOBILEMENUCK_PLUGIN_MEDIA_URI', \Mobilemenuck\CKUri::root(true) . '/media/plg_system_mobilemenuck');
define('MOBILEMENUCK_SITE_ROOT', JPATH_ROOT);
define('MOBILEMENUCK_URI_ROOT', \Mobilemenuck\CKUri::root(true));
define('MOBILEMENUCK_URI_BASE', \Mobilemenuck\CKUri::base(true));

if (!defined('MOBILEMENUCK_ADMIN_PATH'))
{
define('MOBILEMENUCK_ADMIN_PATH', JPATH_SITE . '/administrator/components/com_mobilemenuck');
}

if (!defined('MOBILEMENUCK_MEDIA_PATH'))
{
define('MOBILEMENUCK_MEDIA_PATH', JPATH_SITE . '/media/com_mobilemenuck');
}

if (!defined('MOBILEMENUCK_SITE_PATH'))
{
define('MOBILEMENUCK_SITE_PATH', JPATH_SITE . '/components/com_mobilemenuck');
}

if (!defined('MOBILEMENUCK_ADMIN_URI'))
{
define('MOBILEMENUCK_ADMIN_URI', \Mobilemenuck\CKUri::root(true) . '/administrator/?option=com_mobilemenuck');
}

if (!defined('MOBILEMENUCK_MEDIA_URI'))
{
define('MOBILEMENUCK_MEDIA_URI', \Mobilemenuck\CKUri::root(true) . '/media/com_mobilemenuck');
}
