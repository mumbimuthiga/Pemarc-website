<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Mobilemenuck\CKView;
use Mobilemenuck\CKFof;

class MobilemenuckViewItems extends CKView {

	public function display($tpl = null) {
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_mobilemenuck') || (count($user->getAuthorisedCategories('com_mobilemenuck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Mobilemenuck\CKText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$this->state = $this->model->getState();
		$this->items = $this->get('Items');

		// Load the left sidebar.
		MobilemenuckHelper::addSubmenu(\Mobilemenuck\CKFof::getApplication()->input->get('view', 'items'));
		// Load the title
		\Joomla\CMS\Toolbar\ToolbarHelper::title(\Mobilemenuck\CKText::_('COM_MOBILEMENUCK') . ' - ' . \Mobilemenuck\CKText::_('CK_MENUS_LIST'), 'logo_mobilemenuck_large.png');

		if (CKFof::userCan('core.admin')) {
			\Joomla\CMS\Toolbar\ToolbarHelper::preferences('com_mobilemenuck');
		}

		parent::display($tpl);
	}
}
