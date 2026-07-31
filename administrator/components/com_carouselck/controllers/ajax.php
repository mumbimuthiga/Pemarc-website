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

class CarouselckControllerAjax extends CKController {

	function __construct() {
		// security check
		if (! CKFof::checkAjaxToken()) exit;
		
		parent::__construct();
		
		$plugin = $this->input->get('plugin', '', 'cmd');
		$task = $this->input->get('task', '', 'cmd');

		if ($plugin) {
			if (file_exists(CAROUSELCK_PLUGINS_PATH . '/' . $plugin . '/helper/helper_' . $plugin . '.php')) {
				include_once(CAROUSELCK_PLUGINS_PATH . '/' . $plugin . '/helper/helper_' . $plugin . '.php');
				$className = 'CarouselckHelpersource' . ucfirst($plugin);
				//CarouselckHelpersourceArticles
				$class = new $className();
				if (method_exists($class, $task)) {
					$class::$task();
					exit;
				}
			}
		}
		die;
	}
}
