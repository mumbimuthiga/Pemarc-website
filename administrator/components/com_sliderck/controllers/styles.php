<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die;

use Sliderck\CKController;

/**
 * Pages list controller class.
 */
class SliderckControllerStyles extends CKController {

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'style', $prefix = 'SliderckModel', $config = array()) {
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}

	public function import() {
		$app = \Sliderck\CKFof::getApplication();
		if ($importClass = SliderckHelper::getParams('import')) {
			$importClass->importFile();
		} else {
			$msg = \Sliderck\CKText::_('CK_SLIDERCK_PARAMS_NOT_FOUND');
			$app->redirect("index.php?option=com_sliderck&view=pages", $msg, 'error');
			return false;
		}
	}

	public function export() {
		$app = \Sliderck\CKFof::getApplication();
		if ($exportClass = SliderckHelper::getParams('export')) {
			$exportClass->exportFile();
		} else {
			$msg = \Sliderck\CKText::_('CK_SLIDERCK_PARAMS_NOT_FOUND');
			$app->redirect("index.php?option=com_sliderck&view=pages", $msg, 'error');
			return false;
		}
	}

//	public function publish() {
//		die('test');
//	}
	
}