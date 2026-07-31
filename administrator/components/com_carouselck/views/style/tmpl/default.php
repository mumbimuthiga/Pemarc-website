<?php
/**
 * @name		Carousel CK
 * @package		com_carouselck
 * @copyright	Copyright (C) 2019. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;

include_once(JPATH_ROOT . '/administrator/components/com_carouselck/helpers/defines.js.php');

Carouselck\CKFramework::load();
Carouselck\CKFramework::loadFaIconsInline();
CarouselckHelper::loadCkbox();

$imagespath = CAROUSELCK_MEDIA_URI .'/images/';
//\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
$doc = \Carouselck\CKFof::getDocument();
$doc->addStylesheet(CAROUSELCK_MEDIA_URI . '/assets/admin.css');
$doc->addStylesheet(\Carouselck\CKUri::root(true) . '/modules/mod_carouselck/themes/default/css/carouselck.css');
$doc->addScript(CAROUSELCK_MEDIA_URI . '/assets/jscolor/jscolor.js');
$doc->addScript(CAROUSELCK_MEDIA_URI . '/assets/admin.js');

$popupclass = ($this->input->get('layout', '', 'string') === 'modal') ? 'ckpopupwizard' : '';

// Load the JS strings
\Carouselck\CKText::script('CK_DOWNLOAD');
\Carouselck\CKText::script('CK_DELETE_STYLES_CONFIRM');
?>
<style>
#stylescontainerleft, #stylescontainerright {
	float :left;
	width: auto;
	padding: 10px;
	box-sizing: border-box;
}

#stylescontainerleft {
	width: 810px;
}
body.contentpane {
	padding-top: 65px;
}
</style>

<?php // Rules for the styles rendering ?>
<div class="menustylescustom" data-prefix="container" data-rule="[container]"></div>
<div class="menustylescustom" data-prefix="slide" data-rule="[slide]"></div>
<div class="menustylescustom" data-prefix="navigation" data-rule="[navigation]"></div>
<div class="menustylescustom" data-prefix="pagination" data-rule="[pagination]"></div>
<div class="menustylescustom" data-prefix="paginationdotthumbs" data-rule="[paginationdotthumbs]"></div>
<div class="menustylescustom" data-prefix="caption" data-rule="[caption]"></div>
<div class="menustylescustom" data-prefix="title" data-rule="[title]"></div>
<div class="menustylescustom" data-prefix="text" data-rule="[text]"></div>
<div class="menustylescustom" data-prefix="button" data-rule="[button]"></div>
<div class="menustylescustom" data-prefix="buttonhover" data-rule="[buttonhover]"></div>


<div id="ckheader">
	<div class="ckheaderlogo"><a href="https://www.joomlack.fr" target="_blank"><img title="JoomlaCK" src="https://media.joomlack.fr/images/logo_ck_white.png" width="35" height="35"></a></div>
	<div class="ckheadermenu">
		<div class="ckheadertitle">CAROUSEL CK</div>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckImportParams('<?php echo $this->input->get('id',0,'int'); ?>')">
			<span class="fa fas fa-file-import cktip" data-placement="bottom" title="<?php echo \Carouselck\CKText::_('CK_IMPORT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Carouselck\CKText::_('CK_IMPORT') ?></span>
		</a>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckExportParams('<?php echo $this->input->get('id',0,'int'); ?>')">
			<span class="fa fas fa-file-export cktip" data-placement="bottom" title="<?php echo \Carouselck\CKText::_('CK_EXPORT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Carouselck\CKText::_('CK_EXPORT') ?></span>
		</a>
		<a href="javascript:void(0);"  class="ckheadermenuitem" onclick="ckClearFields()">
			<span class="fa fas fa-broom cktip" data-placement="bottom" title="<?php echo \Carouselck\CKText::_('CK_CLEAR_FIELDS') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Carouselck\CKText::_('CK_CLEAR_FIELDS') ?></span>
		</a>
		<a href="javascript:void(0);" class="ckheadermenuitem" onclick="ckPreviewStylesparams()">
			<span class="fa fas fa-eye cktip" data-placement="bottom" title="<?php echo \Carouselck\CKText::_('CK_PREVIEW') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Carouselck\CKText::_('CK_PREVIEW') ?></span>
		</a>
		<a href="javascript:void(0)" onclick="window.parent.CKBox.close()" class="ckheadermenuitem ckcancel">
			<span class="fa fa-times cktip" data-placement="bottom" title="<?php echo \Carouselck\CKText::_('CK_EXIT') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Carouselck\CKText::_('CK_EXIT') ?></span>
		</a>
		<a href="javascript:void(0);" id="ckpopupstyleswizard_save" class="ckheadermenuitem cksave" onclick="ckSaveStylesparams(this, '<?php echo $this->input->get('id',0,'int'); ?>', '<?php echo $this->input->get('layout','','string'); ?>')">
			<span class="fa fa-check cktip" data-placement="bottom" title="<?php echo \Carouselck\CKText::_('CK_SAVE') ?>"></span>
			<span class="ckheadermenuitemtext"><?php echo \Carouselck\CKText::_('CK_SAVE') ?></span>
		</a>
	</div>
</div>

<div id="ckpopupstyleswizard" class="<?php echo $popupclass; ?>">
	<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" id="layoutcss" name="layoutcss" value="<?php echo $this->item->layoutcss; ?>" />
	<input type="hidden" id="params" name="params" value="<?php echo htmlspecialchars($this->item->params); ?>" />
	<input type="hidden" id="returnFunc" name="returnFunc" value="<?php echo htmlspecialchars($this->input->get('returnFunc', '', 'cmd')); ?>" />
	<div id="stylescontainer" style="min-width: 1300px;" class="animateck">
	<div id="stylescontainerleft" class="ckinterface">
		<label for="name" style="display: inline-block;"><?php echo \Carouselck\CKText::_('CK_NAME'); ?></label>
		<input type="text" id="name" name="name" value="<?php echo $this->item->name; ?>" />
		<div id="styleswizard_options" class="styleswizard">
			<div class="ckinterfacetablink current" data-tab="tab_container" data-group="main"><?php echo \Carouselck\CKText::_('CK_SLIDESHOW'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_caption" data-group="main"><?php echo \Carouselck\CKText::_('CK_CAPTION'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_title" data-group="main"><?php echo \Carouselck\CKText::_('CK_TITLE'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_description" data-group="main"><?php echo \Carouselck\CKText::_('CK_DESCRIPTION'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_button" data-group="main"><?php echo \Carouselck\CKText::_('CK_BUTTON'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_custom" data-group="main"><?php echo \Carouselck\CKText::_('CK_CUSTOM_CSS'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_presets" data-group="main"><?php echo \Carouselck\CKText::_('CK_PRESETS'); ?></div>
			<div class="ckclr"></div>
			<div class="ckinterfacetab current hascol" id="tab_container" data-group="main">
				<div class="ckcol_left">
					<div class="ckinterfacetablink current" data-tab="tab_mainslider" data-group="container"><?php echo \Carouselck\CKText::_('CK_SLIDE'); ?></div>
					<div class="ckinterfacetablink" data-tab="tab_mainnavigation" data-group="container"><?php echo \Carouselck\CKText::_('CK_NAVIGATION'); ?></div>
					<div class="ckinterfacetablink" data-tab="tab_mainpagination" data-group="container"><?php echo \Carouselck\CKText::_('CK_PAGINATION'); ?></div>
				</div>
				<div class="ckcol_right">
					<div class="ckinterfacetab current" id="tab_mainslider" data-group="container">
						<?php
						echo $this->interface->createMargins('slide');
						echo $this->interface->createBorders('slide');
						echo $this->interface->createRoundedCorners('slide');
						echo $this->interface->createShadow('slide');
						?>
					</div>
					<div class="ckinterfacetab" id="tab_mainnavigation" data-group="container">
						<?php echo CarouselckHelper::getProMessage() ?>
					</div>
					<div class="ckinterfacetab" id="tab_mainpagination" data-group="container">
						<?php echo CarouselckHelper::getProMessage() ?>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="ckinterfacetab" id="tab_caption" data-group="main">
				<div class="ckrow">
					<label for="layoutposition"><?php echo \Carouselck\CKText::_('CK_LAYOUT'); ?></label>
					<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/layout.png" />
					<?php 
					$html = '<span class="ckinfo" style="display: inline-block;"><i class="fas fa-info"></i><a href="https://www.joomlack.fr/en/joomla-extensions/carousel-ck" target="_blank">' . \Carouselck\CKText::_('CAROUSELCK_ONLY_PRO') . '</a></span>';
					echo $html
					?>
				</div>
				<?php echo $this->interface->createAll('caption'); ?>
			</div>
			<div class="ckinterfacetab" id="tab_title" data-group="main">
				<?php echo CarouselckHelper::getProMessage() ?>
			</div>
			<div class="ckinterfacetab" id="tab_description" data-group="main">
				<?php echo CarouselckHelper::getProMessage() ?>
			</div>
			<div class="ckinterfacetab hascol" id="tab_button" data-group="main">
				<div class="ckcol_left">
					<div class="ckinterfacetablink current" data-tab="tab_buttonnormal" data-group="button"><?php echo \Carouselck\CKText::_('CK_BUTTON'); ?></div>
					<div class="ckinterfacetablink" data-tab="tab_buttonhover" data-group="button"><?php echo \Carouselck\CKText::_('CK_BUTTON_HOVER'); ?></div>
				</div>
				<div class="ckcol_right">
					<div class="ckinterfacetab current" id="tab_buttonnormal" data-group="button">
						<?php echo CarouselckHelper::getProMessage() ?>
					</div>
					<div class="ckinterfacetab" id="tab_buttonhover" data-group="button">
						<?php echo CarouselckHelper::getProMessage() ?>
					</div>
					<div style="clear:both;"></div>
				</div>
			</div>
			
			<div class="ckinterfacetab" id="tab_custom" data-group="main">
				<div id="customcssbuttons">
					<div class="customcssbutton ckbutton" data-prefix="container" data-rule="[container] { }"><?php echo \Carouselck\CKText::_('CK_CONTAINER'); ?></div>
					<div class="customcssbutton ckbutton" data-prefix="caption" data-rule="[caption] { }"><?php echo \Carouselck\CKText::_('CK_CAPTION'); ?></div>
					<div class="customcssbutton ckbutton" data-prefix="title" data-rule="[title] { }"><?php echo \Carouselck\CKText::_('CK_TITLE'); ?></div>
					<div class="customcssbutton ckbutton" data-prefix="text" data-rule="[text] { }"><?php echo \Carouselck\CKText::_('CK_TEXT'); ?></div>
					<div class="customcssbutton ckbutton" data-prefix="button" data-rule="[button] { }"><?php echo \Carouselck\CKText::_('CK_BUTTON'); ?></div>
					<div class="customcssbutton ckbutton" data-prefix="buttonhover" data-rule="[buttonhover] { }"><?php echo \Carouselck\CKText::_('CK_BUTTON_HOVER'); ?></div>
					<div class="customcssbutton ckbutton" data-prefix="paginationdotthumbs" data-rule="[paginationdotthumbs] { }"><?php echo \Carouselck\CKText::_('CK_PAGINATION_WITH_DOTS'); ?></div>
				</div>
				<textarea id="customcss" name="customcss" style="width:450px;height:300px;"></textarea>
			</div>
			<div class="ckinterfacetab" id="tab_presets" data-group="main">
				<?php echo CarouselckHelper::getProMessage() ?>
			</div>
		</div>
	</div>
	<div id="stylescontainerright">
		<div id="previewarea">
			<div class="ckstyle"></div>

			<div class="carouselck carouselck_wrap carouselck_amber_skin carousel_wrap" id="carouselckdemo1" style="width: 494px; height: 197.6px;">
				<div class="carouselck_src">

				</div>
				<div class="carouselck_images paused">
					<img style="position: absolute; height: 197.75px; width: 318.807px; left: 87.2207px; top: 0px; z-index: 1000; opacity: 1;" src="/media/com_carouselck/images/slides/bridge.jpg" class="carouselckcurrentimg" index="1" naturalindex="0" width="424" height="263">
					
					<img style="position: absolute; height: 142.11px; width: 229.33px; left: 259.406px; top: 27.0685px; z-index: 990; opacity: 1;" src="/media/com_carouselck/images/slides/road.jpg" index="2" naturalindex="1" width="305" height="189">
					
					<img style="position: absolute; height: 142.11px; width: 229.33px; left: 4.51142px; top: 27.0685px; z-index: 989; opacity: 1;" src="/media/com_carouselck/images/slides2/sea.jpg" index="0" naturalindex="2" width="305" height="189">
					
				</div>
				<div class="carouselck_thumbs_cont"></div>
				<div class="carouselck_captions" style="position: absolute; height: 197.75px; width: 318.807px; left: 87.2207px; top: 0px;">
					<div class="carouselck_caption moveFromLeft" style="display: block; left: 0px; right: auto; visibility: visible;">
						<div style="font-size: 12px;">
												<div class="carouselck_caption_title">
								This is a bridge					</div>
																	<div class="carouselck_caption_desc">
								This bridge is very long					
																	</div>
							<a class="carouselck-button" href="#">Read more ...</a>
					</div>
					</div>
				</div>
				<div class="carouselck_videos" style="position: absolute; z-index: 1000; height: 197.75px; width: 318.807px; left: 87.2207px; top: 0px; display: block;"></div>
				<div class="carouselck_pag">
					<div class="carouselck_pag_ul">
						<div class="carouselck_pag_nav pag_nav_0 carouselckcurrent" style="position:relative; z-index:1002" index="0"><span><span>0</span></span><img src="/media/com_carouselck/images/slides/th/bridge_th.jpg" class="carouselck_thumb" style="position: absolute; opacity: 1;display:block;margin-top: -70px;margin-left: -50px;"><div class="thumb_arrow" style="opacity: 1;"></div></div>
						<div class="carouselck_pag_nav pag_nav_1" style="position:relative; z-index:1002" index="1"><span><span>1</span></span><img src="/media/com_carouselck/images/slides/th/road_th.jpg" class="carouselck_thumb" style="position: absolute; opacity: 0;"><div class="thumb_arrow" style="opacity: 0;"></div></div>
						<div class="carouselck_pag_nav pag_nav_2" style="position:relative; z-index:1002" index="2"><span><span>2</span></span><img src="/media/com_carouselck/images/slides2/th/sea_th.jpg" class="carouselck_thumb" style="position: absolute; opacity: 0;"><div class="thumb_arrow" style="opacity: 0;"></div></div>
					</div>
				</div>
				<div class="carouselck_commands" style="display: none;">
					<div class="carouselck_play" style="display: none;"></div>
					<div class="carouselck_stop" style="display: none;"></div>
				</div>
				<div class="carouselck_prev" style="opacity: 1;"><span></span></div>
				<div class="carouselck_next" style="opacity: 1;"><span></span></div>
			</div>
	
	<div style="clear:both;"></div>
</div>
<?php include_once ('default_importexport.php'); ?>
</div>
<script type="text/javascript">
	CAROUSELCK.CKCSSREPLACEMENT = new Object();
	<?php foreach (CarouselckHelper::getCssReplacement() as $tag => $rep) { ?>
	CAROUSELCK.CKCSSREPLACEMENT['<?php echo $tag ?>'] = '<?php echo $rep ?>';
	<?php } ?>

	jQuery(document).ready(function($){
		CKBox.initialize({});
		CKBox.assign($('a.modal'), {
			parse: 'rel'
		});
		CKApi.Tooltip('.cktip');

		// manage the tabs
		ckInitTabs();
		// launch the preview when the user do a change
		$('#styleswizard_options input,#styleswizard_options select,#styleswizard_options textarea').change(function() {
			ckPreviewStylesparams();
		});

		ckApplyStylesparams();
		ckSetFloatingOnPreview();
		ckPlayAnimationPreview();
		
		$ck('.customcssbutton').click(function() {
			$ck('#customcss').val($ck('#customcss').val() + $ck(this).attr('data-rule'));
		});
	});
</script>
