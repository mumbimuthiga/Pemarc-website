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

class SliderckTableStyles extends \Joomla\CMS\Table\Table {

	/**
	 * Constructor
	 *
	 * @param \Joomla\Data\DataObjectbase A database connector object
	 */
	public function __construct(&$db) {
		$this->setColumnAlias('published', 'state');
		parent::__construct('#__sliderck_styles', 'id', $db);
	}
}
