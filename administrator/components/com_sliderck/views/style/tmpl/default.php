<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

include_once(SLIDERCK_PATH . '/helpers/defines.js.php');

Sliderck\CKFramework::loadFaIconsInline();

$imagespath = SLIDERCK_MEDIA_URI .'/images/';
// $type = $this->input->get('type', 'image', 'string');
\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
$doc = \Sliderck\CKFof::getDocument();
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/admin.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckframework.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckbox.css');
// $doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckpopup.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/style.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/swiper.css');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/jscolor/jscolor.js');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/ckbox.js');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/admin.js');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/style.js');

$popupclass = ($this->input->get('layout', '', 'string') === 'modal') ? 'ckpopupwizard' : '';

// Load the JS strings
\Sliderck\CKText::script('CK_DOWNLOAD');
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
<div id="ckpopupstyleswizard" class="<?php echo $popupclass; ?>">
		<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" id="layoutcss" name="layoutcss" value="<?php echo $this->item->layoutcss; ?>" />
		<input type="hidden" id="params" name="params" value="<?php echo htmlspecialchars($this->item->params); ?>" />
		<input type="hidden" id="returnFunc" name="returnFunc" value="<?php echo htmlspecialchars($this->input->get('returnFunc', '', 'cmd')); ?>" />
	<?php if ($this->input->get('layout', '', 'string') === 'modal') {
		include 'default_mainmenu.php'; 
	} ?>
