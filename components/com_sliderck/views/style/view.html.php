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

jimport('joomla.application.component.view');

class SliderckViewStyle extends \Joomla\CMS\MVC\View\HtmlView {

	function display($tpl = null) {
		$input = \Sliderck\CKFof::getApplication()->input;

		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.create', 'com_sliderck') || $user->authorise('core.edit', 'com_sliderck') || (count($user->getAuthorisedCategories('com_sliderck', 'core.create'))));

		if ($authorised !== true)
		{
			JError::raiseError(403, \Sliderck\CKText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}

		// dislay the page title
		\Joomla\CMS\Toolbar\ToolbarHelper::title(\Sliderck\CKText::_('COM_SLIDERCK') . ' - ' . \Sliderck\CKText::_('CK_STYLES_EDITION'), 'logo_sliderck_large.png');

		// load the styles helper and the interface
		require_once JPATH_SITE . '/administrator/components/com_sliderck/helpers/ckstyles.php';
		require_once(JPATH_SITE . '/administrator/components/com_sliderck/helpers/ckinterface.php');

		$this->interface = new CKInterface();
		$this->item = $this->get('Item');

		if (\Sliderck\CKFof::getApplication()->isAdmin()) $this->addToolbar();
		parent::display($tpl);
	}

	/*private function getItem() {
		// load the sliderck_styles table
		\Joomla\CMS\Table\Table::addIncludePath(SLIDERCK_ADMIN_PATH . '/tables');
		$row = \Joomla\CMS\Table\Table::getInstance('Styles', 'SliderckTable');

		// search for existing item
		$input	= \Sliderck\CKFof::getApplication()->input;
		$id = $input->get('id', 0, 'int');

		// load the item from the table
		$row->load( (int) $id ); 

		return $row;
	}*/
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		SliderckHelper::loadCkbox();

		\Sliderck\CKFof::getApplication()->input->set('hidemainmenu', true);
		$user		= \Joomla\CMS\Factory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$state = $this->get('State');
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
