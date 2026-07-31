<?php
/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;


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
		$styles = '.ckinfo {position:relative;background:#efefef;border: none;border-radius: px;color: #333;font-weight: normal;line-height: 24px;padding: 5px 5px 5px 35px;margin: 3px 0;text-align: left;text-decoration: none;}
.ckinfo > .fas {
	font-size: 15px;
	padding: 3px 5px;
	background: rgba(0, 0, 0, 0.1);
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	line-height: 25px;
	width: 30px;
	text-align: center;
}
.ckinfo img {margin: 0 10px 0 0;}
.control-label:empty {display: none;}
.control-label:empty + .controls {margin: 0;}
';
		$doc->addStyleDeclaration($styles);

		// get the extension version
		$current_version = $this->getCurrentVersion(JPATH_SITE .'/administrator/components/com_sliderck/sliderck.xml');
		$html = '';
		$html .= '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">';
		$html .= '<div class="ckinfo"><i class="fas fa-thumbs-up"></i><a href="https://extensions.joomla.org/extension/slider-ck/" target="_blank">' . \Sliderck\CKText::_('SLIDERCK_VOTE_JED') . '</a></div>';
		$html .= '<div class="ckinfo"><i class="fas fa-info"></i><b>SLIDER CK</b> - ' . \Sliderck\CKText::_('SLIDERCK_CURRENT_VERSION') . ' : <span class="label">' . $current_version . '</span></div>';
		$html .= '<div class="ckinfo"><i class="fas fa-file-alt"></i><a href="https://www.joomlack.fr/documentation/slider-ck" target="_blank">' . \Sliderck\CKText::_('SLIDERCK_DOCUMENTATION') . '</a></div>';

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
		if ($xml_installed = simplexml_load_file($file_url)) {
			$installed_version = (string)$xml_installed->version;
		}

		return $installed_version;
	}
}
