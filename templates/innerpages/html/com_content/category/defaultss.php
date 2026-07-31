<?php
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Component\Content\Site\Helper\RouteHelper as ContentHelperRoute;

// ---------------------------------------------------------------------
// Bootstrap
// ---------------------------------------------------------------------
$app  = Factory::getApplication();
$menu = $app->getMenu()->getActive();

// If no active menu item, abort safely
if (!$menu) {
    echo '<p class="alert alert-danger">No active menu item.</p>';
    return;
}

// Menu parameter used for filtering (custom)
$programmeType = $menu->getParams()->get('programme_type');

if (!$programmeType) {
    echo '<p class="alert alert-warning">Programme type is not configured for this menu item.</p>';
    return;
}

// Articles loaded by Joomla
$items = $this->items ?? [];
?>

<div class="programmes-layout">

    <!-- Sidebar navigation (menu-driven, Joomla-safe) -->
    <aside class="programme-sidebar">
        <ul class="programme-nav">
            <?php
            $menuItems = $app->getMenu()->getItems('component', 'com_content');

            if ($menuItems) :
                foreach ($menuItems as $menuItem) :
                    // Joomla 4/5 SAFE parameter access
                    $params = $menuItem->getParams();
                    $type   = $params->get('programme_type');

                    if (!$type) {
                        continue;
                    }
            ?>
                <li class="<?= $menuItem->id === $menu->id ? 'active' : ''; ?>">
                    <a href="<?= Route::_($menuItem->link); ?>">
                        <?= htmlspecialchars($menuItem->title, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </li>
            <?php
                endforeach;
            endif;
            ?>
        </ul>
    </aside>

    <!-- Main content -->
    <main class="programmes-grid">

        <?php
        $hasResults = false;

        foreach ($items as $item) :

            // Custom field safety check
            $programmeField = $item->jcfields['programme_type'] ?? null;

            if (
                !$programmeField ||
                $programmeField->value !== $programmeType
            ) {
                continue;
            }

            $hasResults = true;
        ?>
            <article class="programme-card">

                <h2 class="programme-title">
                    <a href="<?= Route::_(
                        ContentHelperRoute::getArticleRoute($item->slug, $item->catid)
                    ); ?>">
                        <?= htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                </h2>

                <div class="programme-intro">
                    <?= $item->introtext; ?>
                </div>

            </article>
        <?php endforeach; ?>

        <?php if (!$hasResults) : ?>
            <p class="alert alert-info">No programmes found.</p>
        <?php endif; ?>

    </main>
</div>
