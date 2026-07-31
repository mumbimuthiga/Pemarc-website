<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

include_once JPATH_ADMINISTRATOR . '/components/com_carouselck/helpers/ckloader.php';

// set variables
define('CAROUSELCK_PLATFORM', 'joomla');
define('CAROUSELCK_VERSION', '2.0.0');
define('CAROUSELCK_PATH', JPATH_SITE . '/administrator/components/com_carouselck');
define('CAROUSELCK_ADMIN_PATH', CAROUSELCK_PATH);
define('CAROUSELCK_FRONT_PATH', JPATH_SITE . '/components/com_carouselck');
define('CAROUSELCK_PROJECTS_PATH', JPATH_SITE . '/administrator/components/com_carouselck/projects');
define('CAROUSELCK_ADMIN_URL', \Carouselck\CKUri::root(true) . '/administrator/index.php?option=com_carouselck');
define('CAROUSELCK_URL', \Carouselck\CKUri::base(true) . '/index.php?option=com_carouselck');
define('CAROUSELCK_ADMIN_GENERAL_URL', \Carouselck\CKUri::root(true) . '/administrator/index.php?option=com_carouselck&view=templates');
define('CAROUSELCK_MEDIA_URI', \Carouselck\CKUri::root(true) . '/media/com_carouselck');
define('CAROUSELCK_MEDIA_URL', CAROUSELCK_MEDIA_URI);
define('CAROUSELCK_MEDIA_PATH', JPATH_ROOT . '/media/com_carouselck');
define('CAROUSELCK_PLUGIN_URL', CAROUSELCK_MEDIA_URI);
define('CAROUSELCK_TEMPLATES_PATH', JPATH_SITE . '/templates');
define('CAROUSELCK_SITE_ROOT', JPATH_ROOT);
define('CAROUSELCK_URI', \Carouselck\CKUri::root(true) . '/administrator/components/com_carouselck');
define('CAROUSELCK_URI_ROOT', \Carouselck\CKUri::root(true));
define('CAROUSELCK_URI_BASE', \Carouselck\CKUri::base(true));
define('CAROUSELCK_PLUGINS_PATH', JPATH_SITE . '/plugins/carouselck');
