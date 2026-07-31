<?php
/**
 * @package     JO Timeline
 * @subpackage  mod_jo_timeline
 *
 * @copyright   Copyright (C) 2025 Your Name. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

// Include the CSS file
$cssPath = Uri::base(true) . '/modules/mod_jo_timeline/assets/css/mod_jo_timeline.css';
\Joomla\CMS\Factory::getDocument()->addStyleSheet($cssPath);

// Include the JavaScript file
$jsPath = Uri::base(true) . '/modules/mod_jo_timeline/assets/js/mod_jo_timeline.js';
\Joomla\CMS\Factory::getDocument()->addScript($jsPath);

// Get module parameters
$markerColorLeft = $params->get('marker_color_left', '#007bff'); // Default blue for left markers
$markerColorRight = $params->get('marker_color_right', '#28a745'); // Default green for right markers
$mode = $params->get('mode', 'light'); // Default mode is light
$tlstyle = $params->get('tlstyle', 'zigzag1'); // Default style is zigzag

// Fetch timeline data from the helper
$timelineData = ModJoTimelineHelper::getTimelineData($params);

// Get visibility options (only applicable for Joomla Articles)
$source = $params->get('source', 'custom');
$showTime = ($source === 'articles') ? $params->get('show_time', 'on') === 'on' : true;
$showTitle = ($source === 'articles') ? $params->get('show_title', 'on') === 'on' : true;
$showDescription = ($source === 'articles') ? $params->get('show_description', 'on') === 'on' : true;
$showImage = ($source === 'articles') ? $params->get('show_image', 'on') === 'on' : true;

if (!empty($timelineData)) : ?>
    <div class="jo-timeline <?php echo $tlstyle; ?>" data-mode="<?php echo $mode; ?>">
        <?php $index = 0; // Initialize a counter ?>
        <?php foreach ($timelineData as $entry) : ?>
            <div class="timeline-item <?php echo ($index % 2 === 0) ? 'timeline-item-right' : 'timeline-item-left'; ?> <?php echo $tlstyle; ?>" role="listitem">
                <div class="timeline-marker <?php echo $tlstyle; ?>" aria-hidden="true" style="border-color: <?php echo ($index % 2 === 0) ? $markerColorRight : $markerColorLeft; ?>;"></div>
                <div class="timeline-content <?php echo $tlstyle; ?>" role="article">
                    <?php if ($showImage && !empty($entry['image'])) : ?>
                        <div class="timeline-image">
                            <img src="<?php echo htmlspecialchars($entry['image']); ?>" alt="<?php echo htmlspecialchars($entry['title']); ?>" />
                        </div>
                    <?php endif; ?>
                    <?php if ($showTime) : ?>
                        <div class="timeline-time <?php echo $tlstyle; ?>"><?php echo $entry['time']; ?></div>
                    <?php endif; ?>
                    <?php if ($showTitle) : ?>
                        <div class="timeline-title <?php echo $tlstyle; ?>"><?php echo $entry['title']; ?></div>
                    <?php endif; ?>
                    <?php if ($showDescription) : ?>
                        <div class="timeline-description <?php echo $tlstyle; ?>"><?php echo $entry['description']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php $index++; // Increment the counter ?>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p><?php echo \Joomla\CMS\Language\Text::_('MOD_JO_TIMELINE_NO_ENTRIES'); ?></p>
<?php endif; ?>