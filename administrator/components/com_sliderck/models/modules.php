<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

use Sliderck\CKModel;

class SliderckModelModules extends CKModel {

	/**
	 * Get the list of sliderck modules
	 */
	public function getModules() {
		// Create a new query object.
		$db = \Sliderck\CKFof::getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.id, a.title, a.published, a.position, a.params');
		$query->from($db->quoteName('#__modules') . ' AS a');
		$query->where('(a.published IN (0, 1))');
		$query->where('a.module = ' . $db->quote('mod_sliderck'));

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
		$results = $db->loadObjectList();

		return $results;
	}
}
