<?php
/**
 * @copyright	Copyright (C) 2012-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Carousel CK
 * @license		GNU/GPL
 * */

// no direct access
defined('_JEXEC') or die;

include_once JPATH_ROOT . '/administrator/components/com_carouselck/helpers/defines.php';
include_once JPATH_ROOT . '/administrator/components/com_carouselck/helpers/helper.php';
include_once dirname(__FILE__) . '/helper.php';
if (! defined('CAROUSELCK_PATH')) define('CAROUSELCK_PATH', JPATH_ROOT . '/administrator/components/com_carouselck');

// load the items
$source = $params->get('source', 'slidesmanager');
if ($source != 'slidesmanager') {
	$sourceFile = JPATH_ROOT . '/plugins/carouselck/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php';
	if (! file_exists($sourceFile)) {
		echo '<p syle="color:red;">Error : File plugins/carouselck/' . strtolower($source) . '/helper/helper_' . strtolower($source) . '.php not found !</p>';
		return;
	}
	include_once $sourceFile;
} else {
	include_once CAROUSELCK_PATH . '/helpers/source/' . $source . '.php';
}
$loaderClass = 'CarouselckHelpersource' . ucfirst($source);
$items = $loaderClass::getItems($params);

// load items for B/C if the save action has not yet been triggered
require dirname(__FILE__) . '/legacy.php';

if (empty($items) || $items === false) {
	echo '<p>CAROUSEL CK : No items found.</p>';
	return;
}

if ($params->get('displayorder', 'normal') == 'shuffle')
	shuffle($items);

$doc = \Carouselck\CKFof::getDocument();
\Joomla\CMS\HTML\HTMLHelper::_("jquery.framework", true);
if ($params->get('loadjqueryeasing', '1')) {
	$doc->addScript(CAROUSELCK_MEDIA_URI . '/assets/jquery.easing.1.3.js');
}

$debug = false;
if ($debug) {
	$doc->addScript(CAROUSELCK_MEDIA_URI . '/assets/carouselck.js?ver=' . CAROUSELCK_VERSION);
} else {
	$doc->addScript(CAROUSELCK_MEDIA_URI . '/assets/carouselck.min.js?ver=' . CAROUSELCK_VERSION);
}

$theme = $params->get('theme', 'default');
$langdirection = $doc->getDirection();

if ($theme == 'default' && file_exists(JPATH_ROOT . '/templates/' . $doc->template . '/css/carouselck.css')) {
	if ($langdirection == 'rtl' && file_exists(JPATH_ROOT . '/templates/' . $doc->template . '/css/carouselck_rtl.css')) {
		$cssfilesrc = 'templates/' . $doc->template . '/css/carouselck_rtl.css';
	} else {
		$cssfilesrc = 'templates/' . $doc->template . '/css/carouselck.css';
	}
} else {
	if ($langdirection == 'rtl' && file_exists(JPATH_ROOT . '/modules/mod_carouselck/themes/' . $theme . '/css/carouselck_rtl.css')) {
		$cssfilesrc = 'modules/mod_carouselck/themes/' . $theme . '/css/carouselck_rtl.css';
	} else {
		$cssfilesrc = 'modules/mod_carouselck/themes/' . $theme . '/css/carouselck.css';
	}
}
$doc->addStylesheet(\Carouselck\CKUri::root(true) . '/' . $cssfilesrc);

// set the navigation variables
if (count($items) == 1) { // for only one slide, no navigation, no button
	$navigation = "navigationHover: false,
			mobileNavHover: false,
			navigation: false,
			playPause: " . $params->get('playPause', 'true') . ",";
} else {
	switch ($params->get('navigation', '2')) {
		case 0:
			// aucune
			$navigation = "navigationHover: false,
				mobileNavHover: false,
				navigation: false,
				playPause: " . $params->get('playPause', 'true') . ",";
			break;
		case 1:
			// toujours
			$navigation = "navigationHover: false,
				mobileNavHover: false,
				navigation: true,
				playPause: " . $params->get('playPause', 'true') . ",";
			break;
		case 2:
		default:
			// on mouseover
			$navigation = "navigationHover: true,
				mobileNavHover: true,
				navigation: true,
				playPause: " . $params->get('playPause', 'true') . ",";
			break;
	}
}

