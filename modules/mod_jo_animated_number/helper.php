<?php
/**
 * @package     JO Animated Number
 * @subpackage  mod_jo_animated_number
 *
 * @copyright   Copyright (C) 2025 Your Name. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined('_JEXEC') or die;

class ModJOAnimatedNumberHelper
{
    public static function getData($params)
    {
        $data = new stdClass();

        $data->title = $params->get('title');
        $data->title_position = $params->get('title_position', 'top');
        $data->number = floatval($params->get('number', 0));
        $data->prefix = $params->get('prefix');
        $data->prefix_position = $params->get('prefix_position', 'before');
        $data->animation_duration = intval($params->get('duration', 2000));

        return $data;
    }
}