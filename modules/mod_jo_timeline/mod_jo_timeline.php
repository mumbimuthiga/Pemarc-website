<?php
/**
 * @package     JO Timeline
 * @subpackage  mod_jo_timeline
 *
 * @copyright   Copyright (C) 2025 Your Name. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

// Include the helper file
require_once __DIR__ . '/helper.php';

// Fetch timeline data using the helper method
$timelineData = ModJoTimelineHelper::getTimelineData($params);

// Render the module template
require ModuleHelper::getLayoutPath('mod_jo_timeline', $params->get('layout', 'default'));

// If no timeline entries are available, display a message
if (empty($timelineData)) {
    echo '<p>' . Text::_('MOD_JO_TIMELINE_NO_ENTRIES') . '</p>';
}