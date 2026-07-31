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
	class com_maximenuckInstallerScript {

		function install($parent) {
			// not used
			return true;
		}
		
		function update($parent) {
			// not used
			return true;
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

function CK_installer_uninstall($parent) {
	// disable all plugins and modules
	$db = \Joomla\CMS\Factory::getDbo();
	$db->setQuery("UPDATE `#__modules` SET `published` = 0 WHERE `module` LIKE '%maximenuck%'");
	$db->execute();

	$db->setQuery("UPDATE `#__extensions` SET `enabled` = 0 WHERE `type` = 'plugin' AND `element` LIKE '%maximenuck%' AND `folder` NOT LIKE '%maximenuck%'");
	$db->execute();
	return true;
}

function CK_installer_preflight($type, $parent) {
	// disable the install on Joomla 3
	if (version_compare(JVERSION, '4') === -1) {
		throw new RuntimeException('This version of Maximenu CK can not be installed on Joomla 3. Please use the version 10.0.12.');
	}

	// disable the install on Joomla 4
	if (version_compare(JVERSION, '5', '<')) {
		throw new RuntimeException('This version of Maximenu CK can not be installed on Joomla 4. Please use the version 10.1.19.');
	}

	// check for V8 modules
	$db = \Joomla\CMS\Factory::getDbo();
	$db->setQuery("SELECT id FROM #__modules WHERE `module` = 'mod_maximenuck' AND `params` LIKE '%\"isv9\":\"0\"%'");
	$oldItems = $db->loadObjectList();

	if (! empty($oldItems)) {
		foreach ($oldItems as $oldItem) {
			throw new RuntimeException('<div>Installation error : You have an old module in V8 mode, ID <b>' . $oldItem->id . '. You must convert your module, please check the infos on https://forum.joomlack.fr</b>.<br/> This check is made to help you to update your website safely.</div>');
		}
		return;
	}
	
	// check if a pro version already installed
	$xmlPath = JPATH_ROOT . '/administrator/components/com_maximenuck/maximenuck.xml';

	// if no file already exists
	if (! file_exists($xmlPath)) return true;

	$xmlData = CK_installer_getXmlData($xmlPath);
	$isProInstalled = ((int)$xmlData->ckpro);

	if ($isProInstalled) {
		throw new RuntimeException('Maximenu CK Light cannot be installed over Maximenu CK Pro. Please install Maximenu CK Pro. To downgrade, please first uninstall Maximenu CK Pro. <a href="https://www.joomlack.fr/en/documentation/maximenu-ck/270-migration-from-maximenu-ck-version-8-to-version-9" target="_blank">Read more</a>');
		// return false;
	}

	// check if a V1 version is installed with the params (needs the pro)
	$xmlPath = JPATH_ROOT . '/modules/mod_maximenuck/mod_maximenuck.xml';

	// if no file already exists
	if (! file_exists($xmlPath)) return true;

	$xmlData = CK_installer_getXmlData($xmlPath);
	$installedVersion = ((int)$xmlData->version );
	// if the installed version is the V1
	if(version_compare($installedVersion, '9.0.0', '<')) {
		// if the params is also installed
		if (file_exists(JPATH_ROOT . '/plugins/system/maximenuckparams/maximenuckparams.xml')) {
			throw new RuntimeException('Maximenu CK Light cannot be installed over Maximenu CK V8 + Params. Please install Maximenu CK Pro to get the same features as previously, else you may loose your existing settings. To downgrade, please first uninstall Maximenu CK Params. <a href="https://www.joomlack.fr/en/documentation/maximenu-ck/270-migration-from-maximenu-ck-version-8-to-version-9" target="_blank">Read more</a>');
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
function CK_installer_postflight($type, $parent) {
	// install modules and plugins
	$db = \Joomla\CMS\Factory::getDbo();
	$status = array();
	$src_ext = dirname(__FILE__).'/administrator/extensions';

	// module
	$result = CK_installer_installExtension($src_ext.'/mod_maximenuck');
	$status[] = array('name'=>'Maximenu CK - Module','type'=>'module', 'result'=>$result);

	// disable the old update site
	$db->setQuery("UPDATE #__update_sites SET enabled = '0' WHERE `location` = 'http://update.joomlack.fr/mod_maximenuck_update.xml'");
	$result3 = $db->execute();
	// disable the old update site
	$db->setQuery("UPDATE #__update_sites SET enabled = '0' WHERE `location` = 'http://update.joomlack.fr/com_maximenuck_update.xml'");
	$result4 = $db->execute();

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

	// check for table creation
	require_once JPATH_ROOT . '/administrator/components/com_maximenuck/helpers/helper.php';
	Maximenuck\Helper::checkDbIntegrity();

	return true;
}

function CK_installer_installExtension($path) {
	$installer = new Installer();
	$installer->setDatabase(Factory::getDbo());

	return $installer->install($path);
}

