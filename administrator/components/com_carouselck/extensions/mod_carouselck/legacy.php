<?php

/**
 * @copyright	Copyright (C) 2012-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * Module Carousel CK
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die;

if ($params->get('carouselckhikashop_enable', '0') == '1') {
	if (is_file(JPATH_ROOT . '/plugins/system/carouselckhikashop/helper/helper_carouselckhikashop.php')) {
		include_once JPATH_ROOT . '/plugins/system/carouselckhikashop/helper/helper_carouselckhikashop.php';
		$items = modCarouselckhikashopHelper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/carouselckhikashop/helper/helper_carouselckhikashop.php not found ! Please download the patch for Carousel CK - Hikashop on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} else if ($params->get('carouselckjoomgallery_enable', '0') == '1') {
	if (is_file(JPATH_ROOT . '/plugins/system/carouselckjoomgallery/helper/helper_carouselckjoomgallery.php')) {
		include_once JPATH_ROOT . '/plugins/system/carouselckjoomgallery/helper/helper_carouselckjoomgallery.php';
		$items = modCarouselckjoomgalleryHelper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/carouselckjoomgallery/helper/helper_carouselckjoomgallery.php not found ! Please download the patch for Carousel CK - Joomgallery on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} else if ($params->get('carouselckvirtuemart_enable', '0') == '1') {
	if (is_file(JPATH_ROOT . '/plugins/system/carouselckvirtuemart/helper/helper_carouselckvirtuemart.php')) {
		include_once JPATH_ROOT . '/plugins/system/carouselckvirtuemart/helper/helper_carouselckvirtuemart.php';
		$items = modCarouselckvirtuemartHelper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/carouselckvirtuemart/helper/helper_carouselckvirtuemart.php not found ! Please download the patch for Carousel CK - Virtuemart on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} else if ($params->get('carouselckk2_enable', '0') == '1') {
	if (is_file(JPATH_ROOT . '/plugins/system/carouselckk2/helper/helper_carouselckk2.php')) {
		include_once JPATH_ROOT . '/plugins/system/carouselckk2/helper/helper_carouselckk2.php';
		$items = modCarouselckk2Helper::getItems($params);
	} else {
		echo '<p style="color:red;font-weight:bold;">File /plugins/system/carouselckk2/helper/helper_carouselckk2.php not found ! Please download the patch for Carousel CK - K2 on <a href="https://www.joomlack.fr">https://www.joomlack.fr</a></p>';
		return false;
	}
} 

else {
	switch ($params->get('slidesssource', 'slidesmanager')) {
		case 'folder':
			$items = modCarouselckHelper::getItemsFromfolder($params);

			break;
		case 'autoloadfolder':
			$items = modCarouselckHelper::getItemsAutoloadfolder($params);

			break;
		case 'autoloadarticlecategory':
			$items = modCarouselckHelper::getItemsAutoloadarticlecategory($params);
			break;
		case 'flickr':
			$items = modCarouselckHelper::getItemsAutoloadflickr($params);
			break;
		case 'googlephotos':
			include_once(JPATH_SITE. '/plugins/system/carouselckparams/helper/class-helpersource-google.php');
			$items = CarouselckHelpersourceGoogle::getItems($params);
			break;
		default:
//			$items = modCarouselckHelper::getItems($params);
			break;
	}

//	if ($params->get('displayorder', 'normal') == 'shuffle')
//		shuffle($items);
}
