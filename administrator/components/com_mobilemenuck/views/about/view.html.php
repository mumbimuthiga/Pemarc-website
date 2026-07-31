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

class MobilemenuckViewAbout extends CKView {

	protected $ckversion;

			function display($tpl = null) {
		$this->ckversion = MobilemenuckController::getCurrentVersion();

		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar() {
		// Load the left sidebar.
		MobilemenuckHelper::addSubmenu('about');

		\Joomla\CMS\Toolbar\ToolbarHelper::title(\Mobilemenuck\CKText::_('COM_MOBILEMENUCK') . ' - ' . \Mobilemenuck\CKText::_('CK_ABOUT') , 'mobilemenuck');
	}

}
