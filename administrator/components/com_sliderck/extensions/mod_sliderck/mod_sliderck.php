<?php
/**
 * @copyright	Copyright (C) 2016 Cédric KEIFLIN alias ced1870
 * http://www.template-creator.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;
include_once JPATH_ROOT . '/administrator/components/com_sliderck/helpers/defines.php';
include_once JPATH_ROOT . '/administrator/components/com_sliderck/helpers/helper.php';

$doc = \Sliderck\CKFof::getDocument();

if (! defined('SLIDERCK_PATH')) define('SLIDERCK_PATH', JPATH_ROOT . '/administrator/components/com_sliderck');

// global params
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx', ''));
$numberslides = $params->get('numberslides', '');
$linkposition = $params->get('linkposition', 'fullslide');
$effect = $params->get('effect', 'slide'); // 'slide' or 'fade' or 'cube' or 'coverflow' or 'flip'
// $captionheight = SliderckHelper::testUnit($params->get('captionheight', '100'));
// get the language direction
$langdirection = $doc->getDirection();

// load the items
$source = $params->get('source', 'slidesmanager');
if ($source != 'slidesmanager') {
	$sourceFile = JPATH_ROOT . '/plugins/sliderck/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php';
	if (! file_exists($sourceFile)) {
		echo '<p syle="color:red;">Error : File plugins/sliderck/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php not found !</p>';
		return;
	}
	require_once $sourceFile;
} else {
	require_once SLIDERCK_PATH . '/helpers/source/' . $source . '.php';
}
$loaderClass = 'SliderckHelpersource' . ucfirst($source);
$items = $loaderClass::getItems($params);

// lightbox management
$lightboxAttribs = '';
$linkClass = '';
if (file_exists(JPATH_SITE . '/plugins/system/mediabox_ck/mediabox_ck.php') && $params->get('lightbox', 'mediabox') == 'mediabox') {
	// get attribute from system plugin Mediabox
	$plugin = \Sliderck\CKFof::getPlugin('system', 'mediabox_ck');
	if (! empty($plugin)) {
		$lightboxParams = new \Sliderck\CKRegistry($plugin->params);
		if ($lightboxParams->get('attribtype') == 'className') {
			$linkClass .= $lightboxParams->get('attribname');
		} else {
			$lightboxAttribs = $lightboxParams->get('attribname')  .'[sliderck' . $module->id . ']';
		}
	}
}

// if no item to load then exit
if ((empty($items) OR !$items)) {
	if ($params->get('debugging', '1') == '1') echo '<p style="color:red;font-weight:bold;">No items found for Slider CK ! Please check your source - '.ucfirst($source).' - in the module options with ID : ' . $module->id . '</p>';
	return false;
}

// random order
if ($params->get('shuffle', '0') == '1') {
	shuffle($items);
}

// frontend medias
\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
// $doc->addScript(\Sliderck\CKUri::root(true) . '/media/jui/js/jquery.min.js');
$doc->addStyleSheet(SLIDERCK_MEDIA_URI . '/assets/swiper.css?ver=2.4.4');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/swiper.min.js?ver=2.4.4');

// load custom styles
$css = '';
$css .= $params->get('height', 'fixed') == 'fixed' ? '#sliderck' . $module->id . ' { height: ' . SliderckHelper::testUnit($params->get('heightvalue', '250')) . '; }' : '';
if ( $params->get('forceimageheight', '1') == '1') {
	$css .= $params->get('imageheight', '') ? '#sliderck' . $module->id . ' .swiper-slide .swiper-img { height: ' . SliderckHelper::testUnit($params->get('imageheight', '')) . ' }' : '';
	$css .= $params->get('imageheight', '') ? '#sliderck' . $module->id . ' .swiper-img-cont { background-size: auto ' . SliderckHelper::testUnit($params->get('imageheight', '')) . ' }' : '';
} else if ($params->get('forceimageheight', '1') == '2') {
	$css .= $params->get('imageheight', '') ? '#sliderck' . $module->id . ' .swiper-img-cont { background-size: cover; }' : '';
}

// $css .= $params->get('captionposition', '1') == '1' ? '#sliderck' . $module->id . ' .swiper-caption { height: ' . $captionheight . '; }' : '';
if ($params->get('pagination', 'bullets') == 'thumbs') {
	$css .= '#sliderck' . $module->id . 'thumbs { height: ' . SliderckHelper::testUnit($params->get('thumbsheight', '100')) . '; }
#sliderck' . $module->id . 'thumbs .swiper-slide { width: ' . (100 / (int) $params->get('thumbsnumber', '5')) . '%; }
';
}

$layoutcss = str_replace('|ID|', '#sliderck' . $module->id, SliderckHelper::getStyleLayoutcss($params->get('styles', '')) );
$css .= $layoutcss;

if ($params->get('usecaptionresponsive', '0') == '1') {
	$css .= "
@media screen and (max-width: " . str_replace("px", "", $params->get('captionresponsiveresolution', '480')) . "px) {
		#sliderck" . $module->id . " .swiper-caption {
			" . ( $params->get('captionresponsivehidecaption', '0') == '1' ? "display: none !important;" : ($params->get('usecaptionresponsive') == '1' ? "font-size: " . $params->get('captionresponsivefontsize', '0.6em') ." !important;" : "") ) . "
		}
}";
}

$doc->addStyleDeclaration($css);

// load the javascript
$js = "";
if ($params->get('pagination', 'bullets') == 'thumbs') {
	$jsthumbs = "var sliderck" . $module->id . "thumbs = new Swiper('#sliderck" . $module->id . "thumbs', {
		spaceBetween: 10,
		centeredSlides: true,
		slidesPerView: 'auto',
		slidesPerGroup: " . (int)$params->get('slidespergroup', '1') . ",
		touchRatio: 0.2,
		loop: " . $params->get('loop', 'false') . ",
		" . ($params->get('loop', 'false') ? "loopedSlides: " . count($items) . "," : "" ) . "
		slideToClickedSlide: true
	});
	sliderck" . $module->id . ".params.control = sliderck" . $module->id . "thumbs;
	sliderck" . $module->id . "thumbs.params.control = sliderck" . $module->id . ";
";
}

// set up responsive settings
$breakpoints = array();
for ($i=0;$i<=3;$i++) {
	if ($params->get('responsive' . $i . 'activate', '0') == '1') {
		$breakpoints[] = $params->get('responsive' . $i . 'resolution') . ": {
			slidesPerView: " . $params->get('responsive' . $i . 'slidesperview', $params->get('slidesperview', 'auto')) . ",
			slidesPerGroup: " . $params->get('responsive' . $i . 'slidespergroup', (int)$params->get('slidespergroup', '1')) . ",
			slidesPerColumn: " . $params->get('responsive' . $i . 'slidespercolumn', (int)$params->get('slidespercolumn', '1')) . ",
			spaceBetween: " . $params->get('responsive' . $i . 'spacebetween', (int) $params->get('spacebetween', '30')) . "
		}";
	}
}
if (count($breakpoints)) {
	$breakpoints = "breakpoints: {\n" . implode(",\n", $breakpoints) . "},\n";
} else {
	$breakpoints = '';
}

$js .= "jQuery(document).ready(function(){
	var sliderck" . $module->id . " = new Swiper('#sliderck" . $module->id . "', {
		" . ($params->get('pagination', 'bullets') != 'none' ? "pagination: '.swiper-pagination',
		paginationClickable: true," : "") . "
		setWrapperSize: true, // nécessaire pour garder les images centrées lors de la réduction de la fenêtre
		slidesPerView: '" . $params->get('slidesperview', 'auto') . "',
		slidesPerGroup: " . (int)$params->get('slidespergroup', '1') . ",
		slidesPerColumn: " . (int)$params->get('slidespercolumn', '1') . ",
		spaceBetween: " . (int) $params->get('spacebetween', '30') . ",
		centeredSlides: " . $params->get('centeredslides', 'true') . ",
		initialSlide: " . ($params->get('initialslide', '0') - 1) . ",
		freeMode: " . $params->get('freemode', 'false') . ",
		speed: " . $params->get('speed', '300') . ",
		" . ($params->get('autoplay', 0) ? "autoplay: " . $params->get('autoplaytime', '300') . "," : "") . "
		autoplayDisableOnInteraction: true,
		autoplayStopOnLast: " . (bool)(1-(int)$params->get('loop', 'false')) . ",
		autoplayPauseOnOver: " . $params->get('autoplaypauseonover', 'false') . ",
		effect: '" . $effect . "',
		fade: {crossFade: true},
		loop: " . $params->get('loop', 'false') . ",
		" . ($params->get('loop', 'false') ? "loopedSlides: " . count($items) . "," : "" ) . "
		keyboardControl: " . $params->get('keybordcontrol', 'false') . ",
		mousewheelControl: " . $params->get('mousecontrol', 'false') . ",
		a11y: true,
		paginationType: '" . $params->get('pagination', 'bullets') . "', // 'bullets' or 'progress' or 'fraction' or 'custom'
		" . $breakpoints . "
		// direction: 'horizontal',
		// autoHeight: true, //enable auto height to use more with content in slides. Not for images
		" . ($params->get('navigationbuttons', '1') == '1' ? "nextButton: '.swiper-button-next',
		prevButton: '.swiper-button-prev'," : "") . "
	});
	" . ($params->get('pagination', 'bullets') == 'thumbs' ? $jsthumbs : "") . "
});
";
$doc->addScriptDeclaration($js);

// load the php Class for the html fixer
if ($params->get('fixhtml', '0') == '1') require_once SLIDERCK_PATH . '/helpers/htmlfixer.php';

require \Joomla\CMS\Helper\ModuleHelper::getLayoutPath('mod_sliderck', $params->get('layout', 'default'));

