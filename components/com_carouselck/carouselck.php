<?php
/**
 * @name		Carousel CK
 * @package		com_carouselck
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */


// no direct access
defined('_JEXEC') or die;
if (! defined('CK_LOADED')) define('CK_LOADED', 1);

include_once JPATH_ADMINISTRATOR . '/components/com_carouselck/helpers/defines.php';

// Access check.
if (!\Joomla\CMS\Factory::getUser()->authorise('core.edit', 'com_carouselck')) {
	return JError::raiseWarning(404, \Carouselck\CKText::_('JERROR_ALERTNOAUTHOR'));
}

// loads the language files from the frontend
$lang	= \Carouselck\CKFof::getLanguage();
$lang->load('com_carouselck', JPATH_SITE . '/components/com_carouselck', $lang->getTag(), false);
$lang->load('com_carouselck', JPATH_SITE, $lang->getTag(), false);

// loads the helper in any case
include_once CAROUSELCK_PATH . '/helpers/cktext.php';
include_once CAROUSELCK_PATH . '/helpers/ckpath.php';
include_once CAROUSELCK_PATH . '/helpers/ckfile.php';
include_once CAROUSELCK_PATH . '/helpers/ckfolder.php';
include_once CAROUSELCK_PATH . '/helpers/ckuri.php';
include_once CAROUSELCK_PATH . '/helpers/ckfof.php';
include_once CAROUSELCK_PATH . '/helpers/helper.php';
include_once CAROUSELCK_PATH . '/helpers/ckframework.php';
include_once CAROUSELCK_PATH . '/helpers/ckcontroller.php';
include_once CAROUSELCK_PATH . '/helpers/ckmodel.php';
include_once CAROUSELCK_PATH . '/helpers/ckview.php';

\Carouselck\CKFramework::load();

// Include dependancies
include_once CAROUSELCK_PATH . '/controller.php';

$controller	= \Joomla\CMS\MVC\Controller\BaseController::getInstance('Carouselck');
$controller->execute(\Carouselck\CKFof::getApplication()->input->get('task'));
