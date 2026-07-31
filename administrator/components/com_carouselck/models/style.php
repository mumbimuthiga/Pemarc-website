<?php
/**
 * @name		Slider CK
 * @package		com_carouselck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use \Carouselck\CKModel;
use \Carouselck\CKFof;


class CarouselckModelStyle extends CKModel {

	protected $table = '#__carouselck_styles';

	var $item = null;

	function __construct() {
		parent::__construct();
	}

	public function save($row) {
		$id = CKFof::dbStore($this->table, $row);

		return $id;
	}

	public function getItem() {
		if (empty($this->item)) {
			$id = $this->input->get('id', 0, 'int');
			$this->item = CKFof::dbLoad($this->table, $id);
		}

		return $this->item;
	}

}