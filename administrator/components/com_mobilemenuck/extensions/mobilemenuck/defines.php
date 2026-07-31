<?php
// set variables
define('MOBILEMENUCK_LOADED', 1);
define('MOBILEMENUCK_PLATFORM', 'joomla');
define('MOBILEMENUCK_PATH', dirname(__FILE__));
define('MOBILEMENUCK_MEDIA_URI', \Mobilemenuck\CKUri::root(true) . '/media/com_mobilemenuck');
define('MOBILEMENUCK_PLUGIN_MEDIA_URI', \Mobilemenuck\CKUri::root(true) . '/media/plg_system_mobilemenuck');
define('MOBILEMENUCK_SITE_ROOT', JPATH_ROOT);
define('MOBILEMENUCK_URI_ROOT', \Mobilemenuck\CKUri::root(true));
define('MOBILEMENUCK_URI_BASE', \Mobilemenuck\CKUri::base(true));