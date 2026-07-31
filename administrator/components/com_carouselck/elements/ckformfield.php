<?php

/**
 * @copyright	Copyright (C) 2011 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');



class JFormField extends \Joomla\CMS\Form\FormField {

	public $mediaPath;

	public function __construct() {
		$this->mediaPath = \Carouselck\CKUri::root(true) . '/media/com_carouselck/images/';
		// loads the language files from the frontend
		$lang	= \Carouselck\CKFof::getLanguage();
		$lang->load('com_carouselck', JPATH_SITE . '/components/com_carouselck', $lang->getTag(), false);
		$lang->load('com_carouselck', JPATH_SITE, $lang->getTag(), false);
		parent::__construct();
	}
	protected function getInput() {
		return '';
	}

	protected function getLabel() {
		return parent::getLabel();
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions() {
		$options = array();

		foreach ($this->element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = \Joomla\CMS\HTML\HTMLHelper::_(
							'select.option', (string) $option['value'],
							\Carouselck\CKText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
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
