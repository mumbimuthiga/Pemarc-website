<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once 'ckformfield.php';
require_once JPATH_ADMINISTRATOR . '/components/com_slideshowck/helpers/ckframework.php';
require_once JPATH_ADMINISTRATOR . '/components/com_slideshowck/helpers/helper.php';

Slideshowck\CKFramework::load();
SlideshowckHelper::loadCkbox();

Slideshowck\CKText::script('SLIDESHOWCK_ADDSLIDE');
Slideshowck\CKText::script('SLIDESHOWCK_SELECTIMAGE');
Slideshowck\CKText::script('SLIDESHOWCK_SELECT_LINK');
Slideshowck\CKText::script('SLIDESHOWCK_REMOVE2');
Slideshowck\CKText::script('SLIDESHOWCK_SELECT');
Slideshowck\CKText::script('SLIDESHOWCK_CAPTION');
Slideshowck\CKText::script('SLIDESHOWCK_USETOSHOW');
Slideshowck\CKText::script('SLIDESHOWCK_IMAGE');
Slideshowck\CKText::script('SLIDESHOWCK_VIDEO');
Slideshowck\CKText::script('SLIDESHOWCK_TEXTOPTIONS');
Slideshowck\CKText::script('SLIDESHOWCK_IMAGEOPTIONS');
Slideshowck\CKText::script('SLIDESHOWCK_LINKOPTIONS');
Slideshowck\CKText::script('SLIDESHOWCK_VIDEOOPTIONS');
Slideshowck\CKText::script('SLIDESHOWCK_ALIGNEMENT_LABEL');
Slideshowck\CKText::script('SLIDESHOWCK_TOPLEFT');
Slideshowck\CKText::script('SLIDESHOWCK_TOPCENTER');
Slideshowck\CKText::script('SLIDESHOWCK_TOPRIGHT');
Slideshowck\CKText::script('SLIDESHOWCK_MIDDLELEFT');
Slideshowck\CKText::script('SLIDESHOWCK_CENTER');
Slideshowck\CKText::script('SLIDESHOWCK_MIDDLERIGHT');
Slideshowck\CKText::script('SLIDESHOWCK_BOTTOMLEFT');
Slideshowck\CKText::script('SLIDESHOWCK_BOTTOMCENTER');
Slideshowck\CKText::script('SLIDESHOWCK_BOTTOMRIGHT');
Slideshowck\CKText::script('SLIDESHOWCK_LINK');
Slideshowck\CKText::script('SLIDESHOWCK_TARGET');
Slideshowck\CKText::script('SLIDESHOWCK_SAMEWINDOW');
Slideshowck\CKText::script('SLIDESHOWCK_NEWWINDOW');
Slideshowck\CKText::script('SLIDESHOWCK_VIDEOURL');
Slideshowck\CKText::script('SLIDESHOWCK_REMOVE');
Slideshowck\CKText::script('SLIDESHOWCK_IMPORTFROMFOLDER');
Slideshowck\CKText::script('SLIDESHOWCK_ARTICLEOPTIONS');
Slideshowck\CKText::script('SLIDESHOWCK_SLIDETIME');
Slideshowck\CKText::script('SLIDESHOWCK_CLEAR');
Slideshowck\CKText::script('SLIDESHOWCK_SELECT');
Slideshowck\CKText::script('SLIDESHOWCK_TITLE');
Slideshowck\CKText::script('SLIDESHOWCK_STARTDATE');
Slideshowck\CKText::script('SLIDESHOWCK_ENDDATE');
Slideshowck\CKText::script('SLIDESHOWCK_SAVE');
Slideshowck\CKText::script('SLIDESHOWCK_TEXT_CUSTOM');
Slideshowck\CKText::script('SLIDESHOWCK_ARTICLE');
Slideshowck\CKText::script('SLIDESHOWCK_TEXT');
Slideshowck\CKText::script('SLIDESHOWCK_VIDEO_AUTOPLAY');
Slideshowck\CKText::script('SLIDESHOWCK_VIDEO_LOOP');
Slideshowck\CKText::script('SLIDESHOWCK_VIDEO_CONTROLS');
Slideshowck\CKText::script('CK_SAVE_CLOSE');

class JFormFieldCkslidesmanager extends CKFormField {

	protected $type = 'ckslidesmanager';

	protected function getInput() {

		// loads the language files from the frontend
		$lang	= Slideshowck\CKFof::getLanguage();
		$lang->load('com_slideshowck', JPATH_SITE . '/components/com_slideshowck', $lang->getTag(), false);
		$lang->load('com_slideshowck', JPATH_SITE, $lang->getTag(), false);

		require_once(JPATH_ROOT . '/administrator/components/com_slideshowck/helpers/defines.js.php');
		$path = 'media/com_slideshowck/assets/elements/ckslidesmanager/';
		\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
		// \Joomla\CMS\HTML\HTMLHelper::_('jquery.ui', array('core', 'sortable'));
		\Joomla\CMS\HTML\HTMLHelper::_('script', 'media/com_slideshowck/assets/jquery-uick-custom.js');
		\Joomla\CMS\HTML\HTMLHelper::_('script', 'media/com_slideshowck/assets/admin.js');
		\Joomla\CMS\HTML\HTMLHelper::_('script', $path . 'ckslidesmanager.js');
		if (\Slideshowck\CKFof::isSite()) {
			\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'media/com_slideshowck/assets/front-edition.css');
		}
		
		\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'media/com_slideshowck/assets/jquery-ui.min.css');
		\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', $path . 'ckslidesmanager.css');

		$html = '<input name="' . $this->name . '" id="ckslides" type="hidden" value="' . $this->value . '" />'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide(false, \'top\');"><i class="far fa-plus-square"></i> ' . Slideshowck\CKText::_('SLIDESHOWCK_ADDSLIDE') . '</div>'
				. '<ul id="ckslideslist" class="ckinterface" style="clear:both;"></ul>'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide();"><i class="far fa-plus-square"></i> ' . Slideshowck\CKText::_('SLIDESHOWCK_ADDSLIDE') . '</div>';

		return $html;
	}

	protected function getLabel() {

		return '';
	}
}

