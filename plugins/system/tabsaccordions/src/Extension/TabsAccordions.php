<?php
/**
 * @package         Tabs & Accordions
 * @version         2.5.6
 * 
 * @author          Peter van Westen <info@regularlabs.com>
 * @link            https://regularlabs.com
 * @copyright       Copyright © 2025 Regular Labs All Rights Reserved
 * @license         GNU General Public License version 2 or later
 */

namespace RegularLabs\Plugin\System\TabsAccordions\Extension;

use RegularLabs\Library\Html as RL_Html;
use RegularLabs\Library\Plugin\System as RL_SystemPlugin;
use RegularLabs\Plugin\System\TabsAccordions\Document;
use RegularLabs\Plugin\System\TabsAccordions\Params;
use RegularLabs\Plugin\System\TabsAccordions\Replace;

defined('_JEXEC') or die;

final class TabsAccordions extends RL_SystemPlugin
{
    public $_enable_in_admin = true;
    public $_lang_prefix     = 'RLTA';

    public function init(): void
    {
        $params = Params::get();

        $this->_enable_in_admin = $params->enable_admin;
    }

    public function processArticle(
        string &$string,
        string $area = 'article',
        string $context = '',
        mixed  $article = null,
        int    $page = 0
    ): void
    {
        Replace::render($string);
    }

    protected function changeDocumentBuffer(string &$buffer): bool
    {
        return Replace::render($buffer);
    }

    protected function changeFinalHtmlOutput(string &$html): bool
    {
        [$pre, $body, $post] = RL_Html::getBody($html);

        $body = Replace::render($body);

        $html = $pre . $body . $post;

        return true;
    }

    protected function cleanFinalHtmlOutput(string &$html): void
    {
        Document::removeHeadStuff($html);
    }

    /**
     * @param object $module
     * @param array  $params
     */
    protected function handleOnAfterRenderModule(object &$module, array &$params): void
    {
        if ( ! isset($module->content))
        {
            return;
        }

        Replace::render($module->content);
    }

    protected function loadStylesAndScripts(string &$buffer): void
    {
        Document::loadStylesAndScripts($buffer);
    }
}
