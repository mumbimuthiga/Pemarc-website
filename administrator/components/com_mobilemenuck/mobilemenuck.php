<?php
/**
 * @name		Mobile Menu CK
 * @package		com_mobilemenuck
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */


// no direct access
defined('_JEXEC') or die;

if (! file_exists(JPATH_ROOT . '/plugins/system/mobilemenuck/helpers/helper.php')) {
	echo 'Plugin Mobile Menu CK not found. Helper.php file missing, operation aborted.';
	return;
}

if (! \Joomla\CMS\Plugin\PluginHelper::isEnabled('system', 'mobilemenuck')) {
	echo 'Plugin Mobile Menu CK not enabled. Please enable the plugin. Operation aborted.';
	return;
}

// Access check.
if (!\Joomla\CMS\Factory::getUser()->authorise('core.manage', 'com_mobilemenuck')) {
	return JError::raiseWarning(404, \Mobilemenuck\CKText::_('JERROR_ALERTNOAUTHOR'));
}

// set variables
//include_once(JPATH_ROOT . '/plugins/system/mobilemenuck/defines.php');
include_once JPATH_ADMINISTRATOR . '/components/com_mobilemenuck/helpers/defines.php';

require_once MOBILEMENUCK_ADMIN_PATH . '/helpers/ckparams.php';
require_once MOBILEMENUCK_ADMIN_PATH . '/helpers/ckcontroller.php';
require_once MOBILEMENUCK_ADMIN_PATH . '/helpers/ckmodel.php';
require_once MOBILEMENUCK_ADMIN_PATH . '/helpers/ckview.php';
require_once(JPATH_ROOT . '/plugins/system/mobilemenuck/helpers/helper.php');

// loads the language files from the frontend
$lang	= \Mobilemenuck\CKFof::getLanguage();
$lang->load('com_mobilemenuck', MOBILEMENUCK_SITE_PATH, $lang->getTag(), false);

// loads the helper in any case
include_once MOBILEMENUCK_ADMIN_PATH . '/helpers/helper.php';

// Include dependancies
//jimport('joomla.application.component.controller');

//$controller	= \Joomla\CMS\MVC\Controller\BaseController::getInstance('Mobilemenuck');
//$controller->execute(\Mobilemenuck\CKFof::getApplication()->input->get('task'));
//$controller->redirect();

$input = Mobilemenuck\CKFof::getInput();

$controller	= Mobilemenuck\CKController::getInstance('Mobilemenuck');
$controller->execute($input->get('task'));
