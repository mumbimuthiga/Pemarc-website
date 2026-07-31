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
	var CAROUSELCK = {
		TOKEN : '<?php echo \Joomla\CMS\Factory::getSession()->getFormToken() ?>=1'
		, URIBASE : '<?php echo \Carouselck\CKUri::base(true) ?>'
		, URIBASEABS : '<?php echo \Carouselck\CKUri::base() ?>'
		, URIROOT : '<?php echo \Carouselck\CKUri::root(true) ?>'
		, URIROOTABS : '<?php echo \Carouselck\CKUri::root() ?>'
		, HASPAGEBUILDERCK : '<?php echo (int)file_exists(JPATH_ROOT . '/administrator/components/com_pagebuilderck') ?>'
		, ADMIN_URL : '<?php echo \Carouselck\CKUri::root(true) ?>/administrator/index.php?option=com_carouselck'
		, FRONT_URL : '<?php echo \Carouselck\CKUri::root(true) ?>/index.php?option=com_carouselck'
		, BASE_URL : '<?php echo \Carouselck\CKUri::base(true) ?>/index.php?option=com_carouselck'
	};
</script>