<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

class JFormFieldMobilemenuckonlypro extends \Joomla\CMS\Form\FormField {

	protected $type = 'mobilemenuckonlypro';

	protected function getLabel() {
		if ((string)$this->element['showlabel'] === 'true') {
			return parent::getLabel();
		}
		return '';
	}

	protected function getInput() {
		// TODO : check si composant est installé ou pas si oui pas de message
//		$isPro = file_exists(JPATH_ROOT . '/administrator/components/com_mobilemenuck/mobilemenuck.php');
//		if ($isPro) return;

		$icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 192 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M20 424.229h20V279.771H20c-11.046 0-20-8.954-20-20V212c0-11.046 8.954-20 20-20h112c11.046 0 20 8.954 20 20v212.229h20c11.046 0 20 8.954 20 20V492c0 11.046-8.954 20-20 20H20c-11.046 0-20-8.954-20-20v-47.771c0-11.046 8.954-20 20-20zM96 0C56.235 0 24 32.235 24 72s32.235 72 72 72 72-32.235 72-72S135.764 0 96 0z"/></svg>';
		$html = '<div class="mobilemenuck-info">' 
			. '<div class="mobilemenuck-info-icon">' . $icon . '</div>'
			. '<a href="https://www.joomlack.fr/en/joomla-extensions/mobile-menu-ck" target="_blank">' . \Mobilemenuck\CKText::_('PLG_MOBILEMENUCK_PRO_ONLY') . '</a></div>';

		return $html;
	}
}

