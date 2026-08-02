<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_articles', 'mod_articles/mod-articles.css');



if (!$list) {
    return;
}

// ID of Zetech in Numbers category
$zetechCategoryId = 9;

// Check if module is showing "Zetech in Numbers" only
$isZetechNumbers = !empty($items) && ((int) $items[0]->catid === $zetechCategoryId);

// Heading tag logic
$groupHeading = 'h4';
if ((bool) $module->showtitle) {
    $modTitle = $params->get('header_tag');
    if ($modTitle == 'h1')
        $groupHeading = 'h2';
    elseif ($modTitle == 'h2')
        $groupHeading = 'h3';
}

// Standard layout suffix
$layoutSuffix = $params->get('title_only', 0) ? '_titles' : '_items';

?>

<?php if ($isZetechNumbers): ?>
    <!-- Zetech in Numbers custom layout -->
    <div class="zetech-numbers">
        <div class="container">
            <div class="row">
                <?php foreach ($list as $item): ?>
                    <div class="col-lg-3 col-md-6 col-6">
                        <div class="number-box">
                            <div class="number"><?php echo $item->title; ?></div>
                            <div class="number-title"><?php echo $item->introtext; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <!-- Default module layout -->
    <?php if ($grouped): ?>
        <?php foreach ($list as $groupName => $items): ?>
            <div class="mod-articles-group">
                <<?php echo $groupHeading; ?>><?php echo Text::_($groupName); ?></<?php echo $groupHeading; ?>>
                <?php require ModuleHelper::getLayoutPath('mod_articles', $params->get('layout', 'default') . $layoutSuffix); ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php $items = $list; ?>
        <?php require ModuleHelper::getLayoutPath('mod_articles', $params->get('layout', 'default') . $layoutSuffix); ?>
    <?php endif; ?>
<?php endif; ?>