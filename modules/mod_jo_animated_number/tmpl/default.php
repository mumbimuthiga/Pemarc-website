<?php
/**
 * @package     JO Animated Number
 * @subpackage  mod_jo_animated_number
 *
 * @copyright   Copyright (C) 2025 Your Name. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined('_JEXEC') or die;

use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

// Get module parameters
$targetNumber = floatval($params->get('number', 75)); // default to 75
$title = $params->get('title');
$titlePosition = $params->get('title_position', 'top');
$prefix = $params->get('prefix');
$prefixPosition = $params->get('prefix_position', 'before');
$animationDuration = intval($params->get('animation_duration', 1000));
$animationType = $params->get('animation_type', 'circular');

// Style settings
$numberFontSize = $params->get('number_font_size', '24px');
$numberColor = $params->get('number_color', '#333333');

$prefixFontSize = $params->get('prefix_font_size', '20px');
$prefixColor = $params->get('prefix_color', '#333333');

$titleFontSize = $params->get('title_font_size', '20px');
$titleColor = $params->get('title_color', '#333333');
$animationColor = $params->get('animation_color', '#2196F3');

// Load CSS
$cssPath = Uri::base(true) . '/modules/mod_jo_animated_number/assets/css/mod_jo_animated_number.css';
\Joomla\CMS\Factory::getDocument()->addStyleSheet($cssPath);

// Inject module settings into JS using unique ID per module
$jsOptions = [
    'targetNumber' => $targetNumber,
    'animationType' => $animationType,
    'animationDuration' => $animationDuration,
    'prefixPosition' => $prefixPosition,
    'animationColor' => $animationColor,
    'moduleId' => $module->id,
];
\Joomla\CMS\Factory::getDocument()->addScriptOptions('mod_jo_animated_number_' . $module->id, $jsOptions);

// Load JS
$jsPath = Uri::base(true) . '/modules/mod_jo_animated_number/assets/js/mod_jo_animated_number.js';
\Joomla\CMS\Factory::getDocument()->addScript($jsPath);
?>

<div class="jo-animated-progress-module<?php echo ($titlePosition === 'bottom') ? ' titleb' : ''; ?>" id="jo-animated-<?php echo $module->id; ?>">

    <?php if ($titlePosition === 'top' && !empty($title)) : ?>
        <div class="title"><?php echo htmlspecialchars($title); ?></div>
    <?php endif; ?>

    <div class="progress-container <?php echo $animationType === 'circular' ? 'circle' : 'signal'; ?>">
        <?php if ($animationType === 'circular') : ?>
            <!-- Pie Chart Animation -->
            <svg viewBox="0 0 200 200">
                <circle class="bg" cx="100" cy="100" r="80"></circle>
                <circle class="progress" cx="100" cy="100" r="80" data-percent="<?php echo $targetNumber; ?>"></circle>
            </svg>
        <?php elseif ($animationType === 'signal') : ?>
            <!-- Signal Bars (20 Step) Animation -->
            <div class="signal-bars" id="signalBars">
                <?php for ($i = 1; $i <= 20; $i++) : ?>
                    <div class="bar" data-level="<?php echo $i; ?>"></div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

			<!-- Centered Counter Text -->
			<div class="counter" id="counter">
				<?php if ($prefixPosition === 'before' && !empty($prefix)) : ?>
					<span class="prefix-text"><?php echo htmlspecialchars($prefix); ?></span>
					<span class="number-text">0</span>
				<?php elseif ($prefixPosition === 'after' && !empty($prefix)) : ?>
					<span class="number-text">0</span>
					<span class="prefix-text"><?php echo htmlspecialchars($prefix); ?></span>
				<?php else : ?>
					<span class="number-text">0</span>
				<?php endif; ?>
			</div>
    </div>

    <?php if ($titlePosition === 'bottom' && !empty($title)) : ?>
        <div class="title"><?php echo htmlspecialchars($title); ?></div>
    <?php endif; ?>
</div>

<style>
.title {
    font-size: <?php echo $titleFontSize; ?>;
    color: <?php echo $titleColor; ?>;
}
.prefix-text {
    font-size: <?php echo $prefixFontSize; ?>;
    color: <?php echo $prefixColor; ?>;
    margin-right: <?php echo $prefixPosition === 'before' ? '4px' : '0'; ?>;
    margin-left: <?php echo $prefixPosition === 'after' ? '4px' : '0'; ?>;
}
.number-text {
    font-size: <?php echo $numberFontSize; ?>;
    color: <?php echo $numberColor; ?>;
}
.progress {
    stroke: <?php echo $animationColor; ?>;
}
</style>
