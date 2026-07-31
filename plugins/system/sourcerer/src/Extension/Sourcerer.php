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

namespace RegularLabs\Plugin\System\Sourcerer\Extension;

use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\Html as RL_Html;
use RegularLabs\Library\Plugin\System as RL_SystemPlugin;
use RegularLabs\Library\Protect as RL_Protect;
use RegularLabs\Plugin\System\Sourcerer\Area;
use RegularLabs\Plugin\System\Sourcerer\Clean;
use RegularLabs\Plugin\System\Sourcerer\Params;
use RegularLabs\Plugin\System\Sourcerer\Protect;
use RegularLabs\Plugin\System\Sourcerer\Replace;
use RegularLabs\Plugin\System\Sourcerer\Security;

defined('_JEXEC') or die;

final class Sourcerer extends RL_SystemPlugin
{
    public $_can_disable_by_url = false;
    public $_lang_prefix        = 'SRC';

    protected function changeDocumentBuffer(string &$buffer): bool
    {
        if ( ! RL_Document::isHtml())
        {
            return false;
        }

        return Area::tag($buffer, 'component');
    }

    protected function changeFinalHtmlOutput(string &$html): bool
    {
        $params = Params::get();

        [$pre, $body, $post] = RL_Html::getBody($html);

        Protect::_($body);
        Replace::replaceInTheRest($body);

        Clean::cleanFinalHtmlOutput($body);
        RL_Protect::unprotect($body);

        $params->enable_in_head
            ? Replace::replace($pre, 'head')
            : Clean::cleanTagsFromHead($pre);

        $html = $pre . $body . $post;

        return true;
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

        Replace::replace($module->content, 'module');
    }

    protected function handleOnContentPrepare(
        string $area,
        string $context,
        mixed  &$article,
        mixed  &$params,
        int    $page = 0
    ): bool
    {
        $src_params = Params::get();

        $area = isset($article->created_by) ? 'articles' : 'other';

        $remove = $src_params->remove_from_search
            && in_array($context, ['com_search.search', 'com_search.search.article', 'com_finder.indexer']);


        if (isset($article->description))
        {
            Replace::replace($article->description, $area, $article, $remove);
        }

        if (isset($article->title))
        {
            Replace::replace($article->title, $area, $article, $remove);
        }

        // Don't handle article texts in category list view
        if (RL_Document::isCategoryList($context))
        {
            return false;
        }

        if (isset($article->text))
        {
            Replace::replace($article->text, $area, $article, $remove);

            // Don't also do stuff on introtext/fulltext if text is set
            return false;
        }

        if (isset($article->introtext))
        {
            Replace::replace($article->introtext, $area, $article, $remove);
        }

        if (isset($article->fulltext))
        {
            Replace::replace($article->fulltext, $area, $article, $remove);
        }

        return false;
    }
}
