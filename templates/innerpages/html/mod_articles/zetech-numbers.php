<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles
 *
 * @copyright   (C) 2026 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Language\Text;

// Zetech in Numbers category ID
$zetechCategoryId = 9;

// Check if we have articles
if (empty($list)) {
    return;
}

// Detect category from first article
$currentCategoryId = (int) $list[0]->catid;

// If not "Zetech in Numbers" category, fallback to default layout
if ($currentCategoryId !== $zetechCategoryId) {
    require ModuleHelper::getLayoutPath('mod_articles', 'default');
    return;
}

// Include module CSS if needed
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->registerAndUseStyle('mod_articles', 'mod_articles/mod-articles.css');

?>

<section class="zetech-numbers">
    <div class="container">
        <div class="row g-3">
            <?php foreach ($list as $item) : ?>
                <div class="col-lg-3 col-md-6 col-sm-12">
                    <div class="number-box">
                        <div class="number"><?php echo $item->title; ?></div>
                        <div class="number-title"><?php echo $item->params->get('alternative_readmore', ''); ?></div>
                        <p><?php echo $item->introtext; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


