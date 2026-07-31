<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Associations;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

/** @var \Joomla\Component\Content\Site\View\Category\HtmlView $this */
$params = $this->item->params;
$canEdit = $this->item->params->get('access-edit');

$currentDate   = Factory::getDate()->format('Y-m-d H:i:s');
$isUnpublished = ($this->item->state == ContentComponent::CONDITION_UNPUBLISHED || $this->item->publish_up > $currentDate)
    || ($this->item->publish_down < $currentDate && $this->item->publish_down !== null);
?>

<div class="programme-card">
    <?php if ($isUnpublished) : ?>
        <div class="system-unpublished">
    <?php endif; ?>

    <?php echo LayoutHelper::render('joomla.content.intro_image', $this->item); ?>

    <div class="item-content">
        <?php echo LayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>

        <?php if ($canEdit) : ?>
            <?php echo LayoutHelper::render('joomla.content.icons', ['params' => $params, 'item' => $this->item]); ?>
        <?php endif; ?>

        <?php echo $this->item->event->beforeDisplayContent; ?>

        <div>
            <?php echo $this->item->introtext; ?>
        </div>

        <?php if ($params->get('show_readmore') && $this->item->readmore) :
            if ($params->get('access-view')) :
                $link = Route::_(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language));
            else :
                $menu = Factory::getApplication()->getMenu();
                $active = $menu->getActive();
                $itemId = $active->id;
                $link = new Uri(Route::_('index.php?option=com_users&view=login&Itemid=' . $itemId, false));
                $link->setVar('return', base64_encode(RouteHelper::getArticleRoute($this->item->slug, $this->item->catid, $this->item->language)));
            endif; ?>

            <?php echo LayoutHelper::render('joomla.content.readmore', ['item' => $this->item, 'params' => $params, 'link' => $link]); ?>

        <?php endif; ?>

        <?php echo $this->item->event->afterDisplayContent; ?>
    </div>

    <?php if ($isUnpublished) : ?>
        </div>
    <?php endif; ?>
</div>