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

namespace RegularLabs\Plugin\EditorButton\Sourcerer\Extension;

use Joomla\CMS\Uri\Uri as JUri;
use RegularLabs\Library\Document as RL_Document;
use RegularLabs\Library\EditorButtonPlugin as RL_EditorButtonPlugin;

defined('_JEXEC') or die;

final class SourcererJ4 extends RL_EditorButtonPlugin
{
    protected $button_icon = '<svg viewBox="0 0 24 24" style="fill:none;" width="24" height="24" fill="none" stroke="currentColor">'
    . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />'
    . '</svg>';

    protected function loadScripts(): void
    {
        $params = $this->getParams();

        RL_Document::scriptOptions([
            'syntax_word'    => $params->syntax_word,
            'tag_characters' => explode('.', $params->tag_characters),
            'color_code'     => (bool) $params->color_code,
            'root'           => JUri::root(true),
        ], 'sourcerer_button');

        RL_Document::script('sourcerer.button');
    }
}
