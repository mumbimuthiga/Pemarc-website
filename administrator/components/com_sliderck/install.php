<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2015. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */
 
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Installer\Installer;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Language\Text;

/*
	preflight which is executed before install and update
	install
	update
	uninstall
	postflight which is executed after install and update
	*/

if (version_compare(JVERSION, 6, '>=')) {
	return new class () implements InstallerScriptInterface {

		public function install(InstallerAdapter $adapter): bool
		{
			// not used
			return true;
		}

		public function update(InstallerAdapter $adapter): bool
		{
			// not used
			return true;
		}

		public function uninstall(InstallerAdapter $adapter): bool
		{
			return SliderCK_installer_uninstall($adapter);
		}

		public function preflight(string $type, InstallerAdapter $adapter): bool
		{
			return SliderCK_installer_preflight($type, $adapter);
		}

		public function postflight(string $type, InstallerAdapter $adapter): bool
		{
			return SliderCK_installer_postflight($type, $adapter);
		}
	};

} else {
	class com_sliderckInstallerScript {

		function install($parent) {
			// not used
		}
		
		function update($parent) {
			// not used
		}

		function uninstall($parent) {
			return SliderCK_installer_uninstall($parent);
		}

		function preflight($type, $parent) {
			return SliderCK_installer_preflight($type, $parent);
		}

		// run on install and update
		function postflight($type, $parent) {
			jimport('joomla.installer.installer');
			return SliderCK_installer_postflight($type, $parent);
		}
	}
}

function SliderCK_installer_uninstall($parent) {
	// disable all plugins and modules
	$db = \Joomla\CMS\Factory::getDbo();
	$db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%sliderck%'");
	$db->execute();

	$db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `type` = 'plugin' AND `element` LIKE '%sliderck%' AND `folder` NOT LIKE '%sliderck%'");
	$db->execute();
	return true;
}

function SliderCK_installer_preflight($type, $parent) {
	// disable the install on Joomla 3
	if (version_compare(JVERSION, '4') === -1) {
		throw new RuntimeException('This version of Slider CK can not be installed on Joomla 3. Please use the version 2.3.1');
	}
	// disable the install on Joomla 4
	if (version_compare(JVERSION, '5', '<')) {
		Factory::getApplication()->enqueueMessage('This version of Slider CK can not be installed on Joomla 4. Please use the version 2.4.6.', 'error');
		return false;
	}

	// check if a pro version already installed
	$xmlPath = JPATH_ROOT . '/administrator/components/com_sliderck/sliderck.xml';

	// if no file already exists
	if (! file_exists($xmlPath)) return true;

	$xmlData = SliderCK_installer_getXmlData($xmlPath);
	$isProInstalled = ((int)$xmlData->ckpro);

	if ($isProInstalled) {
		throw new RuntimeException('Slider CK Light cannot be installed over Slider CK Pro. Please install Slider CK Pro. To downgrade, please first uninstall Slider CK Pro. <a href="https://forum.joomlack.fr/index.php/slider-ck/17181-how-to-update-slider-ck-v1-to-v2" target="_blank">Read more</a>');
		// return false;
	}

	// check if a V1 version is installed with the params (needs the pro)
	$xmlPath = JPATH_ROOT . '/modules/mod_sliderck/mod_sliderck.xml';

	// if no file already exists
	if (! file_exists($xmlPath)) return true;

	$xmlData = SliderCK_installer_getXmlData($xmlPath);
	$installedVersion = ((int)$xmlData->version );
	// if the installed version is the V1
	if(version_compare($installedVersion, '2.0.0', '<')) {
		// if the params is also installed
		if (file_exists(JPATH_ROOT . '/plugins/system/sliderckparams/sliderckparams.xml')) {
			throw new RuntimeException('Slider CK Light cannot be installed over Slider CK V1 + Params. Please install Slider CK Pro to get the same features as previously, else you may loose your existing settings. To downgrade, please first uninstall Slider CK Params. <a href="https://forum.joomlack.fr/index.php/slider-ck/17181-how-to-update-slider-ck-v1-to-v2" target="_blank">Read more</a>');
			// return false;
		}

		// install over V1, but not over Params. Then install the folder plugin
		// $this->doInstallFolderPlugin = true; // disabled since 2.4.0 and removed the system and folder plugins from the installer
	}

	return true;
}

function SliderCK_installer_getXmlData($file) {
	if ( ! is_file($file))
	{
		return '';
	}

	$xml = simplexml_load_file($file);

	if ( ! $xml || ! isset($xml['version']))
	{
		return '';
	}

	return $xml;
}

// run on install and update
function SliderCK_installer_postflight($type, $parent) {
	// install modules and plugins
	$db = \Joomla\CMS\Factory::getDbo();
	$status = array();
	$src_ext = dirname(__FILE__).'/administrator/extensions';

	// module
	$result = SliderCK_installer_installExtension($src_ext.'/mod_sliderck');
	$status[] = array('name'=>'Slider CK - Module','type'=>'module', 'result'=>$result);

	foreach ($status as $statu) {
		if ($statu['result'] == true) {
			$alert = 'success';
			$icon = 'icon-ok';
			$text = 'Successful';
		} else {
			$alert = 'warning';
			$icon = 'icon-cancel';
			$text = 'Failed';
		}
		echo '<div class="alert alert-' . $alert . '"><i class="icon ' . $icon . '"></i>Installation and activation of the <b>' . $statu['type'] . ' ' . $statu['name'] . '</b> : ' . $text . '</div>';
	}

	return true;
}

function SliderCK_installer_installExtension($path) {
	$installer = new Installer();
	$installer->setDatabase(Factory::getDbo());

	return $installer->install($path);
}

