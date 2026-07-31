<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Sliderck\CKView;
use Sliderck\CKFof;

/**
 * View class for a list of Maximenuck.
 */
class SliderckViewStyles extends CKView {

	protected $items;

	protected $pagination;

	protected $state;

	protected $toolbar;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_sliderck') || (count($user->getAuthorisedCategories('com_sliderck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Sliderck\CKText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}
		
		$this->items = $this->get('Items');

		$this->toolbar = $this->getToolbar();
		// Load the left sidebar.
		// SliderckHelper::addSubmenu();
		// Load the left sidebar.
		if ($this->input->get('layout') !== 'modal') {
			// SliderckHelper::addSubmenu($this->input->get('view', 'styles'));
			// Load the title
			\Joomla\CMS\Toolbar\ToolbarHelper::title(\Sliderck\CKText::_('COM_SLIDERCK') . ' - ' . \Sliderck\CKText::_('CK_MODULES_LIST'), 'logo_sliderck_large.png');
		}
		// if (\Sliderck\CKFof::getApplication()->isAdmin()) $this->addToolbar();
		parent::display();
	}

	private function getToolbar() {
		// Get the toolbar object instance
		$bar = \Joomla\CMS\Toolbar\Toolbar::getInstance('toolbar');
		if (CKFof::userCan('create')) {

			if ($this->input->get('layout') == 'modal') {
				\Joomla\CMS\Toolbar\ToolbarHelper::addNew('style.add', 'CK_NEW');
			} else {
				// Render the popup button
				$html = '<button class="btn btn-small btn-success" onclick="CKBox.open({handler:\'iframe\', fullscreen: true, url:\'' . \Sliderck\CKUri::root(true) . '/administrator/index.php?option=com_sliderck&view=style&layout=edit&tmpl=component&id=0\'})">
						<span class="icon-new icon-white"></span>
						' . \Sliderck\CKText::_('CK_NEW') . '
						</button>';
				$bar->appendButton('Custom', $html);
			}

			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.copy', 'copy', 'copy', 'CK_COPY');
		}
		if (CKFof::userCan('edit')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.edit', 'edit', 'edit', 'CK_EDIT');
			\Joomla\CMS\Toolbar\ToolbarHelper::trash('style.delete', 'CK_TRASH');
		}

		return $bar;
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	/*protected function addToolbar() {
		// Load the title
		\Joomla\CMS\Toolbar\ToolbarHelper::title(\Sliderck\CKText::_('COM_SLIDERCK') . ' - ' . \Sliderck\CKText::_('CK_STYLES_LIST'), 'logo_sliderck_large.png');

		// Get the toolbar object instance
		$bar = \Joomla\CMS\Toolbar\Toolbar::getInstance('toolbar');
		if (CKFof::userCan('create')) {
			// \Joomla\CMS\Toolbar\ToolbarHelper::addNew('style.add', 'CK_NEW');
			if ($this->input->get('layout') == 'modal') {
				\Joomla\CMS\Toolbar\ToolbarHelper::addNew('style.add', 'CK_NEW');
			} else {
				// Render the popup button
				$html = '<button class="btn btn-small btn-success" onclick="CKBox.open({handler:\'iframe\', fullscreen: true, url:\'' . \Sliderck\CKUri::root(true) . '/administrator/index.php?option=com_sliderck&view=style&layout=modal&tmpl=component&id=0\'})">
						<span class="icon-new icon-white"></span>
						' . \Sliderck\CKText::_('JTOOLBAR_NEW') . '
						</button>';
				$bar->appendButton('Custom', $html);
			}
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.copy', 'copy', 'copy', 'CK_COPY');
		}
		if (CKFof::userCan('edit')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.edit', 'edit', 'edit', 'CK_EDIT');
		}
		if (CKFof::userCan('delete')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::trash('style.delete', 'CK_REMOVE');
		}

		if (CKFof::userCan('core.admin')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::preferences('com_sliderck');
		}

		return $bar;
	}*/
}
