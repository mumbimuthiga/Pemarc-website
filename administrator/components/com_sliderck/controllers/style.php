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

use Sliderck\CKController;
use Sliderck\CKFof;

/**
 * Style controller class.
 */
class SliderckControllerStyle extends CKController {

	public function save() {
		// security check
		if (! CKFof::checkAjaxToken()) {
			exit();
		}

		$id = $this->input->get('id', 0, 'int');

		$model = $this->getModel();
		$row = $model->getItem($id);
		// get data
		$fields = $this->input->get('fields', '', 'raw');
		$name = $this->input->get('name', '', 'string');
		if (! $name) $name = 'style' . $id;
		$layoutcss = trim($this->input->get('layoutcss', '', 'html'));
		// set data
		$row->params = $fields;
		$row->name = $name;
		$row->layoutcss = $layoutcss;

		if (! $id = $model->save($row)) {
			echo "{'result': '0', 'id': '" . $row->id . "', 'message': 'Error : Can not save the Styles !'}";
			echo($this->_db->getErrorMsg());
			exit;
		}
		echo '{"result": "1", "id": "' . $id . '", "message": "Styles saved successfully"}';
		exit;
	}

	/*
	 * Generate the CSS styles from the settings
	 */
	public function ajaxRenderCss() {
		$this->input	= \Sliderck\CKFof::getApplication()->input;
		$fields = $this->input->get('fields', '', 'raw');
		$fields = json_decode($fields);
		$customstyles = stripslashes( $this->input->get('customstyles', '', 'string'));
		$customstyles = json_decode($customstyles);
		$customcss = $this->input->get('customcss', '', 'html');

		$css = $this->renderCss($fields, $customstyles);
		echo $css . $customcss;
		exit();
	}

	/*
	 * Render the CSS from the settings
	 */
	public function renderCss($fields, $customstyles) {
		include_once SLIDERCK_PATH . '/helpers/ckstyles.php';
		$ckstyles = new Sliderck\CKStyles();
		$css = $ckstyles->create($fields, $customstyles);

		return $css;
	}

	/*
	 * Generate the CSS styles from the settings
	 */
	public function ajaxSaveStyles() {
		// security check
		CKFof::checkAjaxToken();

		// Import Table
		\Joomla\CMS\Table\Table::addIncludePath(SLIDERCK_ADMIN_PATH . '/tables');
		$row = \Joomla\CMS\Table\Table::getInstance('Styles', 'SliderckTable');

		$this->input	= \Sliderck\CKFof::getApplication()->input;

		$fields = $this->input->get('fields', '', 'raw');
		$id = $this->input->get('id', 0, 'int');
		$name = $this->input->get('name', '', 'string');
		if (! $name) $name = 'style' . $id;
		$layoutcss = trim($this->input->get('layoutcss', '', 'html'));

		// load the module
		$row->load( (int) $id ); 
		$row->params = $fields;
		$row->name = $name;
		$row->layoutcss = $layoutcss;
		if (!$row->store()) {
			echo "{'result': '0', 'id': '" . $row->id . "', 'message': 'Error : Can not save the Styles !'}";
			// echo($this->_db->getErrorMsg());
			die;
		}
		echo '{"result": "1", "id": "' . $row->id . '", "message": "Styles saved successfully"}';
		exit();
	}

	/**
	 * Ajax method to save the json data into the .mmck file
	 *
	 * @return  boolean - true on success for the file creation
	 *
	 */
	function exportParams() {
		// security check
		CKFof::checkAjaxToken();

		// create a backup file with all fields stored in it
		$fields = $this->input->get('jsonfields', '', 'string');
		$backupfile_path = SLIDERCK_ADMIN_PATH . '/export/exportParamsSliderckStyle'. $this->input->get('styleid',0,'int') .'.mmck';
		if (\Sliderck\CKFile::write($backupfile_path, $fields)) {
			echo '1';
		} else {
			echo '0';
		}

		exit();
	}
	
	/**
	 * Ajax method to import the .mmck file into the interface
	 *
	 * @return  boolean - true on success for the file creation
	 *
	 */
	function importParams() {
		// security check
		CKFof::checkAjaxToken();

		$file = $this->input->files->get('file', '', 'array');
		if (!is_array($file))
			exit();

		$filename = \Sliderck\CKFile::makeSafe($file['name']);

		// check if the file exists
		if (\Sliderck\CKFile::getExt($filename) != 'mmck') {
			$msg = \Sliderck\CKText::_('CK_NOT_MMCK_FILE', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		//Set up the source and destination of the file
		$src = $file['tmp_name'];

		// check if the file exists
		if (!$src || !is_file($src)) {
			$msg = \Sliderck\CKText::_('CK_FILE_NOT_EXISTS', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		// read the file
		if (!$filecontent = file_get_contents($src)) {
			$msg = \Sliderck\CKText::_('CK_UNABLE_READ_FILE', true);
			echo json_encode(array('error'=> $msg));
			exit();
		}

		// replace vars to allow data to be moved from another server
		$filecontent = str_replace("|URIROOT|", \Sliderck\CKUri::root(true), $filecontent);
//		$filecontent = str_replace("|qq|", '"', $filecontent);

		echo json_encode(array('data'=> $filecontent));
		exit();
	}
}