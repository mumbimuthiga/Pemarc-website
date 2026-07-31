<?php
/**
 * @copyright	Copyright (C) 2016 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;



\Carouselck\CKText::script('CAROUSELCK_SAVE_CLOSE');
include_once JPATH_ROOT . '/administrator/components/com_carouselck/helpers/helper.php';

class JFormFieldCkstyle extends \Joomla\CMS\Form\FormField {

	protected $type = 'ckstyle';

	protected function getInput() {
		$doc = \Carouselck\CKFof::getDocument();
		// Initialize some field attributes.
		$js = 'function ckSelectStyle(id, name, close) {
			if (!close && close != false) close = true;
			jQuery("#' . $this->id . '").val(id);
			jQuery("#' . $this->id . 'name").val(name);
			if (close) CKBox.close();
		}';
		$doc->addScriptDeclaration($js);
		
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$defautlwidth = $suffix ? '128px' : '150px';
		$styles = ' style="width:'.$defautlwidth.';'.$this->element['styles'].'"';
		$styleName = CarouselckHelper::getStyleNameById($this->value);

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		$html = $icon ? '<div style="display:inline-block;vertical-align:top;margin-top:4px;width:20px;"><img src="' . CAROUSELCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';		

		$html .= '<div class="ckbutton-group">';
		$html .= '<input type="hidden" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		$html .= '<input type="text" disabled name="' . $this->name . 'name" id="' . $this->id . 'name"' . ' value="'
			. htmlspecialchars($styleName) . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		$footerHtml = '<a class="ckboxmodal-button" href="javascript:void(0)" onclick="ckSaveIframe(\'test\')">' . \Carouselck\CKText::_('CK_CREATE_NEW') . '</a>';
		$html .= '<div class="ckbutton" onclick="CKBox.open({url: \'index.php?option=com_carouselck&view=styles&tmpl=component&layout=modal\', style: {padding: \'0px\'}})"><i class="fas fa-mouse-pointer "></i> ' . \Carouselck\CKText::_('CAROUSELCK_SELECT') . '</div>';
		$html .= '<div class="ckbutton" onclick="CKBox.open({url: \'index.php?option=com_carouselck&view=style&tmpl=component&layout=modal&id=\'+jQuery(\'#' . $this->id . '\').val()+\'\'})"><i class="fas fa-edit"></i> ' . \Carouselck\CKText::_('CAROUSELCK_EDIT') . '</div>';
		$html .= '<div class="ckbutton cktip" onclick="jQuery(\'#' . $this->id . '\').val(\'\');jQuery(\'#' . $this->id . 'name\').val(\'\');" title="' . \Carouselck\CKText::_('CAROUSELCK_REMOVE') . '"><i class="fas fa-times"></i></div>';
		$html .= '</div>';

		return $html;
	}
}
