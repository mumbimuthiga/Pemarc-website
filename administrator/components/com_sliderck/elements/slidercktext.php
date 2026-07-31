<?php

/**
 * @copyright	Copyright (C) 2011-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('_JEXEC') or die;

class JFormFieldSlidercktext extends \Joomla\CMS\Form\Field\TextField {

	/**
	 * The form field type.
	 *
	 * @var    string
	 */
	protected $type = 'slidercktext';

	protected function getInput() {
		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];

		$html = $icon ? '<div class="sliderck-field-icon" ' . ($suffix ? 'data-has-suffix="1"' : '') . '><img src="' . SLIDERCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div class="sliderck-field-icon"></div>';

		$html .= parent::getInput();
		if ($suffix)
			$html .= '<span class="sliderck-field-suffix">' . $suffix . '</span>';
		return $html;
	}

}
