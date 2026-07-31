<?php
/**
 * @name		Slideshow CK
 * @package		com_slideshowck
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */


// no direct access
defined('_JEXEC') or die;
if (! defined('CK_LOADED')) define('CK_LOADED', 1);

// use Slideshowck\CKFof;

include_once JPATH_ADMINISTRATOR . '/components/com_slideshowck/helpers/defines.php';

// Access check.
if (!\Joomla\CMS\Factory::getUser()->authorise('core.edit', 'com_slideshowck')) {
	return JError::raiseWarning(404, Slideshowck\CKText::_('JERROR_ALERTNOAUTHOR'));
}

// loads the language files from the frontend
$lang	= Slideshowck\CKFof::getLanguage();
$lang->load('com_slideshowck', JPATH_SITE . '/components/com_slideshowck', $lang->getTag(), false);
$lang->load('com_slideshowck', JPATH_SITE, $lang->getTag(), false);

// loads the helper in any case
require_once SLIDESHOWCK_PATH . '/helpers/ckloader.php';
require_once SLIDESHOWCK_PATH . '/helpers/helper.php';
require_once SLIDESHOWCK_PATH . '/helpers/ckframework.php';
require_once SLIDESHOWCK_PATH . '/helpers/ckcontroller.php';
require_once SLIDESHOWCK_PATH . '/helpers/ckmodel.php';
require_once SLIDESHOWCK_PATH . '/helpers/ckview.php';

\Slideshowck\CKFramework::load();

// Include dependancies
require_once SLIDESHOWCK_PATH . '/controller.php';

$controller	= \Slideshowck\CKController::getInstance('Slideshowck');
$controller->execute(Slideshowck\CKFof::getApplication()->input->get('task'));
//$controller->redirect();
