<?php
/**
 * @name		Carousel CK
 * @package		com_carouselck
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
// No direct access
defined('_JEXEC') or die;

use \Carouselck\CKView;
use \Carouselck\CKFof;

class CarouselckViewStyles extends CKView {

	protected $items;

	protected $pagination;

	protected $state;

	protected $toolbar;

	/**
	 * Display the view
	 */
	public function display($tpl = null) {

		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_carouselck') || (count($user->getAuthorisedCategories('com_carouselck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Carouselck\CKText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$this->items = $this->get('Items');

		$this->toolbar = $this->getToolbar();

		$this->input->set('tmpl', 'component');
		$this->input->set('layout', 'modal');

		parent::display();
	}

	private function getToolbar() {
		// Get the toolbar object instance
		$bar = \Joomla\CMS\Toolbar\Toolbar::getInstance('toolbar');
		if (CKFof::userCan('create')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::addNew('style.add', 'CK_NEW');
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.copy', 'copy', 'copy', 'CK_COPY');
			// Render the popup button
//				$html = '<button class="btn btn-small btn-success" onclick="CKBox.open({handler:\'iframe\', fullscreen: true, url:\'' . \Carouselck\CKUri::root(true) . '/administrator/index.php?option=com_carouselck&view=style&layout=modal&tmpl=component&id=0\'})">
//						<span class="icon-new icon-white"></span>
//						' . \Carouselck\CKText::_('JTOOLBAR_NEW') . '
//						</button>';
//				$bar->appendButton('Custom', $html);
			
		}
		if (CKFof::userCan('edit')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.edit', 'edit', 'edit', 'CK_EDIT');
			\Joomla\CMS\Toolbar\ToolbarHelper::trash('style.delete', 'CK_TRASH');
		}

		return $bar;
	}
}
