<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

class MobilemenuckTableMenus extends \Joomla\CMS\Table\Table {

	/**
	 * Constructor
	 *
	 * @param \Joomla\Data\DataObjectbase A database connector object
	 */
	public function __construct(&$db) {
		$this->setColumnAlias('published', 'state'); // needed to trash the item with the default joomla API model
		parent::__construct('#__mobilemenuck_menus', 'id', $db);
	}
}
