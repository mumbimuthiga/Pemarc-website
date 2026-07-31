<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$document = \Sliderck\CKFof::getDocument();
$document->addStyleSheet(SLIDERCK_MEDIA_URI . '/assets/ckframework.css');

// get the version installed
$installed_version = 'UNKOWN';
if ($xml_installed = simplexml_load_file(JPATH_SITE .'/administrator/components/com_sliderck/sliderck.xml')) {
	$installed_version = (string)$xml_installed->version;
}

// loads the language files from the frontend
$lang	= \Sliderck\CKFof::getLanguage();
$lang->load('mod_sliderck', JPATH_SITE . '/modules/mod_sliderck', $lang->getTag(), false);
$lang->load('mod_sliderck', JPATH_SITE, $lang->getTag(), false);
?>
<style>
	.ckaboutversion {
		margin: 10px;
		padding: 10px;
		font-size: 20px;
		font-color: #000;
		text-align: center;
	}
	.ckcenter {
		margin: 10px 0;
		text-align: center;
	}
</style>
<div style="display:flex;flex-wrap:wrap;">
	<div style="flex: 0 0 250px">
	<?php SliderckHelper::addSidebar(); ?>
	</div>
	<div style="flex: 1 1 auto">
		<div class="ckaboutversion">SLIDER CK <?php echo $installed_version; ?> LIGHT</div>
		<div class="ckcenter"><img src="<?php echo SLIDERCK_MEDIA_URI ?>/images/logo_sliderck_110.png" /></div>
		<p class="ckcenter"><a href="http://www.joomlack.fr" target="_blank">http://www.joomlack.fr</a></p>
		<p class="ckcenter"><?php echo \Sliderck\CKText::_('CK_SLIDERCK_DESC'); ?></p><hr />
		<div class="ckalert"><?php echo \Sliderck\CKText::_('CK_VOTE_JED'); ?>&nbsp;<a href="https://extensions.joomla.org/extensions/extension/photos-a-images/slideshow/slider-ck/" target="_blank" class="ckbutton ckbutton-small ckbutton-warning"><?php echo \Sliderck\CKText::_('CK_VOTE_JED_BUTTON'); ?></a></div>
	</div>
</div>
<?php

