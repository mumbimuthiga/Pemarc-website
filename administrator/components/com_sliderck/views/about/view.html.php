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

class SliderckViewAbout extends CKView {

	function display($tpl = 'default') {

		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_sliderck') || (count($user->getAuthorisedCategories('com_sliderck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Sliderck\CKText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		if ($this->input->get('layout') !== 'modal') {
			// SliderckHelper::addSubmenu($this->input->get('view', 'about'));
			// Load the title
			\Joomla\CMS\Toolbar\ToolbarHelper::title('Slider CK - ' . \Sliderck\CKText::_('CK_ABOUT'), 'logo_sliderck_large.png');
		}

		parent::display($tpl);
	}
}
