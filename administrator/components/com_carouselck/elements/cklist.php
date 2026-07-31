<?php

/**
 * @copyright	Copyright (C) 2011-2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
defined('_JEXEC') or die;

jimport('joomla.html.html');
jimport('joomla.form.formfield');

class JFormFieldCklist extends \Joomla\CMS\Form\Field\ListField {

	protected $type = 'cklist';

	protected function getInput() {
		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];

		$html = $icon ? '<div class="carouselck-field-icon" ' . ($suffix ? 'data-has-suffix="1"' : '') . '><img src="' . CAROUSELCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';

		$html .= parent::getInput();
		if ($suffix)
			$html .= '<span class="carouselck-field-suffix">' . $suffix . '</span>';
		return $html;
	}

}
