<?php
/**
 * @copyright	Copyright (C) 2019 Cedric KEIFLIN alias ced1870
 * https://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;

use \Carouselck\CKFof;
use \Carouselck\CKFolder;
use \Carouselck\CKFile;
use \Carouselck\CKText;


include_once JPATH_ROOT . '/administrator/components/com_carouselck/helpers/ckloader.php';

class JFormFieldCkmigrate extends \Joomla\CMS\Form\FormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 */
	protected $type = 'ckmigrate';

	private $options;

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 */
	protected function getLabel()
	{
		return '';
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 *
	 */
	protected function getInput()
	{
		$input = \Carouselck\CKFof::getApplication()->input;
		$id = $input->get('id', 0, 'int');
		$doMigration = $input->get('domigration', 0, 'int');
		if (! $id) return '';

		$options = $this->getModuleOptions($id);
//var_dump($options);die;
		$params = json_decode($options->params);
		if (isset($params->slidesssource)) {
			$this->makeBackup($id, $options->params);
			if ($doMigration) {
				$this->doMigration($id, $options->params);
			} else {
				CKfof::enqueueMessage(CKText::_('CAROUSELCK_MIGRATION_NEEDED'), 'warning');
				CKfof::enqueueMessage('<a href="' . CKFof::getCurrentUri() . '&domigration=1">' . CKText::_('CAROUSELCK_MIGRATION_ACTION') . '</a>', 'warning');
			}
		}

		if ($this->isPluginEnabled()) {
			CKFof::dbExecute("UPDATE #__extensions SET enabled = 0 WHERE element = 'carouselckparams'");
			CKfof::enqueueMessage(CKText::_('CAROUSELCK_PARAMS_UNPUBLISHED_INFO'), 'warning');
			CKfof::enqueueMessage('<a href="https://www.joomlack.fr/en/documentation/45-carousel-ck/246-migration-from-slideshow-ck-version-1-to-version-2" target="_blank">' . CKText::_('CAROUSELCK_PARAMS_MIGRATION_LINK') . '</a>', 'warning');
			CKfof::redirect();
		}

		$this->alertObsoletePlugin($params, 'hikashop');
		$this->alertObsoletePlugin($params, 'k2');
		$this->alertObsoletePlugin($params, 'joomgallery');
	}

	protected function alertObsoletePlugin($params, $plugin) {
		if (isset($params->source) && $params->source == $plugin && $this->isPluginEnabled('carouselck' . $plugin)) {
			CKfof::enqueueMessage(CKText::_('CAROUSELCK_WARNING_PLUGIN_OBSOLETE') . ' : ' . '<b>carouselck' . $plugin . '</b>', 'warning');
			CKfof::enqueueMessage('<a href="index.php?option=com_plugins&view=plugins&filter_element=carouselck' . $plugin . '" target="_blank">' . CKText::_('CAROUSELCK_DISABLE_PLUGIN') . '</a>', 'warning');
		}
	}

	protected function isPluginEnabled($plugin = 'carouselckparams') {
		if (file_exists(JPATH_ROOT . '/plugins/system/' . $plugin)) {
			$isEnabled = CKFof::dbLoadResult("SELECT enabled FROM #__extensions WHERE element = '" . $plugin . "'");
			return (bool)$isEnabled;
		}
		return false;
	}

	protected function getModuleOptions($id) {
		if (empty($this->options)) {
			$this->options = CKFof::dbLoadObject("SELECT * FROM #__modules WHERE id = " . (int)$id);
		}
		return $this->options;
	}

	protected function doMigration($id, $params) {
		$find = array('slidesssource', '"autoloadfolder"', 'autoloadarticlecategory', 'imagetarget', 'articlelength', 'showarticletitle', 'lightboxtype', 'lightboxautolinkimages');
		$replace = array('source', '"folder"', 'linktarget', 'articles', 'textlength', 'usetitle', 'lightbox', 'linkautoimage');
		$newparams = str_replace($find, $replace, $params);

		$paramsObj = new \Carouselck\CKRegistry($newparams);
		if ($paramsObj->get('carouselckhikashop_enable', '0') == '1') $paramsObj->set('source', 'hikashop');
		if ($paramsObj->get('carouselckjoomgallery_enable', '0') == '1') $paramsObj->set('source', 'joomgallery');
		if ($paramsObj->get('carouselckk2_enable', '0') == '1') $paramsObj->set('source', 'k2');
		$newparams = json_encode($paramsObj);

		$data = CKFof::dbLoad('#__modules', $id);
		$data->id = $id;
		$data->params = $newparams;

		$return = CKFof::dbStore('#__modules', $data);

		if ($return) {
			CKfof::enqueueMessage(CKText::_('CAROUSELCK_MIGRATION_SUCCESS'), 'success');
		} else {
			CKfof::enqueueMessage(CKText::_('CAROUSELCK_MIGRATION_ERROR'), 'error');
		}
		CKfof::redirect();
	}

	protected function makeBackup($id, $params) {
		$path = JPATH_ROOT . '/administrator/components/com_carouselck/backup/';

		// create the folder
		if (! CKFolder::exists($path)) {
			CKFolder::create($path);
		}

		$exportfiledest = $path . '/backup_' . $id . '_' . date("d-m-Y-G-i-s") . '.ssck';
		CKFile::write($exportfiledest, $params);
	}
}