// if no pagination, disable the thumbs by default to avoid errors
if ($params->get('pagination', '1') === '0') {
	$thumbs = '0';
} else {
	$thumbs = $params->get('thumbnails', '1');
}
// load the script
$js = "jQuery(document).ready(function(){
		new Carouselck('#carouselck_wrap_" . $module->id . "', {
			wrapheight: '" . $params->get('wrapheight', '40') . "',
			imageheight: '" . $params->get('imageheight', '40') . "',
			imagesratio: '" . $params->get('imagesratio', '0.72') . "',
			pagination: " . $params->get('pagination', '1') . ",
			thumbnails: " . $thumbs . ",
			duration: " . $params->get('duration', '600') . ",
			time: " . $params->get('time', '7000') . ",
			captionduration: " . $params->get('captionduration', '600') . ",
			autoAdvance: " . $params->get('autoAdvance', 'true') . ",
			lightbox: '" . $params->get('lightboxtype', 'mediaboxck') . "',
			captionresponsive: " . (int)($params->get('usecaptionresponsive', '2') == '2') . ",
			imagePath: '" . \Carouselck\CKUri::base(true) . "/media/com_carouselck/images/',
			" . $navigation . "
			hover: " . $params->get('hover', 'true') . "
		});
});";

if ($params->get('loadinline', '0') == '1') {
	echo '<script>' . $js . '</script>';
} else {
	$doc->addScriptDeclaration($js);
}

$css = '';
// load some css
$css = "#carouselck_wrap_" . $module->id . " .carouselck_pag_ul .carouselck_thumb {width:" . $params->get('thumbnailwidth', '100') . "px;height:" . CarouselckHelper::testUnit($params->get('thumbnailheight', '75')) . "px;}";

// load the caption styles
$captioncss = modCarouselckHelper::createCss($params, 'captionstyles');
$imagecss = modCarouselckHelper::createCss($params, 'imagestyles');
$fontfamily = ($params->get('captionstylesusefont', '0') && $params->get('captionstylestextgfont', '0')) ? "font-family:'" . $params->get('captionstylestextgfont', 'Droid Sans') . "';" : '';
if ($fontfamily) {
	$gfonturl = str_replace(" ", "+", $params->get('captionstylestextgfont', 'Droid Sans'));
	$doc->addStylesheet('https://fonts.googleapis.com/css?family=' . $gfonturl);
}

$css .= "
#carouselck_wrap_" . $module->id . " .carouselck_caption > div {
	" . $captioncss['padding'] . $captioncss['margin'] . $captioncss['background'] . $captioncss['gradient'] . $captioncss['borderradius'] . $captioncss['shadow'] . $captioncss['border'] . $fontfamily . "
}
#carouselck_wrap_" . $module->id . " .carouselck_caption > div > div {
	" . $captioncss['fontcolor'] . $captioncss['fontsize'] . "
}
#carouselck_wrap_" . $module->id . " .carouselck_caption > div div.carouselck_caption_desc {
	" . $captioncss['descfontcolor'] . $captioncss['descfontsize'] . "
}
#carouselck_wrap_" . $module->id . " .carouselck_images > img {
	" . $imagecss['padding'] . $imagecss['margin'] . $imagecss['background'] . $imagecss['gradient'] . $imagecss['borderradius'] . $imagecss['shadow'] . $imagecss['border'] . "
}
";

if ($params->get('usecaptionresponsive') == '1' || $params->get('usecaptionresponsive') == '2') {
	$css .= "
@media screen and (max-width: " . str_replace("px", "", $params->get('captionresponsiveresolution', '480')) . "px) {
		#carouselck_wrap_" . $module->id . " .carouselck_caption {
			" . ( $params->get('captionresponsivehidecaption', '0') == '1' ? "display: none !important;" : ($params->get('usecaptionresponsive') == '1' ? "font-size: " . $params->get('captionresponsivefontsize', '0.6em') ." !important;" : "") ) . "
		}
}";
}

// load the style 
if ($styleId = $params->get('styles', '')) {
	$layoutcss = str_replace('|ID|', '#carouselck_wrap_' . $module->id, CarouselckHelper::getStyleLayoutcss($styleId) );
	$css .= $layoutcss;
}

$doc->addStyleDeclaration($css);

// load the php Class for the html fixer
if ($params->get('fixhtml', '0') == '1') include_once CAROUSELCK_PATH . '/helpers/htmlfixer.php';

// display the module
require \Joomla\CMS\Helper\ModuleHelper::getLayoutPath('mod_carouselck', $params->get('layout', 'default'));
