<?php
/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

if (!defined('SLIDERCK_MEDIA_URI'))
{
	define('SLIDERCK_MEDIA_URI', \Sliderck\CKUri::root(true) . '/media/com_sliderck');
}

class JFormFieldCkinfo extends \Joomla\CMS\Form\FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 */
	protected $type = 'ckinfo';

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
		$doc = \Sliderck\CKFof::getDocument();
		$styles = '.ckinfo {background:#efefef;border: none;border-radius: 3px;color: #333;font-weight: normal;line-height: 24px;padding: 5px;margin: 3px 0;text-align: left;text-decoration: none;}';
		$styles .= '.ckinfo img {margin: 0 10px 0 0;}';
		$styles .= '.control-label:empty {display: none;}';
		$styles .= '.control-label:empty + .controls {margin: 0;}';
		$doc->addStyleDeclaration($styles);

		// get the extension version
		$current_module_version = $this->getCurrentVersion(JPATH_SITE .'/modules/mod_sliderck/mod_sliderck.xml');
		$current_component_version = $this->getCurrentVersion(JPATH_SITE .'/administrator/components/com_sliderck/sliderck.xml');

		$html = '';
		$html .= '<div class="ckinfo"><img src="' . SLIDERCK_MEDIA_URI . '/images/thumb-up.png" /><a href="https://extensions.joomla.org/extensions/extension/photos-a-images/slideshow/slider-ck" target="_blank">' . \Sliderck\CKText::_('MOD_SLIDERCK_PLEASE_VOTE_JED') . '</a></div>';
		$html .= '<div class="ckinfo"><img src="' . SLIDERCK_MEDIA_URI . '/images/information.png" /><b>MODULE SLIDER CK</b> - ' . \Sliderck\CKText::_('MOD_SLIDERCK_CURRENT_VERSION') . ' : <span class="label">' . $current_module_version . '</span></div>';
		$html .= '<div class="ckinfo"><img src="' . SLIDERCK_MEDIA_URI . '/images/information.png" /><b>COMPONENT SLIDER CK</b> - ' . \Sliderck\CKText::_('MOD_SLIDERCK_CURRENT_VERSION') . ' : <span class="label">' . $current_component_version . '</span></div>';
		$html .= '<div class="ckinfo"><img src="' . SLIDERCK_MEDIA_URI . '/images/link.png" /><a href="http://docs.joomlack.fr/slider-ck" target="_blank">' . \Sliderck\CKText::_('MOD_SLIDERCK_DOCS_URL') . '</a></div>';

		return $html;
	}

	/*
	 * Get a variable from the manifest file
	 * 
	 * @return the current version
	 */
	public static function getCurrentVersion($file_url) {
		// get the version installed
		$installed_version = 'UNKOWN';
		if ($xml_installed = \Joomla\CMS\Factory::getXML($file_url)) {
			$installed_version = (string)$xml_installed->version;
		}

		return $installed_version;
	}
}
