<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_ROOT . '/administrator/components/com_slideshowck/helpers/ckloader.php';

// set variables
define('SLIDESHOWCK_PLATFORM', 'joomla');
define('SLIDESHOWCK_PATH', JPATH_SITE . '/administrator/components/com_slideshowck');
define('SLIDESHOWCK_ADMIN_PATH', SLIDESHOWCK_PATH);
define('SLIDESHOWCK_FRONT_PATH', JPATH_SITE . '/components/com_slideshowck');
define('SLIDESHOWCK_PROJECTS_PATH', JPATH_SITE . '/administrator/components/com_slideshowck/projects');
define('SLIDESHOWCK_ADMIN_URL', Slideshowck\CKUri::root(true) . '/administrator/index.php?option=com_slideshowck');
define('SLIDESHOWCK_URL', Slideshowck\CKUri::base(true) . '/index.php?option=com_slideshowck');
define('SLIDESHOWCK_ADMIN_GENERAL_URL', Slideshowck\CKUri::root(true) . '/administrator/index.php?option=com_slideshowck&view=templates');
define('SLIDESHOWCK_MEDIA_URI', Slideshowck\CKUri::root(true) . '/media/com_slideshowck');
define('SLIDESHOWCK_MEDIA_URL', SLIDESHOWCK_MEDIA_URI);
define('SLIDESHOWCK_MEDIA_PATH', JPATH_ROOT . '/media/com_slideshowck');
define('SLIDESHOWCK_PLUGIN_URL', SLIDESHOWCK_MEDIA_URI);
define('SLIDESHOWCK_TEMPLATES_PATH', JPATH_SITE . '/templates');
define('SLIDESHOWCK_SITE_ROOT', JPATH_ROOT);
define('SLIDESHOWCK_URI', Slideshowck\CKUri::root(true) . '/administrator/components/com_slideshowck');
define('SLIDESHOWCK_URI_ROOT', Slideshowck\CKUri::root(true));
define('SLIDESHOWCK_URI_BASE', Slideshowck\CKUri::base(true));
define('SLIDESHOWCK_PLUGINS_PATH', JPATH_SITE . '/plugins/slideshowck');
define('SLIDESHOWCK_VERSION', simplexml_load_file(SLIDESHOWCK_PATH . '/slideshowck.xml')->version);

