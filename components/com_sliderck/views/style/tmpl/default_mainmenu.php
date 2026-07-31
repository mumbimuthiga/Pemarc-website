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
$input = new \Joomla\CMS\Input\Input();
?>
<div id="mainmenuck" class="clearfix">
	<div class="ckheader clearfix">
		<table>
			<tr>
				<td style="vertical-align:top;">
					<div class="ckheaderlogo"></div>
				</td>
				<td style="width:100%;">
					<div id="ckmessage">
						<div></div>
					</div>
					<div class="ckpopupbuttons_right">
						<div class="ckpopupbutton big">
							<a href="javascript:void(0);" id="ckpopupstyleswizard_import" class="ckimport black" onclick="ckImportParams('<?php echo $input->get('id',0,'int'); ?>')">
								<?php echo \Sliderck\CKText::_('CK_IMPORT') ?>
							</a>
						</div>
						<div class="ckpopupbutton big">
							<a href="javascript:void(0);" id="ckpopupstyleswizard_export" class="ckexport black" onclick="ckExportParams('<?php echo $input->get('id',0,'int'); ?>')">
								<?php echo \Sliderck\CKText::_('CK_EXPORT') ?>
							</a>
						</div>
						<div class="ckpopupbutton big">
							<a href="javascript:void(0);" id="ckpopupstyleswizard_clearfields" class="ckcancel" onclick="ckClearFields()">
								<?php echo \Sliderck\CKText::_('CK_CLEAR_FIELDS') ?>
							</a>
						</div>
						<div class="ckpopupbutton big">
							<a href="javascript:void(0);" id="ckpopupstyleswizard_makepreview" class="ckpreview black" onclick="ckPreviewStylesparams()">
								<?php echo \Sliderck\CKText::_('CK_PREVIEW') ?>
							</a>
						</div>
						<div class="ckpopupbutton big">
							<a href="javascript:void(0);" id="ckpopupstyleswizard_save" class="cksave black" onclick="ckSaveStylesparams(this, '<?php echo $input->get('id',0,'int'); ?>', '<?php echo $input->get('layout','','string'); ?>')">
								<?php echo \Sliderck\CKText::_('CK_SAVE') ?>
							</a>
						</div>
						<?php if ($input->get('layout', '', 'string') === 'modal') { ?>
						<div class="ckpopupbutton big">
							<a href="javascript:void(0)" class="ckcancel" onclick="window.parent.CKBox.close()">
								<?php echo \Sliderck\CKText::_('CK_CLOSE') ?>
							</a>
						</div>
						<?php } else { ?>
						<div class="ckpopupbutton big">
							<a href="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_sliderck'); ?>" class="ckcancel" >
								<?php echo \Sliderck\CKText::_('CK_EXIT') ?>
							</a>
						</div>
						<?php } ?>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>