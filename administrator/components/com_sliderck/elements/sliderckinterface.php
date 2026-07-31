<?php
/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

use Sliderck\CKFramework;

include_once JPATH_ROOT . '/administrator/components/com_sliderck/helpers/ckframework.php';
include_once JPATH_ROOT . '/administrator/components/com_sliderck/helpers/defines.php';

\Joomla\CMS\Form\FormHelper::loadFieldClass('hidden');
CKFramework::load();

class JFormFieldSliderckinterface extends \Joomla\CMS\Form\Field\HiddenField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 */
	protected $type = 'sliderckinterface';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 */
	protected function getLabel()
	{
		return '';
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 */
	protected function getInput()
	{
		// loads the language files from the frontend
		$lang	= \Sliderck\CKFof::getLanguage();
		$lang->load('com_sliderck', JPATH_SITE . '/components/com_sliderck', $lang->getTag(), false);
		$lang->load('com_sliderck', JPATH_SITE, $lang->getTag(), false);

		if (version_compare(JVERSION, '4') >= 0) {
		$css = '.sliderck-field-suffix {
	display: inline-block;
	line-height: 25px;
	transform: translate(0, -50%);
	position: absolute;
	top: 20px;
	height: 25px;
	right: 20px;
}

.sliderck-field-icon {
	display: inline-block;
	vertical-align: top;
	margin-top: 10px;
	width: 20px;
}

.sliderck-field-icon + input,
.sliderck-field-icon + fieldset,
.sliderck-field-icon + select {
	display: inline-block;
	width: calc(100% - 30px);
}

.ckbutton-group input[type="text"] {
	min-height: 28px;
	box-sizing: border-box;
	font-size: 13px;
}';
		} else {
			$css = '.sliderck-field-icon {
	display: inline-block;
	vertical-align: top;
	margin-top: 4px;
	width: 20px;
}';
		}

		$doc = \Sliderck\CKFof::getDocument();
		$doc->addStyleDeclaration($css);

		return '';
	}
}
