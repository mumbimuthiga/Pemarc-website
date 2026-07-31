    <!-- DEBUG: Show raw urls property for this article -->
    <pre style="background:#fff;color:#c00;font-size:10px;overflow:auto;max-width:100%;max-height:100px;">URLS: <?php echo htmlspecialchars(var_export($item->urls, true)); ?></pre>
<?php
/**
 * @package     mod_jt_contentslider
 * @license     GNU/GPL
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Language\Text;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use Joomla\Module\JTContentSlider\Site\Helper\JTContentSliderHelper;

?>
<div class="jtcs_item_wrapper jt-cs"
     style="padding:<?php echo htmlspecialchars($params->get('content_padding'), ENT_QUOTES, 'UTF-8'); ?>;">

<div class="jtcs<?php echo (int) $module->id; ?> owl-carousel owl-theme slides_container">


<?php foreach ($list as $i => $item): ?>
<?php
    // Resolve final link (External → Secondary → Article)
    $customLink = '';
    $fields = FieldsHelper::getFields('com_content.article', $item, true);
    if (!empty($fields)) {
        foreach ($fields as $field) {
            if ($field->name === 'external_link' && !empty($field->rawvalue)) {
                $customLink = $field->rawvalue;
                break;
            }
            if ($field->name === 'secondary_link' && !empty($field->rawvalue)) {
                $customLink = $field->rawvalue;
            }
        }
    }
    $finalLink  = !empty($customLink) ? $customLink : $item->link;
    $isExternal = !empty($customLink);
    // Thumbnail
    $thumb_img = JTContentSliderHelper::getThumbnail(
        $item->id,
        $item->images,
        $thumb_folder,
        $show_default_thumb,
        $thumb_width,
        $thumb_height,
        $item->title,
        $item->introtext,
        $modulebase
    );
    // Use Joomla's built-in Link A and Link B from the article's urls property, with text and target
    $urls = isset($item->urls) ? json_decode($item->urls) : null;
    $linkA = ($urls && !empty($urls->urla)) ? $urls->urla : '';
    $linkAText = ($urls && !empty($urls->urlatext)) ? $urls->urlatext : '';
    $linkATarget = ($urls && isset($urls->urlatarget)) ? $urls->urlatarget : '';
    $linkB = ($urls && !empty($urls->urlb)) ? $urls->urlb : '';
    $linkBText = ($urls && !empty($urls->urlbtext)) ? $urls->urlbtext : '';
    $linkBTarget = ($urls && isset($urls->urlbtarget)) ? $urls->urlbtarget : '';

    if (!empty($linkA)) {
        $readMoreLink = $linkA;
        $readMoreText = !empty($linkAText) ? $linkAText : $params->get('ReadMoreText', 'Read More');
        // Joomla target: 0 = parent, 1 = new window, 2 = popup (rare)
        if ($linkATarget === '1') {
            $readMoreTarget = ' target="_blank" rel="noopener noreferrer"';
        } else {
            $readMoreTarget = '';
        }
    } elseif (!empty($linkB)) {
        $readMoreLink = $linkB;
        $readMoreText = !empty($linkBText) ? $linkBText : $params->get('ReadMoreText', 'Read More');
        if ($linkBTarget === '1') {
            $readMoreTarget = ' target="_blank" rel="noopener noreferrer"';
        } else {
            $readMoreTarget = '';
        }
    } else {
        $readMoreLink = $finalLink;
        $readMoreText = $params->get('ReadMoreText', 'Read More');
        $readMoreTarget = $isExternal ? ' target="_blank" rel="noopener noreferrer"' : '';
    }
?>
<div class="slide"
     style="padding:<?php echo htmlspecialchars($params->get('article_block_padding'), ENT_QUOTES, 'UTF-8'); ?>;
            margin:<?php echo htmlspecialchars($params->get('article_block_margin'), ENT_QUOTES, 'UTF-8'); ?>;"
     data-slide-index="<?php echo (int) $i; ?>">
    <div class="jt-inner">
        <?php if ($params->get('show_thumbnail')): ?>
            <div class="jt-imagecover">
                <?php echo $thumb_img; ?>
            </div>
        <?php endif; ?>
        <?php if ($params->get('show_title')): ?>
            <<?php echo htmlspecialchars($params->get('TitleClass'), ENT_QUOTES, 'UTF-8'); ?>
                class="jt-title">
                <?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
            </<?php echo htmlspecialchars($params->get('TitleClass'), ENT_QUOTES, 'UTF-8'); ?>>
        <?php endif; ?>
        <?php if ($params->get('show_introtext')): ?>
            <div class="jt-introtext">
                <?php
                if ($limit_intro_by === 'word' && $introtext_truncate > 0) {
                    echo JTContentSliderHelper::substrword(
                        $item->introtext,
                        $strip_tags,
                        $allowed_tags,
                        $replacer_text,
                        $introtext_truncate
                    );
                } elseif ($limit_intro_by === 'char' && $introtext_truncate > 0) {
                    echo JTContentSliderHelper::substring(
                        $item->introtext,
                        $strip_tags,
                        $allowed_tags,
                        $replacer_text,
                        $introtext_truncate
                    );
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
    <?php if ($params->get('showReadmore')): ?>
        <div class="jt-readmore-wrapper">
            <a class="btn btn-primary jt-readmore" href="<?php echo htmlspecialchars($readMoreLink, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $readMoreTarget; ?>>
                <?php echo htmlspecialchars($readMoreText, ENT_QUOTES, 'UTF-8'); ?>
            </a>
        </div>
    <?php endif; ?>
</div>
<?php endforeach; ?>
</a>
</div>