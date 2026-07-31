<?php

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
			return MobileMenucCK_installer_uninstall($adapter);
		}

		public function preflight(string $type, InstallerAdapter $adapter): bool
		{
			return MobileMenucCK_installer_preflight($type, $adapter);
			
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
			return MobileMenucCK_installer_postflight($type, $adapter);
		}
	};

} else {
	class com_mobilemenuckInstallerScript {

		function install($parent) {
			// not used
		}
		
		function update($parent) {
			// not used
		}

		function uninstall($parent) {
			return MobileMenucCK_installer_uninstall($parent);
		}

		function preflight($type, $parent) {
			return MobileMenucCK_installer_preflight($type, $parent);
		}

		// run on install and update
		function postflight($type, $parent) {
			jimport('joomla.installer.installer');
			return MobileMenucCK_installer_postflight($type, $parent);
		}
	}
}


function MobileMenucCK_installer_uninstall($parent) {
	jimport('joomla.installer.installer');
	$db = \Joomla\CMS\Factory::getDbo();
	// Check first that the plugin exist
	$db->setQuery('SELECT `extension_id` FROM #__extensions WHERE `element` = "mobilemenuck" AND `type` = "plugin"');
	$id = $db->loadResult();

	if($id)
	{
		$installer = new \Joomla\CMS\Installer\Installer;
		$result = $installer->uninstall('plugin', $id);
	}
	return true;
}

function MobileMenucCK_installer_preflight($type, $parent) {
	// disable the install on Joomla 3
	if (version_compare(JVERSION, '4', '<')) {
		throw new RuntimeException('This version of Mobile Menu CK can not be installed on Joomla 3. Please use the version 1.6.11.');
	}
	// disable the install on Joomla 4
	if (version_compare(JVERSION, '5', '<')) {
		Factory::getApplication()->enqueueMessage('This version of Mobile Menu CK can not be installed on Joomla 4. Please use the version 1.6.11.', 'error');
		return false;
	}

	// check if a pro version already installed
	$xmlPath = JPATH_ROOT . '/administrator/components/com_mobilemenuck/mobilemenuck.xml';
	
	// if no file already exists
	if (! file_exists($xmlPath)) return true;

	$xmlData = MobileMenucCK_installer_getXmlData($xmlPath);
	$isProInstalled = ((int)$xmlData->ckpro);
	
	if ($isProInstalled) {
		throw new RuntimeException('Mobile Menu CK Light cannot be installed over Mobile Menu CK Pro. Please install Mobile Menu CK Pro. To downgrade, please first uninstall Mobile Menu CK Pro.');
		// return false;
	}
	return true;
}

function MobileMenucCK_installer_getXmlData($file) {
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
function MobileMenucCK_installer_postflight($type, $parent) {
	// install modules and plugins
	$db = \Joomla\CMS\Factory::getDbo();
	$status = array();
	$src_ext = dirname(__FILE__).'/administrator/extensions';

	// install the plugin
	$result = MobileMenucCK_installer_installExtension($src_ext.'/mobilemenuck');
	// auto enable the plugin
	$db->setQuery("UPDATE #__extensions SET enabled = '1' WHERE `element` = 'mobilemenuck' AND `type` = 'plugin'");
	$result = $db->execute();
	$status[] = array('name'=>'Mobile Menu CK - Plugin','type'=>'plugin', 'result'=>$result);

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

function MobileMenucCK_installer_installExtension($path) {
	$installer = new Installer();
	$installer->setDatabase(Factory::getDbo());

	return $installer->install($path);
}
