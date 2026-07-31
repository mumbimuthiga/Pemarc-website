<?php
/**
 * @name		Carousel CK
 * @package		com_carouselck
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
// No direct access
defined('CK_LOADED') or die;

use \Carouselck\CKController;
use \Carouselck\CKFof;
use \Carouselck\CKText;

class CarouselckController extends CKController {

	static function getInstance($prefix = '') {
		return parent::getInstance('Carouselck');
	}

	public function display($cachable = false, $urlparams = false) {
		$view = $this->input->get('view', 'styles');
		$this->input->set('view', $view);

		parent::display();

		return $this;
	}

	public static function ajaxCreateFolder() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		if (CKFof::userCan('create', 'com_media')) {
			$input = CKFof::getInput();
			$path = $input->get('path', '', 'string');
			$name = $input->get('name', '', 'string');

			include_once CAROUSELCK_PATH . '/helpers/ckbrowse.php';
			if ($result = CKBrowse::createFolder($path, $name)) {
				$msg = CKText::_('CK_FOLDER_CREATED_SUCCESS');
			} else {
				$msg = CKText::_('CK_FOLDER_CREATED_ERROR');
			}

			echo '{"status" : "' . ($result == false ? '0' : '1') . '", "message" : "' . $msg . '"}';
		} else {
			echo '{"status" : "2", "message" : "' . CKText::_('CK_ERROR_USER_NO_AUTH') . '"}';
		}
		exit;
	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxAddPicture() {
		include_once CAROUSELCK_PATH . '/helpers/ckbrowse.php';
		CKBrowse::ajaxAddPicture();
	}
}
