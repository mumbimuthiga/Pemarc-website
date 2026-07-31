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

\Sliderck\CKText::script('MOD_SLIDERCK_SAVE_CLOSE');

class JFormFieldCklayout extends \Joomla\CMS\Form\FormField
{

	protected $type = 'cklayout';

	function __construct($form = null) {
		$doc = \Sliderck\CKFof::getDocument();

		$css = '.cklayout {
	display: inline-block;
	width: 150px;
	height: 95px;
	margin: 5px;
	box-shadow: #888 0 0 5px;
	background-position: center center;
	background-size: contain;
	background-repeat: no-repeat;
	background-color: #fff;
	border: 2px solid #fff;
	position: relative;
}
.cklayout:hover {
	box-shadow: #5f9ea0 0 0 10px;
}
:checked + .cklayout {
	border: 2px solid #5f9ea0;
}
';
		$doc->addStyleDeclaration($css);

		parent::__construct($form);
	}

	protected function getInput() {
		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="ckbutton-group ' . (string) $this->element['class'] . '"' : ' class="ckbutton-group"';
		$icon = $this->element['icon'];

		// Start the radio field output.
        $html[] = $icon ? '<div style="display:inline-block;vertical-align:top;margin-top:5px;width:20px;"><img src="' . SLIDERCK_MEDIA_URI . '/images/' . $icon . '" style="margin-right:5px;" /></div>' : '<div style="display:inline-block;width:20px;"></div>';
		$html[] = '<fieldset id="' . $this->id . '-fieldset"' . $class . ' style="">';

		// Get the field options.
		$options = $this->getOptions();

		// Build the radio field output.
		foreach ($options as $i => $option)
		{

			// if (stristr($option->text,"img:")) $option->text = '<img src="' . SLIDERCK_MEDIA_URI . '/images/' . str_replace("img:","",$option->text) . '" style="margin:0; float:none;" />';

			// Initialize some option attributes.
			$checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class = !empty($option->class) ? ' class="cklayout hasTooltip ' . $option->class . '"' : 'class="cklayout hasTooltip"';
			$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

			$html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . ' style="display:none;"/>';

			$html[] = '<label for="' . $this->id . $i . '"' . $class . ' style="background-image:url(' . SLIDERCK_MEDIA_URI . '/images/' . str_replace("img:","",$option->text) . ');" title="' . $option->value . '"></label>';
		}

		// End the radio field output.
		$html[] = '</fieldset>';

		return implode($html);
	}

	/**
	 * Method to get the field options for radio buttons.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();

		foreach ($this->element->children() as $option)
		{

			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = \Joomla\CMS\HTML\HTMLHelper::_(
				'select.option', (string) $option['value'], trim((string) $option), 'value', 'text',
				((string) $option['disabled'] == 'true')
			);

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
