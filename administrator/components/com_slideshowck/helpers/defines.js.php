<?php
/**
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;
?>
<script>
	var SLIDESHOWCK = {
		TOKEN : '<?php echo \Joomla\CMS\Factory::getSession()->getFormToken() ?>=1'
		, URIBASE : '<?php echo Slideshowck\CKUri::base(true) ?>'
		, URIBASEABS : '<?php echo Slideshowck\CKUri::base() ?>'
		, URIROOT : '<?php echo Slideshowck\CKUri::root(true) ?>'
		, URIROOTABS : '<?php echo Slideshowck\CKUri::root() ?>'
		, HASPAGEBUILDERCK : '<?php echo (int)file_exists(JPATH_ROOT . '/administrator/components/com_pagebuilderck') ?>'
		, ADMIN_URL : '<?php echo Slideshowck\CKUri::root(true) ?>/administrator/index.php?option=com_slideshowck'
		, FRONT_URL : '<?php echo Slideshowck\CKUri::root(true) ?>/index.php?option=com_slideshowck'
		, BASE_URL : '<?php echo Slideshowck\CKUri::base(true) ?>/index.php?option=com_slideshowck'
		, USERID : '<?php echo \Joomla\CMS\Factory::getUser()->id ?>'
		, ISJ4 : '<?php echo version_compare(JVERSION, "4") ?>'
	};
</script>