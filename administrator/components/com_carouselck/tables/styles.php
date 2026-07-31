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

class CarouselckTableStyles extends \Joomla\CMS\Table\Table {

	/**
	 * Constructor
	 *
	 * @param \Joomla\Data\DataObjectbase A database connector object
	 */
	public function __construct(&$db) {
		$this->setColumnAlias('published', 'state');
		parent::__construct('#__carouselck_styles', 'id', $db);
	}
}
