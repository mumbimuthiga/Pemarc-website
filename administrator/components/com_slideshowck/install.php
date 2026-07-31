<?php
/**
 * @name		Slideshow CK
 * @package		com_slideshowck
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

		// private string $minimumJoomla = '4.4.0';
		// private string $minimumPhp    = '7.4.0';

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
			return CK_installer_uninstall($adapter);
		}

		public function preflight(string $type, InstallerAdapter $adapter): bool
		{
			return CK_installer_preflight($type, $adapter);
			
			// if (version_compare(PHP_VERSION, $this->minimumPhp, '<')) {
				// Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_PHP'), $this->minimumPhp), 'error');
				// return false;
			// }

			// if (version_compare(JVERSION, $this->minimumJoomla, '<')) {
				// Factory::getApplication()->enqueueMessage(sprintf(Text::_('JLIB_INSTALLER_MINIMUM_JOOMLA'), $this->minimumJoomla), 'error');
				// return false;
			// }

			// return true;
		}

		public function postflight(string $type, InstallerAdapter $adapter): bool
		{
			return CK_installer_postflight($type, $adapter);
		}
	};

} else {
	class com_slideshowckInstallerScript {

		function install($parent) {
			// not used
		}
		
		function update($parent) {
			// not used
		}

		function uninstall($parent) {
			return CK_installer_uninstall($parent);
		}

		function preflight($type, $parent) {
			return CK_installer_preflight($type, $parent);
		}

		// run on install and update
		function postflight($type, $parent) {
			jimport('joomla.installer.installer');
			return CK_installer_postflight($type, $parent);
		}
	}
}

function CK_installer_uninstall($adapter) {
	// disable all plugins and modules
	$db = \Joomla\CMS\Factory::getDbo();
	$db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%slideshowck%'");
	$db->execute();

	// $db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `type` = 'plugin' AND `element` LIKE '%slideshowck%' AND `folder` NOT LIKE '%slideshowck%'");
	// $db->execute();
	return true;
}

function CK_installer_preflight($type, $adapter) {
	// disable the install on Joomla 3 since 3.2.0
	if (version_compare(JVERSION, '4', '<')) {
		throw new RuntimeException('This version of Slideshow CK can not be installed on Joomla 3. Please use the version 2.4.3.');
	}
	// disable the install on Joomla 4 after 3.4.8
	if (version_compare(JVERSION, '5', '<')) {
		Factory::getApplication()->enqueueMessage('This version of Slideshow CK can not be installed on Joomla 4. Please use the version 2.7.5.', 'error');
		return false;
	}

	// check if a pro version already installed
	$xmlPath = JPATH_ROOT . '/administrator/components/com_slideshowck/slideshowck.xml';

	// if no file already exists
	if (! file_exists($xmlPath)) return true;

	$xmlData = CK_installer_getXmlData($xmlPath);
	$isProInstalled = ((int)$xmlData->ckpro);

	if ($isProInstalled) {
		throw new RuntimeException('Slideshow CK Light cannot be installed over Slideshow CK Pro. Please install Slideshow CK Pro. To downgrade, please first uninstall Slideshow CK Pro. <a href="https://www.joomlack.fr/en/documentation/48-slideshow-ck/246-migration-from-slideshow-ck-version-1-to-version-2" target="_blank">Read more</a>');
		// return false;
	}

	// check if a V1 version is installed with the params (needs the pro)
	$xmlPath = JPATH_ROOT . '/modules/mod_slideshowck/mod_slideshowck.xml';

	// if no file already exists
	if (! file_exists($xmlPath)) return true;

	$xmlData = CK_installer_getXmlData($xmlPath);
	$installedVersion = ((int)$xmlData->version );
	// if the installed version is the V1
	if(version_compare($installedVersion, '2.0.0', '<')) {
		// if the params is also installed
		if (file_exists(JPATH_ROOT . '/plugins/system/slideshowckparams/slideshowckparams.xml')) {
			throw new RuntimeException('Slideshow CK Light cannot be installed over Slideshow CK V1 + Params. Please install Slideshow CK Pro to get the same features as previously, else you may loose your existing settings. To downgrade, please first uninstall Slideshow CK Params. <a href="https://www.joomlack.fr/en/documentation/48-slideshow-ck/246-migration-from-slideshow-ck-version-1-to-version-2" target="_blank">Read more</a>');
			// return false;
		}
	}

	return true;
}

function CK_installer_getXmlData($file) {
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
function CK_installer_postflight($type, $adapter) {
	// install modules and plugins
	$db = \Joomla\CMS\Factory::getDbo();
	$status = array();
	$src_ext = dirname(__FILE__).'/administrator/extensions';

	// module
	$result = CK_installer_installExtension($src_ext.'/mod_slideshowck');
	$status[] = array('name'=>'Slideshow CK - Module','type'=>'module', 'result'=>$result);

	// system plugin
	/*$result = $installer->install($src_ext.'/slideshowck');
	$status[] = array('name'=>'System - Slideshow CK','type'=>'plugin', 'result'=>$result);
	// system plugin must be enabled for user group limits and private areas
	$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'slideshowck' AND `type` = 'plugin'");
	$db->execute();*/

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

function CK_installer_installExtension($path) {
	$installer = new Installer();
	$installer->setDatabase(Factory::getDbo());

	return $installer->install($path);
}

