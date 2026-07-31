<?php
/**
 * @package     mod_jt_contentslider
 * @copyright   Copyright (C) 2007 - 2024 http://www.joomlatema.net, Inc. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @author      JoomlaTema.Net
 * @link        http://www.joomlatema.net
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Module\JTContentSlider\Site\Helper\JTContentSliderHelper;

// Joomla 4/5/6 compatibility for Filesystem classes
if (!class_exists('JoomlaFilesystemFolder')) {
    if (class_exists('Joomla\Filesystem\Folder')) {
        class_alias('Joomla\Filesystem\Folder', 'JoomlaFilesystemFolder');
        class_alias('Joomla\Filesystem\File', 'JoomlaFilesystemFile');
    } elseif (class_exists('Joomla\CMS\Filesystem\Folder')) {
        class_alias('Joomla\CMS\Filesystem\Folder', 'JoomlaFilesystemFolder');
        class_alias('Joomla\CMS\Filesystem\File', 'JoomlaFilesystemFile');
    }
}


// Load jQuery and Bootstrap Modal
HTMLHelper::_('jquery.framework');
HTMLHelper::_('bootstrap.renderModal');

// Include the helper
require_once __DIR__ . '/helper/jtcontentsliderhelper.php';

$app = Factory::getApplication();
$doc = $app->getDocument();

$modulebase = "mod_jt_contentslider";
$thumb_folder = "/cache/" . $modulebase . "/";

// Create thumbnail folder if not exist
if (!JoomlaFilesystemFolder::exists(JPATH_BASE . $thumb_folder)) {
    JoomlaFilesystemFolder::create(JPATH_BASE . $thumb_folder);
    JoomlaFilesystemFile::write(JPATH_BASE . $thumb_folder . 'index.html', "");
}

// Get the articles model
$model = $app->bootComponent('com_content')->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);
$list = JTContentSliderHelper::getList($params, $model);

// Get module parameters
$show_introtext = $params->get('show_introtext', 1);
$thumb_width = $params->get('thumb_width', 300);
$introtext_truncate = $params->get('limit_intro', 200);
$limit_title = $params->get('limit_title', 25);
$show_morecat_links = $params->get('show_more_in', 1);
$show_date = $params->get('show_date', 1);
$show_date_type = $params->get('show_date_type', 1);
$custom_date_format = $params->get('custom_date_format', "");
$show_default_thumb = $params->get('show_default_thumb', 0);
$use_caption = $params->get('use_caption', 0);
$limit_intro_by = $params->get('limit_intro_by', 'char');
$limit_title_by = $params->get('limit_title_by', 'char');
$replacer_text = $params->get('replacer_text', '...');
$strip_tags = $params->get('strip_tags', 1);
$allowed_tags = $params->get('allowed_tags', '');
$replacertitle = $params->get('replacer', '...');

// Get thumbnail height with aspect ratio support
$tmp = $params->get('keep_aspect_ratio', 'true');
$tmp2 = $params->get('thumb_height', 200);
$thumb_height = ($tmp == 'true') ? '' : (int)$tmp2;

// Get open target
$openTarget = $params->get('open_target', '_parent');

// Thumbnail path
$thumbPath = JPATH_BASE . '/cache/' . $module->module . '/';

// Load module assets
$doc->addStyleSheet(Uri::base(true) . '/modules/' . $modulebase . '/tmpl/assets/css/style.css');
$doc->addStyleSheet(Uri::base(true) . '/modules/' . $modulebase . '/tmpl/assets/css/lightbox.css');
$doc->addScript(Uri::base(true) . '/modules/' . $modulebase . '/tmpl/assets/js/lightbox-plus-jquery.js');
$doc->addScript(Uri::base(true) . '/modules/' . $modulebase . '/tmpl/assets/js/owl.carousel.js');

// Load the layout
require ModuleHelper::getLayoutPath($modulebase, $params->get('layout', 'default'));