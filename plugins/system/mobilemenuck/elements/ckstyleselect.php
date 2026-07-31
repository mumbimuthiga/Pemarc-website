<?php
/**
 * @copyright	Copyright (C) 2017 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;
if (!defined('MOBILEMENUCK_MEDIA_URI'))
{
	define('MOBILEMENUCK_MEDIA_URI', \Mobilemenuck\CKUri::root(true) . '/media/com_mobilemenuck');
}

\Mobilemenuck\CKText::script('MOD_MOBILEMENUCK_SAVE_CLOSE');

class JFormFieldCkstyleselect extends \Joomla\CMS\Form\FormField
{

	protected $type = 'ckstyleselect';

	private $activate = true;

	function __construct($form = null) {
		require_once(MOBILEMENUCK_PATH . '/helpers/helper.php');
		if (! \Mobilemenuck\Helper::checkIsProVersion()) $this->activate = false;
		parent::__construct($form);
	}

	protected function getInput() {
		if (! $this->activate) {
			$html = \Mobilemenuck\Helper::renderProMessage();
			return $html;
		}
		$doc = \Mobilemenuck\CKFof::getDocument();
		$doc->addStylesheet(MOBILEMENUCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(MOBILEMENUCK_MEDIA_URI . '/assets/ckbox.js');
		// Initialize some field attributes.
		$js = 'function ckMobilemenuSelectStyle(id, name, close) {
			if (!close && close != false) close = true;
			jQuery("#' . $this->id . '").val(id);
			jQuery("#' . $this->id . 'name").val(name);
			if (close) CKBox.close();
}

function ckMobilemenuUpdateStyle(nothing, id, name) {
			ckMobilemenuSelectStyle(id, name, false);
}';
		$doc->addScriptDeclaration($js);
	
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : 'class="form-control"';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$defautlwidth = $suffix ? '128px' : '150px';
		$styles = ' style="width:'.$defautlwidth.';'.$this->element['styles'].'"';
		$styleName = \Mobilemenuck\Helper::getStyleNameById($this->value);

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		$html = $icon ? '<div style="display:inline-block;vertical-align:top;margin-top:4px;width:20px;"><img src="' . MOBILEMENUCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '';

		$html .= '<div class="btn-group">';
		$html .= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		$html .= '<input type="text" disabled name="' . $this->name . 'name" id="' . $this->id . 'name"' . ' value="'
			. htmlspecialchars($styleName) . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		$html .= '<div class="btn btn-primary" onclick="CKBox.open({url: \'index.php?option=com_mobilemenuck&view=styles&tmpl=component&modal=1&returnFunc=ckMobilemenuSelectStyle\'})">' . \Mobilemenuck\CKText::_('PLG_MOBILEMENUCK_SELECT') . '</div>';
		$html .= '<div class="btn btn-secondary" onclick="if (jQuery(\'#' . $this->id . '\').val()) {CKBox.open({url: \'index.php?option=com_mobilemenuck&view=style&tmpl=component&modal=1&id=\'+jQuery(\'#' . $this->id . '\').val()+\'\'}) } else { alert(\'' . \Mobilemenuck\CKText::_('PLG_MOBILEMENUCK_SELECT_FIRST', true) . '\');}">' . \Mobilemenuck\CKText::_('PLG_MOBILEMENUCK_EDIT', true) . '</div>';
		$html .= '<div class="btn btn-light" onclick="jQuery(\'#' . $this->id . '\').val(\'\');jQuery(\'#' . $this->id . 'name\').val(\'\');">' . \Mobilemenuck\CKText::_('PLG_MOBILEMENUCK_CLEAR', true) . '</div>';
		$html .= '</div>';

		return $html;
	}
}
