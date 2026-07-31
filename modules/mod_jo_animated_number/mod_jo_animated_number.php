<?php
/**
 * @package     JO Animated Number
 * @subpackage  mod_jo_animated_number
 *
 * @copyright   Copyright (C) 2025 Your Name. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

require_once __DIR__ . '/helper.php';

$layout = $params->get('layout', 'default');

$moduleData = ModJOAnimatedNumberHelper::getData($params);

require ModuleHelper::getLayoutPath('mod_jo_animated_number', $layout);