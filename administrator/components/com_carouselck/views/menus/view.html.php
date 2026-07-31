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

class CarouselckViewMenus extends CKView {

	protected $menus;

	/**
	 * Display the view
	 */
	public function display($tpl = 'default') {
		$user = \Joomla\CMS\Factory::getUser();
		$authorised = ($user->authorise('core.edit', 'com_carouselck') || (count($user->getAuthorisedCategories('com_carouselck', 'core.edit'))));

		if ($authorised !== true)
		{
			throw new Exception(\Carouselck\CKText::_('JERROR_ALERTNOAUTHOR'), 403);
			return false;
		}

		$this->menus = $this->get('Menus');

		parent::display($tpl);
	}
}
