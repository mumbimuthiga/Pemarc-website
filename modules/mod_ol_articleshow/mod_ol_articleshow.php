<?php
/**
 * @package Article Show
 * @version 4.1.4
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @copyright (c) 2025 Olwebdesign. All Rights Reserved.
 * @author Olwebdesign http://www.olwebdesign.com
 * 
 */
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

if(!isset($params) || !(count($params) > 0)) return;

if (JVERSION >= 4) require_once dirname(__FILE__).'/core/helper4.php';
if (JVERSION < 4) require_once dirname(__FILE__).'/core/helper.php';

$layout_name = $params->get('layout', 'default');
$cacheid = md5(serialize(array ($layout_name, $module->module)));
$cacheparams = new stdClass;
$cacheparams->cachemode = 'id';
$cacheparams->class = 'OlArticleShowHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = $cacheid;
$list = ModuleHelper::moduleCache ($module, $params, $cacheparams);
$moduleclass_sfx = $params->get('moduleclass_sfx');
require ModuleHelper::getLayoutPath('mod_ol_articleshow', $params->get('get_style', 'default'));
