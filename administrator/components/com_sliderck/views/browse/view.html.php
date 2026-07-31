<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
 
// No direct access
defined('_JEXEC') or die;

use Sliderck\CKView;
use Sliderck\CKFof;

class SliderckViewBrowse extends CKView {

	function display($tpl = 'default') {
		$input = \Sliderck\CKFof::getApplication()->input;

		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_sliderck') || (count($user->getAuthorisedCategories('com_sliderck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Sliderck\CKText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		// load the items
		require_once JPATH_ADMINISTRATOR . '/components/com_sliderck/helpers/ckbrowse.php';
		$this->items = CKBrowse::getItemsList();

		parent::display($tpl);
	}
}
