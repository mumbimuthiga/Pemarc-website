<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/ckframework.php';
require_once JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/helper.php';

Sliderck\CKFramework::load();
SliderckHelper::loadCkbox();

\Sliderck\CKText::script('SLIDERCK_ADDSLIDE');
\Sliderck\CKText::script('SLIDERCK_SELECTIMAGE');
\Sliderck\CKText::script('SLIDERCK_SELECT_LINK');
\Sliderck\CKText::script('SLIDERCK_REMOVE2');
\Sliderck\CKText::script('SLIDERCK_SELECT');
\Sliderck\CKText::script('SLIDERCK_CAPTION');
\Sliderck\CKText::script('SLIDERCK_USETOSHOW');
\Sliderck\CKText::script('SLIDERCK_IMAGE');
\Sliderck\CKText::script('SLIDERCK_VIDEO');
\Sliderck\CKText::script('SLIDERCK_TEXTOPTIONS');
\Sliderck\CKText::script('SLIDERCK_IMAGEOPTIONS');
\Sliderck\CKText::script('SLIDERCK_LINKOPTIONS');
\Sliderck\CKText::script('SLIDERCK_VIDEOOPTIONS');
\Sliderck\CKText::script('SLIDERCK_ALIGNEMENT_LABEL');
\Sliderck\CKText::script('SLIDERCK_TOPLEFT');
\Sliderck\CKText::script('SLIDERCK_TOPCENTER');
\Sliderck\CKText::script('SLIDERCK_TOPRIGHT');
\Sliderck\CKText::script('SLIDERCK_MIDDLELEFT');
\Sliderck\CKText::script('SLIDERCK_CENTER');
\Sliderck\CKText::script('SLIDERCK_MIDDLERIGHT');
\Sliderck\CKText::script('SLIDERCK_BOTTOMLEFT');
\Sliderck\CKText::script('SLIDERCK_BOTTOMCENTER');
\Sliderck\CKText::script('SLIDERCK_BOTTOMRIGHT');
\Sliderck\CKText::script('SLIDERCK_LINK');
\Sliderck\CKText::script('SLIDERCK_TARGET');
\Sliderck\CKText::script('SLIDERCK_SAMEWINDOW');
\Sliderck\CKText::script('SLIDERCK_NEWWINDOW');
\Sliderck\CKText::script('SLIDERCK_VIDEOURL');
\Sliderck\CKText::script('SLIDERCK_REMOVE');
\Sliderck\CKText::script('SLIDERCK_IMPORTFROMFOLDER');
\Sliderck\CKText::script('SLIDERCK_ARTICLEOPTIONS');
\Sliderck\CKText::script('SLIDERCK_SLIDETIME');
\Sliderck\CKText::script('SLIDERCK_CLEAR');
\Sliderck\CKText::script('SLIDERCK_SELECT');
\Sliderck\CKText::script('SLIDERCK_TITLE');
\Sliderck\CKText::script('SLIDERCK_STARTDATE');
\Sliderck\CKText::script('SLIDERCK_ENDDATE');
\Sliderck\CKText::script('SLIDERCK_SAVE');
\Sliderck\CKText::script('SLIDERCK_TEXT_CUSTOM');
\Sliderck\CKText::script('SLIDERCK_ARTICLE');
\Sliderck\CKText::script('SLIDERCK_TEXT');
\Sliderck\CKText::script('SLIDERCK_ONLY_PRO');


class JFormFieldCkslidesmanager extends \Joomla\CMS\Form\FormField {

	protected $type = 'ckslidesmanager';

	protected function getInput() {
		// loads the language files from the frontend
		$lang	= \Sliderck\CKFof::getLanguage();
		$lang->load('com_sliderck', JPATH_SITE . '/components/com_sliderck', $lang->getTag(), false);
		$lang->load('com_sliderck', JPATH_SITE, $lang->getTag(), false);

		require_once(JPATH_ROOT . '/administrator/components/com_sliderck/helpers/defines.js.php');
		$path = 'media/com_sliderck/assets/elements/ckslidesmanager/';
		\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
		// \Joomla\CMS\HTML\HTMLHelper::_('jquery.ui', array('core', 'sortable'));
		\Joomla\CMS\HTML\HTMLHelper::_('script', 'media/com_sliderck/assets/jquery-uick-custom.js');
		\Joomla\CMS\HTML\HTMLHelper::_('script', 'media/com_sliderck/assets/admin.js');
		\Joomla\CMS\HTML\HTMLHelper::_('script', $path . 'ckslidesmanager.js');
		// if (\Sliderck\CKFof::isSite()) {
			// \Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'media/com_sliderck/assets/front-edition.css');
		// }
		
		\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', 'media/com_sliderck/assets/jquery-ui.min.css');
		\Joomla\CMS\HTML\HTMLHelper::_('stylesheet', $path . 'ckslidesmanager.css');

		$html = '<input name="' . $this->name . '" id="ckslides" type="hidden" value="' . $this->value . '" />'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide(false, \'top\');"><i class="far fa-plus-square"></i> ' . \Sliderck\CKText::_('SLIDERCK_ADDSLIDE') . '</div>'
				. '<ul id="ckslideslist" class="ckinterface" style="clear:both;"></ul>'
				. '<div class="ckaddslide ckbutton ckbutton-success" onclick="javascript:ckAddSlide();"><i class="far fa-plus-square"></i> ' . \Sliderck\CKText::_('SLIDERCK_ADDSLIDE') . '</div>';

		return $html;
	}

	protected function getLabel() {

		return '';
	}
}

