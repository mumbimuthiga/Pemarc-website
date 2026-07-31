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

/**
 * Helper Class.
 */
class SliderckHelpersourceFolder {

	private static $folderLabels = array();

	private static $imagesOrderByLabels = array();

	private static $params;

	/*
	 * Get the items from the source
	 */
	public static function getItems($params, $folder = null) {
		if (empty(self::$params)) {
			self:$params = $params;
		}

		$folder = $folder ? $folder : $params->get('folder', '');
		if (! $folder) {
			return false;
		}

		// encode the folder path, needed if contains an accent
		// $foldern = iconv("UTF-8", "ISO-8859-1//TRANSLIT", urldecode($folder)); // pose probleme !
		$foldern = $folder; // temporary workaround to the line above
		// encode the folder path, needed if contains an accent
		try {
			$translatedDirectory = iconv("UTF-8", "ISO-8859-1//TRANSLIT", urldecode($folder));
			if ($translatedDirectory) $foldern = $translatedDirectory;
		} catch (Exception $e) {
			echo 'Slider CK Message : ',  $e->getMessage(), "\n";
		}
		// $files = \Sliderck\CKFolder::files(JPATH_SITE . '/' . $foldern, '.png|.jpg|.bmp|.tif|.tiff|.jpeg', false, false);
		$files = \Sliderck\CKFolder::files(JPATH_SITE . '/' . $foldern, '\.(?:png|jpe?g|bmp|tiff?)$', false, false);
		natsort($files);

		// load the labels from the folder
		self::$imagesOrderByLabels = array();
		self::getImageLabelsFromFolder($foldern);

		// set the images order
		$order = $params->get('order', 'natural');
$order = 'labels';
		if ($order == 'random') {
			shuffle($files);
		} else if($order == 'labels') {
			natsort($files);
			$files = array_map(array(__CLASS__, 'formatPath'), $files);
			$baseDir = self::formatPath($folder);
			$labelsOrder = array_reverse(self::$imagesOrderByLabels);

			foreach ($labelsOrder as $name) {
				if (in_array($name, $files)) array_unshift($files, $name);
			}
			// now make it unique
			$files = array_unique($files);
		} else {
			natsort($files);
		}

		$items = array();

		foreach ($files as $file) {
			// get the data for the image
			$filedata = self::getImageDataFromfolder($file, $foldern);

			$file = str_replace("\\", "/", utf8_encode($file));
			$item = new stdClass();
			$item->path = trim(str_replace('\\', '/', $folder), '/') . '/' . $file;
			$item->link = $filedata->link ? $filedata->link : ($params->get('linkautoimage', '1') && $params->get('lightbox', 'mediabox') != 'none' ? \Sliderck\CKUri::root(true) . '/' . $item->path : '' );
			$item->title = trim($filedata->title);
			$item->desc = trim($filedata->desc);

			$items[] = $item;
		}
		return $items;
	}

	/*
	 * Load the data for the image (title and description)
	 */
	private static function getImageDataFromfolder($file, $folder) {
		$filename = explode('/', $file);
		$filename = end($filename);
		$dirindex = self::cleanName($folder);
		$fileindex = self::cleanName($filename);

		if (! empty(self::$folderLabels[$dirindex]) && ! empty(self::$folderLabels[$dirindex][$fileindex])) {
			$item = self::$folderLabels[$dirindex][$fileindex];
		} else {
			$item = new stdClass();
			$item->title = null;
			$item->desc = null;
			$item->link = null;
		}

		return $item;
	}

	/*
	 * Load the data for the image (title and description)
	 */
	private static function getImageLabelsFromFolder($folder) {
		$dirindex = self::cleanName($folder);
		if (! empty(self::$folderLabels[$dirindex])) return;

		$items = array();
		$item = new stdClass();

		// get the language
		$lang = \Sliderck\CKFof::getLanguage();
		$langtag = $lang->getTag(); // returns fr-FR or en-GB

		// load the image data from txt
		if (file_exists(JPATH_ROOT . '/' . $folder . '/labels.' . $langtag . '.txt')) {
			$data = file_get_contents(JPATH_ROOT . '/' . $folder . '/labels.' . $langtag . '.txt');
		} else if (file_exists(JPATH_ROOT . '/' . $folder . '/labels.txt')) {
			$data = file_get_contents(JPATH_ROOT . '/' . $folder . '/labels.txt');
		} else {
			return null;
		}
		$doUTF8encode = true;
		// remove UTF-8 BOM and normalize line endings
		if (!strcmp("\xEF\xBB\xBF", substr($data,0,3))) {  // file starts with UTF-8 BOM
			$data = substr($data, 3);  // remove UTF-8 BOM
			$doUTF8encode = false;
		}
		$data = str_replace("\r", "\n", $data);  // normalize line endings

		// if no data found, exit
		if(! $data) return null;

		// explode the file into rows
		// $imgdatatmp = explode("\n", $data);
		$imgdatatmp = preg_split("/\r\n|\n|\r/", $data, -1, PREG_SPLIT_NO_EMPTY);

		$parmsnumb = count($imgdatatmp);
		for ($i = 0; $i < $parmsnumb; $i++) {
			$imgdatatmp[$i] = trim($imgdatatmp[$i]);
			$line = explode('|', $imgdatatmp[$i]);
			// store the order or files from the TXT file
			self::$imagesOrderByLabels[] = $line[0];

			$item = new stdClass();

			$item->index = self::cleanName($line[0]);
			// $item->title = (isset($line[1])) ? ( $doUTF8encode ? htmlspecialchars(utf8_encode($line[1])) : ($line[1]) ) : '';
			// $item->desc = (isset($line[2])) ? ( $doUTF8encode ? htmlspecialchars(utf8_encode($line[2])) : ($line[2]) ) : '';
			// $item->link = (isset($line[3])) ? ( $doUTF8encode ? htmlspecialchars(utf8_encode($line[3])) : ($line[3]) ) : '';
			$item->title = (isset($line[1])) ? $line[1] : '';
			$item->desc = (isset($line[2])) ? $line[2] : '';
			$item->link = (isset($line[3])) ? $line[3] : '';

			$items[$item->index] = $item;
		}


		self::$folderLabels[$dirindex] = $items;
	}

	/*
	 * Remove special character
	 */
	private static function cleanName($path) {
		return preg_replace('/[^a-z0-9]/i', '_', $path);
	}

	/*
	 * Format the path to use only /
	 */
	public static function formatPath($p) {
			return trim(str_replace("\\", "/", $p), "/");
	}
}
