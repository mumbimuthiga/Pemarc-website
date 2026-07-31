<?php
/**
 * @package         Sourcerer
 * @version         12.2.7
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://regularlabs.com
 * @copyright       Copyright © 2025 Regular Labs All Rights Reserved
 * @license         GNU General Public License version 2 or later
 */

namespace RegularLabs\Plugin\System\Sourcerer;

defined('_JEXEC') or die;

use RegularLabs\Library\User as RL_User;

class Security
{
    protected static $security = null;

    public static function get(): object
    {
        if ( ! is_null(self::$security))
        {
            return self::$security;
        }

        self::$security = (object) [
            'pass'     => true,
            'pass_css' => true,
            'pass_js'  => true,
            'pass_php' => true,
        ];

        return self::$security;
    }

    public static function set(object|false|null $article = null): void
    {
        if ( ! isset($article->created_by))
        {
            return;
        }

        $params = Params::get();

        $security_level = (array) $params->articles_security_level;
        $security_css   = $params->articles_security_level_default_css
            ? (array) $params->articles_security_level
            : (array) $params->articles_security_level_css;
        $security_js    = $params->articles_security_level_default_js
            ? (array) $params->articles_security_level
            : (array) $params->articles_security_level_js;
        $security_php   = $params->articles_security_level_default_php
            ? (array) $params->articles_security_level
            : (array) $params->articles_security_level_php;

        $user  = RL_User::get();
        $table = $user->getTable();

        if ($table->load($article->created_by))
        {
            $user = RL_User::get($article->created_by);
        }

        $groups = $user->getAuthorisedGroups();
        array_unshift($groups, -1);

        // Set if security is passed
        // passed = creator is equal or higher than security group level
        $security           = (object) [];
        $pass               = array_intersect($security_level, $groups);
        $security->pass     = ( ! empty($pass));
        $pass               = array_intersect($security_css, $groups);
        $security->pass_css = ( ! empty($pass));
        $pass               = array_intersect($security_js, $groups);
        $security->pass_js  = ( ! empty($pass));
        $pass               = array_intersect($security_php, $groups);
        $security->pass_php = ( ! empty($pass));

        self::$security = $security;
    }
}