<div id="stylescontainer" style="min-width: 1300px;" class="animateck">
	<div id="stylescontainerleft" class="ckinterface">
		<label for="name" style="display: inline-block;"><?php echo \Sliderck\CKText::_('CK_NAME'); ?></label>
		<input type="text" id="name" name="name" value="<?php echo $this->item->name; ?>" />
		<div id="styleswizard_options" class="styleswizard">
			<div class="ckinterfacetablink current" data-tab="tab_main" data-group="main" ><?php echo \Sliderck\CKText::_('CK_SLIDER'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_slide" data-group="main"><?php echo \Sliderck\CKText::_('CK_SLIDE'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_caption" data-group="main"><?php echo \Sliderck\CKText::_('CK_CAPTION'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_title" data-group="main"><?php echo \Sliderck\CKText::_('CK_TITLE'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_description" data-group="main"><?php echo \Sliderck\CKText::_('CK_DESCRIPTION'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_button" data-group="main"><?php echo \Sliderck\CKText::_('CK_BUTTON'); ?></div>
			<div class="ckinterfacetablink" data-tab="tab_custom" data-group="main"><?php echo \Sliderck\CKText::_('CK_CUSTOM_CSS'); ?></div>
			<div class="ckclr"></div>
			<div class="ckinterfacetab current hascol" id="tab_main" data-group="main">
				<div class="ckcol_left">
					<div class="ckinterfacetablink current" data-tab="tab_mainslider" data-group="container"><?php echo \Sliderck\CKText::_('CK_SLIDER'); ?></div>
					<div class="ckinterfacetablink" data-tab="tab_mainpagination" data-group="container"><?php echo \Sliderck\CKText::_('CK_PAGINATION'); ?></div>
				</div>
				<div class="ckcol_right">
					<div class="ckinterfacetab current menustylescustom" data-group="container" id="tab_mainslider" data-prefix="slider" data-rule="">
						<?php
						echo $this->interface->createMargins('slider');
						echo $this->interface->createBorders('slider');
						echo $this->interface->createShadow('slider');
						?>
					</div>
					<div class="ckinterfacetab" id="tab_mainpagination" data-group="container">
						<div class="ckheading"><?php echo \Sliderck\CKText::_('CK_PAGINATION_LABEL'); ?></div>
						<div class="ckrow">
							<label for="slidearrowcolor"><?php echo \Sliderck\CKText::_('CK_ARROW_COLOR_LABEL'); ?></label>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
							<input type="text" id="slidearrowcolor" name="slidearrowcolor" class="hasTip slide <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo \Sliderck\CKText::_('CK_COLOR_DESC'); ?>"/>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/layers.png" />
							<input type="text" id="slidearrowopacity" name="slidearrowopacity" class="hasTip slide" style="width:30px;" title="<?php echo \Sliderck\CKText::_('CK_OPACITY_DESC'); ?>"/>
						</div>
						<div class="ckrow">
							<label for="slidearrowhovercolor"><?php echo \Sliderck\CKText::_('CK_ARROW_HOVER_COLOR_LABEL'); ?></label>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
							<input type="text" id="slidearrowhovercolor" name="slidearrowhovercolor" class="hasTip slide <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo \Sliderck\CKText::_('CK_COLOR_DESC'); ?>"/>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/layers.png" />
							<input type="text" id="slidearrowhoveropacity" name="slidearrowhoveropacity" class="hasTip slide" style="width:30px;" title="<?php echo \Sliderck\CKText::_('CK_OPACITY_DESC'); ?>"/>
						</div>
						<div class="ckrow">
							<label for="slidepaginationcolor"><?php echo \Sliderck\CKText::_('CK_PAGINATION_COLOR_LABEL'); ?></label>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
							<input type="text" id="slidepaginationcolor" name="slidepaginationcolor" class="hasTip slide <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo \Sliderck\CKText::_('CK_COLOR_DESC'); ?>"/>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/layers.png" />
							<input type="text" id="slidepaginationopacity" name="slidepaginationopacity" class="hasTip slide" style="width:30px;" title="<?php echo \Sliderck\CKText::_('CK_OPACITY_DESC'); ?>"/>
						</div>
						<div class="ckrow">
							<label for="slidepaginationactivecolor"><?php echo \Sliderck\CKText::_('CK_PAGINATION_ACTIVE_COLOR_LABEL'); ?></label>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/color.png" />
							<input type="text" id="slidepaginationactivecolor" name="slidepaginationactivecolor" class="hasTip slide <?php echo $this->interface->colorpicker_class; ?>" title="<?php echo \Sliderck\CKText::_('CK_COLOR_DESC'); ?>"/>
							<img class="ckicon" src="<?php echo $this->interface->imagespath ?>/layers.png" />
							<input type="text" id="slidepaginationactiveopacity" name="slidepaginationactiveopacity" class="hasTip slide" style="width:30px;" title="<?php echo \Sliderck\CKText::_('CK_OPACITY_DESC'); ?>"/>
						</div>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="ckinterfacetab hascol" id="tab_slide" data-group="main">
				<div class="ckcol_left">
					<div class="ckinterfacetablink current" data-tab="tab_slidenormal" data-group="slide"><?php echo \Sliderck\CKText::_('CK_NORMAL_STATE'); ?></div>
					<div class="ckinterfacetablink" data-tab="tab_slidehover" data-group="slide"><?php echo \Sliderck\CKText::_('CK_HOVER_STATE'); ?></div>
					<div class="ckinterfacetablink" data-tab="tab_slideactive" data-group="slide"><?php echo \Sliderck\CKText::_('CK_ACTIVE_STATE'); ?></div>
					<div class="ckinterfacetablink" data-tab="tab_slideeffects" data-group="slide"><?php echo \Sliderck\CKText::_('CK_EFFECTS'); ?></div>
				</div>
				<div class="ckcol_right">
					<div class="ckinterfacetab current menustylescustom" id="tab_slidenormal" data-group="slide" data-prefix="slide" data-rule=".swiper-slide">
						<?php
						echo $this->interface->createBorders('slide');
						echo $this->interface->createRoundedCorners('slide');
						echo $this->interface->createShadow('slide');
						?>
					</div>
					<div class="ckinterfacetab menustylescustom" id="tab_slidehover" data-group="slide" data-prefix="slidehover" data-rule=".swiper-slide:hover">
						<?php echo SliderckHelper::getProMessage() ?>
					</div>
					<div class="ckinterfacetab menustylescustom" id="tab_slideactive" data-group="slide" data-prefix="slideactive" data-rule=".swiper-slide.swiper-slide-active">
						<?php echo SliderckHelper::getProMessage() ?>
					</div>
					<div class="ckinterfacetab" id="tab_slideeffects" data-group="slide">
						<?php echo SliderckHelper::getProMessage() ?>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="ckinterfacetab menustylescustom" data-group="main" id="tab_caption" data-prefix="caption" data-rule=".swiper-caption">
				<?php echo $this->interface->createAll('caption'); ?>
			</div>
			<div class="ckinterfacetab menustylescustom" data-group="main" id="tab_title" data-prefix="title" data-rule=".swiper-title">
				<?php echo SliderckHelper::getProMessage() ?>
			</div>
			<div class="ckinterfacetab menustylescustom" data-group="main" id="tab_description" data-prefix="description" data-rule=".swiper-desc">
				<?php echo SliderckHelper::getProMessage() ?>
			</div>
			<div class="ckinterfacetab menustylescustom" data-group="main" id="tab_button" data-prefix="button" data-rule=".swiper-button">
				<?php echo SliderckHelper::getProMessage() ?>
			</div>
			<div class="ckinterfacetab" id="tab_custom" data-group="main">
				<textarea id="customcss" name="customcss" style="width:450px;height:300px;"></textarea>
			</div>
		</div>
	</div>
	<div id="stylescontainerright">
		<div id="previewarea">
			<div class="ckstyle"></div>
			<h3><?php echo \Sliderck\CKText::_('CK_NORMAL_STATE'); ?></h3>
			<div id="sliderckdemo1" class="swiper-container swiper-container-horizontal swiper-layout-over" dir="ltr" data-effect="slide">
				<div class="swiper-wrapper">
					<div class="swiper-slide" style="width: 444px;height: 250px;box-sizing: initial;">
						<div style="height:100%;width:100%;background-image:url('../media/com_sliderck/images/slides/slide1.jpg')">
							<img class="swiper-img" src="<?php echo \Sliderck\CKUri::root(true) ?>/media/com_sliderck/images/slides/slide1.jpg">
						</div>
						<div class="swiper-caption">
							<div class="swiper-title">John Doe</div>
							<div class="swiper-desc">My name is John and I like eggs.</div>
							<a href="#" class=" btn swiper-button">Read more</a>
						</div>
					</div>
				</div>
				<div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets">
					<span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 1"></span>
					<span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span>
					<span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 3"></span>
				</div>
				<div class="swiper-button-next" aria-disabled="false" tabindex="0" role="button" aria-label="Next slide"></div>
				<div class="swiper-button-prev" aria-disabled="true" tabindex="0" role="button" aria-label="Previous slide"></div>
			</div>
			<h3><?php echo \Sliderck\CKText::_('CK_ACTIVE_STATE'); ?></h3>
			<div id="sliderckdemo2" class="swiper-container swiper-container-horizontal swiper-layout-over" dir="ltr" data-effect="slide">
				<div class="swiper-wrapper">
					<div class="swiper-slide swiper-slide-active" style="width: 444px;height: 250px;box-sizing: initial;">
						<div style="height:100%;width:100%;background-image:url('../media/com_sliderck/images/slides/slide1.jpg')">
							<img class="swiper-img" src="<?php echo \Sliderck\CKUri::root(true) ?>/media/com_sliderck/images/slides/slide1.jpg">
						</div>
						<div class="swiper-caption">
							<div class="swiper-title">John Doe</div>
							<div class="swiper-desc">My name is John and I like eggs.</div>
							<a href="#" class=" btn swiper-button">Read more</a>
						</div>
					</div>
				</div>
				<div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets">
					<span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 1"></span>
					<span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span>
					<span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 3"></span>
				</div>
				<div class="swiper-button-next" aria-disabled="false" tabindex="0" role="button" aria-label="Next slide"></div>
				<div class="swiper-button-prev" aria-disabled="true" tabindex="0" role="button" aria-label="Previous slide"></div>
			</div>
		</div>
	</div>
	<div style="clear:both;"></div>
</div>
<?php include 'default_importexport.php'; ?>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		CKBox.initialize({});
		CKBox.assign($('a.modal'), {
			parse: 'rel'
		});

		// manage the tabs
		ckInitTabs();

		// launch the preview when the user do a change
		$('#styleswizard_options input,#styleswizard_options select,#styleswizard_options textarea').change(function() {
			ckPreviewStylesparams();
		});
		// jQuery('.hasTip').tooltip({"html": true,"container": "body"});
		ckApplyStylesparams();
		ckSetFloatingOnPreview();
	});

	var JoomlaCK = {};
	JoomlaCK.submitbutton = Joomla.submitbutton;
	Joomla.submitbutton = function(task) {
		ckSaveStylesparams();
	}
</script>
