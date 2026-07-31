<?php

/**
 * @copyright	Copyright (C) 2011-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('_JEXEC') or die;



class JFormFieldCktext extends \Joomla\CMS\Form\Field\TextField {

	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'cktext';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];

		$html = $icon ? '<div class="carouselck-field-icon" ' . ($suffix ? 'data-has-suffix="1"' : '') . '><img src="' . CAROUSELCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div class="carouselck-field-icon"></div>';

		$html .= parent::getInput();
		if ($suffix)
			$html .= '<span class="carouselck-field-suffix">' . $suffix . '</span>';
		return $html;
	}

}
