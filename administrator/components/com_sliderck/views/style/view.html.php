<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access
defined('_JEXEC') or die;

use Sliderck\CKView;
use Sliderck\CKFof;

class SliderckViewStyle extends CKView {

	protected $interface;

	function display($tpl = null) {
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_sliderck') || (count($user->getAuthorisedCategories('com_sliderck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Sliderck\CKText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// dislay the page title
		\Joomla\CMS\Toolbar\ToolbarHelper::title(\Sliderck\CKText::_('COM_SLIDERCK') . ' - ' . \Sliderck\CKText::_('CK_STYLES_EDITION'), 'logo_sliderck_large.png');
		// load the styles helper and the interface
		require_once JPATH_SITE . '/administrator/components/com_sliderck/helpers/ckstyles.php';
		require_once(JPATH_SITE . '/administrator/components/com_sliderck/helpers/ckinterface.php');

		$this->interface = new Sliderck\CKInterface();
		$this->item = $this->get('Item');

		$this->input->set('tmpl', 'component');
		$this->input->set('layout', 'modal');

		// if (\Sliderck\CKFof::getApplication()->isAdmin()) $this->addToolbar();
		parent::display();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		// dislay the page title
		\Joomla\CMS\Toolbar\ToolbarHelper::title(\Sliderck\CKText::_('COM_SLIDERCK') . ' - ' . \Sliderck\CKText::_('CK_STYLES_EDITION'), 'logo_sliderck_large.png');

		$this->input->set('hidemainmenu', true);
		$user		= \Joomla\CMS\Factory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo = SliderckHelper::getActions();

		// For new records, check the create permission.
		if ($isNew && $user->authorise('core.create', 'com_sliderck'))
		{
			\Joomla\CMS\Toolbar\ToolbarHelper::apply('style.apply');
			\Joomla\CMS\Toolbar\ToolbarHelper::save('style.save');
			// \Joomla\CMS\Toolbar\ToolbarHelper::save2new('page.save2new');
			\Joomla\CMS\Toolbar\ToolbarHelper::cancel('style.cancel');
		} else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					\Joomla\CMS\Toolbar\ToolbarHelper::apply('style.apply');
					\Joomla\CMS\Toolbar\ToolbarHelper::save('style.save');
//					\Joomla\CMS\Toolbar\ToolbarHelper::custom('style.restore', 'archive', 'archive', 'CK_RESTORE', false);
					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						// \Joomla\CMS\Toolbar\ToolbarHelper::save2new('page.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				// \Joomla\CMS\Toolbar\ToolbarHelper::save2copy('page.save2copy');
			}

			\Joomla\CMS\Toolbar\ToolbarHelper::cancel('style.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
