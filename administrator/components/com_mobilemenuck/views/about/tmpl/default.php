<?php
/**
 * @name		Mobile Menu CK
 * @package		com_mobilemenuck
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$doc = \Mobilemenuck\CKFof::getDocument();
if (version_compare(JVERSION, '4') < 0) $doc->addStyleSheet(MOBILEMENUCK_MEDIA_URI . '/assets/admin.css');
$doc->addStylesheet(MOBILEMENUCK_MEDIA_URI . '/assets/ckframework.css');

// check for the update
//$latest_version = MobilemenuckController::getLatestVersion();
//$isOutdated = MobilemenuckController::isOutdated();

?>
<div class="ckadminarea">
<?php
/*if ($latest_version !== false) {
	if ($isOutdated) {
		echo '<p class="ckalert ckalert-danger">' . \Mobilemenuck\CKText::_('CK_IS_OUTDATED') . ' : <b>' . $latest_version . '</b></p>';
	} else {
		echo '<p class="ckalert ckalert-success">' . \Mobilemenuck\CKText::_('CK_IS_UPTODATE') . '</p>';
	}
}*/
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
	.ckabout {
		background: url("https://media.joomlack.fr/images/texture/texture_003.jpg") center center repeat;
		background-size: auto auto;
		color: #fff;
		font-family: verdana;
		font-size: 13px;
		border-radius: 5px;
		box-shadow: #111 0 0 5px;
		background-size: cover;
		position: relative;
		overflow: hidden;
	}
	.ckabout > .inner {
		padding: 20px;
		background: rgba(40,40,40,0.7);
	}
	.ckabout > .inner > * {
		padding: 10px;
	}
	.ckabout a {
		color: orange;
	}
	.ckabout .ckbutton {
		background: rgba(255,255,255, 0.2);
		border-radius: 4px;
		padding: 10px 20px;
		color: #fff;
		text-transform: uppercase;
		font-size: 11px;
	}
	.ckabout .ckbutton:hover {
		background: rgba(255,153,0, 0.3);
		color: orange;
	}
</style>
<div class="ckaboutversion"><?php echo 'Mobile Menu CK Light Version ' . $this->ckversion; ?></div>
<div class="ckabout">
	<div class="inner">
		<div class="ckcenter"><img src="<?php echo MOBILEMENUCK_MEDIA_URI ?>/images/logo_mobilemenuck_large.png" /></div>
		<p class="ckcenter"><a href="https://www.joomlack.fr" target="_blank">https://www.joomlack.fr</a></p>
		<p class="ckcenter"><?php echo \Mobilemenuck\CKText::_('CK_MOBILEMENUCK_DESC'); ?></p>
		<p class="ckcenter"><a class="ckbutton" href="https://www.joomlack.fr/documentation" target="_blank"><?php echo \Mobilemenuck\CKText::_('CK_READ_DOCUMENTATION'); ?></a></p>
	</div>
</div>
<hr />
<?php /*<div class="alert"><?php echo \Mobilemenuck\CKText::_('CK_VOTE_JED'); ?>&nbsp;<a href="https://extensions.joomla.org/extensions/extension/style-a-design/articles-styling/custom-fields-ck/" target="_blank" class="btn btn-small btn-warning"><?php echo \Mobilemenuck\CKText::_('CK_VOTE_JED_BUTTON'); ?></a></div> 
*/ ?>
<?php
MobilemenuckController::displayReleaseNotes();
?>
</div>
