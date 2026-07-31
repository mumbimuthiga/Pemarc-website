<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

include_once JPATH_ADMINISTRATOR . '/components/com_carouselck/helpers/ckloader.php';
include_once JPATH_ADMINISTRATOR . '/components/com_carouselck/helpers/ckframework.php';
include_once JPATH_ADMINISTRATOR . '/components/com_carouselck/helpers/helper.php';

Carouselck\CKFramework::load();
CarouselckHelper::loadCkbox();

\Carouselck\CKText::script('CAROUSELCK_ADDSLIDE');
\Carouselck\CKText::script('CAROUSELCK_SELECTIMAGE');
\Carouselck\CKText::script('CAROUSELCK_SELECT_LINK');
\Carouselck\CKText::script('CAROUSELCK_REMOVE2');
\Carouselck\CKText::script('CAROUSELCK_SELECT');
\Carouselck\CKText::script('CAROUSELCK_CAPTION');
\Carouselck\CKText::script('CAROUSELCK_USETOSHOW');
\Carouselck\CKText::script('CAROUSELCK_IMAGE');
\Carouselck\CKText::script('CAROUSELCK_VIDEO');
\Carouselck\CKText::script('CAROUSELCK_TEXTOPTIONS');
\Carouselck\CKText::script('CAROUSELCK_IMAGEOPTIONS');
\Carouselck\CKText::script('CAROUSELCK_LINKOPTIONS');
\Carouselck\CKText::script('CAROUSELCK_VIDEOOPTIONS');
\Carouselck\CKText::script('CAROUSELCK_ALIGNEMENT_LABEL');
\Carouselck\CKText::script('CAROUSELCK_TOPLEFT');
\Carouselck\CKText::script('CAROUSELCK_TOPCENTER');
\Carouselck\CKText::script('CAROUSELCK_TOPRIGHT');
\Carouselck\CKText::script('CAROUSELCK_MIDDLELEFT');
\Carouselck\CKText::script('CAROUSELCK_CENTER');
\Carouselck\CKText::script('CAROUSELCK_MIDDLERIGHT');
\Carouselck\CKText::script('CAROUSELCK_BOTTOMLEFT');
\Carouselck\CKText::script('CAROUSELCK_BOTTOMCENTER');
\Carouselck\CKText::script('CAROUSELCK_BOTTOMRIGHT');
\Carouselck\CKText::script('CAROUSELCK_LINK');
\Carouselck\CKText::script('CAROUSELCK_TARGET');
\Carouselck\CKText::script('CAROUSELCK_SAMEWINDOW');
\Carouselck\CKText::script('CAROUSELCK_NEWWINDOW');
\Carouselck\CKText::script('CAROUSELCK_VIDEOURL');
\Carouselck\CKText::script('CAROUSELCK_REMOVE');
\Carouselck\CKText::script('CAROUSELCK_IMPORTFROMFOLDER');
\Carouselck\CKText::script('CAROUSELCK_ARTICLEOPTIONS');
\Carouselck\CKText::script('CAROUSELCK_SLIDETIME');
\Carouselck\CKText::script('CAROUSELCK_CLEAR');
\Carouselck\CKText::script('CAROUSELCK_SELECT');
\Carouselck\CKText::script('CAROUSELCK_TITLE');
\Carouselck\CKText::script('CAROUSELCK_STARTDATE');
\Carouselck\CKText::script('CAROUSELCK_ENDDATE');
\Carouselck\CKText::script('CAROUSELCK_SAVE');
\Carouselck\CKText::script('CAROUSELCK_TEXT_CUSTOM');
\Carouselck\CKText::script('CAROUSELCK_ARTICLE');
\Carouselck\CKText::script('CAROUSELCK_TEXT');




class JFormFieldCkslidesmanager extends \Joomla\CMS\Form\FormField {

	protected $type = 'ckslidesmanager';

	protected function getInput() {

		// loads the language files from the frontend
		$lang	= \Carouselck\CKFof::getLanguage();
		$lang->load('com_carouselck', JPATH_SITE . '/components/com_carouselck', $lang->getTag(), false);
		$lang->load('com_carouselck', JPATH_SITE, $lang->getTag(), false);

		include_once(JPATH_ROOT . '/administrator/components/com_carouselck/helpers/defines.js.php');
		$path = 'media/com_carouselck/assets/elements/ckslidesmanager/';
		\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
		// \Joomla\CMS\HTML\HTMLHelper::_('jquery.ui', array('core', 'sortable'));
		\Joomla\CMS\HTML\HTMLHelper::_('script', 'media/com_carouselck/assets/jquery-uick-custom.js');
		\Joomla\CMS\HTML\HTMLHelper::_('script', 'media/com_carouselck/assets/admin.js');
		\Joomla\CMS\HTML\HTMLHelper::_('script', $path . 'ckslidesmanager.js');
		if (\Carouselck\CKFof::isSite()) {
			\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'media/com_carouselck/assets/front-edition.css');
		}
		
		\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'media/com_carouselck/assets/jquery-ui.min.css');
		\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', $path . 'ckslidesmanager.css');

		$html = '<input name="' . $this->name . '" id="ckslides" type="hidden" value="' . $this->value . '" />'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide();"><i class="far fa-plus-square"></i> ' . \Carouselck\CKText::_('CAROUSELCK_ADDSLIDE') . '</div>'
				. '<ul id="ckslideslist" class="ckinterface" style="clear:both;"></ul>'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide();"><i class="far fa-plus-square"></i> ' . \Carouselck\CKText::_('CAROUSELCK_ADDSLIDE') . '</div>';

		return $html;
	}

	protected function getLabel() {

		return '';
	}

//	protected function getArticlesList() {
//		$db = & \Joomla\CMS\Factory::getDBO();
//
//		$query = "SELECT id, title FROM #__content WHERE state = 1 LIMIT 2;";
//		$db->setQuery($query);
//		$row = $db->loadObjectList('id');
//		var_dump($row);
//		return json_encode($row);
//	}

}

