<?php
/**
 * @copyright	Copyright (C) 2016 Cedric KEIFLIN alias ced1870
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;
if (!defined('SLIDERCK_MEDIA_URI'))
{
	define('SLIDERCK_MEDIA_URI', \Sliderck\CKUri::root(true) . '/media/com_sliderck');
}

\Sliderck\CKText::script('MOD_SLIDERCK_SAVE_CLOSE');

class JFormFieldCkfolder extends \Joomla\CMS\Form\FormField
{

	protected $type = 'ckfolder';

	function __construct($form = null) {
		$doc = \Sliderck\CKFof::getDocument();
		/*$js = 'function ckGetFolder(id, path) {
			document.getElementById(id).value = path;
			CKBox.close();
		}';
		$conf = \Joomla\CMS\Factory::getConfig();
		$editor = $conf->get('editor');
		$editor = \Joomla\CMS\Editor\Editor::getInstance($editor);
		echo '<div id="ckeditor">' . $editor->display('ckeditor', $html = '', $width = '', $height = '200px', $col='', $row='', $buttons = true, $id = 'ckeditor') . '</div>';
		*/
		\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
		$input	= \Sliderck\CKFof::getApplication()->input;
		$moduleId = $input->get('id', '', 'int');
		$js = 'CKURIROOT = "' . \Sliderck\CKUri::root(true) . '";';
		$js .= 'CKURIBASE = "' . \Sliderck\CKUri::base(true) . '";';
		$js .= 'CKMODULEID = "' . $moduleId . '";';
		$doc->addScriptDeclaration($js);
		$doc->addStyleSheet(\Sliderck\CKUri::root(true) . '/modules/mod_sliderck/elements/ckfolder.css');
		$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/jquery-ui.min.js');
		$doc->addScript(\Sliderck\CKUri::root(true) . '/modules/mod_sliderck/elements/ckfolder.js');

//		\Joomla\CMS\HTML\HTMLHelper::_('jquery.ui', array('sortable'));
		parent::__construct($form);
	}

	protected function getInput() {
		$doc = \Sliderck\CKFof::getDocument();
		$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckbox.css');
		$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/ckbox.js');
		// Initialize some field attributes.
		$icon = $this->element['icon'];
		$suffix = $this->element['suffix'];
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$defautlwidth = $suffix ? '128px' : '150px';
		$styles = ' style="width:'.$defautlwidth.';'.$this->element['styles'].'"';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
		$html = $icon ? '<div style="display:inline-block;vertical-align:top;margin-top:4px;width:20px;"><img src="' . SLIDERCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';
		$html .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . $styles . '/>';
		if ($suffix)
			$html .= '<span style="display:inline-block;line-height:25px;">' . $suffix . '</span>';
		$html .= '<div class="btn" onclick="CKBox.open({url: \'index.php?option=com_sliderck&view=browse&type=folder&id=' . $this->id . '&tmpl=component\'})">' . \Sliderck\CKText::_('MOD_SLIDERCK_SELECT') . '</div>';
		$html .= '<p></p><p>' . \Sliderck\CKText::_('MOD_SLIDERCK_EDIT_LABELS_DESC') . '</p>';
		$html .= '<p><div class="btn" onclick="ckEditLabelsFile()">' . \Sliderck\CKText::_('MOD_SLIDERCK_EDIT_LABELS') . '</div></p>';
		$html .= '<h3>' . \Sliderck\CKText::_('MOD_SLIDERCK_SLIDES_PREVIEW') . '</h3>';
		$html .= '<div id="' . $this->id . 'imagespreview"></div>';
		$html .= '<div id="' . $this->id . 'labelseditor" ></div>';
		return $html;
	}
}
