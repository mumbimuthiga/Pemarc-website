<?php
/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

class JFormFieldMobilemenuckinfo extends \Joomla\CMS\Form\FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 */
	protected $type = 'mobilemenuckinfo';

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
		$doc = \Mobilemenuck\CKFof::getDocument();
		$styles = '.mobilemenuck-info {position:relative;background:#efefef;border: none;border-radius: px;color: #333;font-weight: normal;line-height: 24px;padding: 5px 5px 5px 35px;margin: 3px 0;text-align: left;text-decoration: none;height:100%;}
.mobilemenuck-info .mobilemenuck-info-icon {
	margin: 0 10px 0 0;
	padding: 3px 5px;
	background: rgba(0, 0, 0, 0.1);
	position: absolute;
	top: 0;
	bottom: 0;
	left: 0;
	line-height: 25px;
	width: 30px;
	height: 100%;
	text-align: center;}
.mobilemenuck-info-icon svg {
	max-width: 25px;
	max-height: 20px;
	vertical-align: bottom;
}
.control-label:empty, .controls:empty {display: none;}
.control-label:empty + .controls {margin: 0;}
';
		$doc->addStyleDeclaration($styles);
	}
}
