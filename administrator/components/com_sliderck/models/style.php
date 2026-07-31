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

use Sliderck\CKModel;
use Sliderck\CKFof;

class SliderckModelStyle extends CKModel {

	protected $table = '#__sliderck_styles';

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
		if (! isset($this->item->customcss)) $this->item->customcss = '';

		return $this->item;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function _save($data) {
		$input = \Sliderck\CKFof::getApplication()->input;
		$id = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('style.id');
		$user = \Joomla\CMS\Factory::getUser();
		// $data['htmlcode'] = JRequest::getVar('htmlcode', '', 'post', 'string', JREQUEST_ALLOWRAW);
		$data['htmlcode'] = $data['htmlcode'] ? $data['htmlcode'] : $input->get('htmlcode', '', 'raw');
		$data['htmlcode'] = str_replace(\Sliderck\CKUri::root(true), "|URIROOT|", $data['htmlcode']);
		
		if (isset($data['options']) && is_array($data['options']))
		{
			$registry = new Registry;
			$registry->loadArray($data['options']);
			$data['params'] = (string) $registry;
		}

		if ($id) {
			//Check the user can edit this item
			$authorised = $user->authorise('core.edit', 'style.' . $id);
		} else {
			//Check the user can create new items in this section
			$authorised = $user->authorise('core.create', 'com_sliderck');
		}

		if ($authorised !== true) {
			JError::raiseError(403, \Sliderck\CKText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		$table = $this->getTable();
		$table->load($data['id']);
		// if ($table->name !== $data['name']) {
			// $this->changeTemplateName($table->name, $data['name']);
		// }

		// make a backup before save
		PagebuilderckHelper::makeBackup($this->getData());

		if ($table->save($data) === true) {
			return $table->id;
		} else {
			return false;
		}
	}
}