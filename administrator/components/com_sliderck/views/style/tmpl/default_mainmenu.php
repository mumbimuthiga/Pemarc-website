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
?>
<div id="ckheader">
	<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" width="35" height="35"></a></div>
	<div class="ckheadermenu">
		<div class="ckheadertitle">SLIDER CK</div>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckImportParams('<?php echo $this->input->get('id',0,'int'); ?>')">
			<span class="fa fas fa-file-import cktip" data-placement="bottom" title="<?php echo \Sliderck\CKText::_('CK_IMPORT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Sliderck\CKText::_('CK_IMPORT') ?></span>
		</a>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckExportParams('<?php echo $this->input->get('id',0,'int'); ?>')">
			<span class="fa fas fa-file-export cktip" data-placement="bottom" title="<?php echo \Sliderck\CKText::_('CK_EXPORT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Sliderck\CKText::_('CK_EXPORT') ?></span>
		</a>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckClearFields()">
			<span class="fa fas fa-broom cktip" data-placement="bottom" title="<?php echo \Sliderck\CKText::_('CK_CLEAR_FIELDS') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Sliderck\CKText::_('CK_CLEAR_FIELDS') ?></span>
		</a>
		<a href="javascript:void(0);" class="ckheadermenuitem" onclick="ckPreviewStylesparams()">
			<span class="fa fas fa-eye cktip" data-placement="bottom" title="<?php echo \Sliderck\CKText::_('CK_PREVIEW') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Sliderck\CKText::_('CK_PREVIEW') ?></span>
		</a>
		<a href="javascript:void(0)" onclick="window.parent.CKBox.close()" class="ckheadermenuitem ckcancel">
			<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo \Sliderck\CKText::_('CK_EXIT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Sliderck\CKText::_('CK_EXIT') ?></span>
		</a>
		<a href="javascript:void(0);" id="ckpopupstyleswizard_save" class="ckheadermenuitem cksave" onclick="ckSaveStylesparams(this, '<?php echo $this->input->get('id',0,'int'); ?>', '<?php echo $this->input->get('layout','','string'); ?>')">
			<span class="fa fa-check cktip" data-placement="bottom" title="<?php echo \Sliderck\CKText::_('CK_SAVE') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Sliderck\CKText::_('CK_SAVE') ?></span>
		</a>
	</div>
</div>
