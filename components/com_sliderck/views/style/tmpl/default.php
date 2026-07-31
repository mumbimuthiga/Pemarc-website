<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

defined('_JEXEC') or die;

$input = \Sliderck\CKFof::getApplication()->input;
$imagespath = SLIDERCK_MEDIA_URI .'/images/';
// $type = $input->get('type', 'image', 'string');
\Joomla\CMS\HTML\HTMLHelper::_('jquery.framework');
$doc = \Sliderck\CKFof::getDocument();
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckframework.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckbox.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/ckpopup.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/sliderck.css');
$doc->addStylesheet(SLIDERCK_MEDIA_URI . '/assets/swiper.css');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/jscolor/jscolor.js');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/ckbox.js');
$doc->addScript(SLIDERCK_MEDIA_URI . '/assets/sliderck.js');

$popupclass = ($input->get('layout', '', 'string') === 'modal') ? 'ckpopupwizard' : '';

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
</style>
<div id="ckpopupstyleswizard" class="<?php echo $popupclass; ?>">
		<input type="hidden" id="id" name="id" value="<?php echo $this->item->id; ?>" />
		<input type="hidden" id="layoutcss" name="layoutcss" value="<?php echo $this->item->layoutcss; ?>" />
		<input type="hidden" id="params" name="params" value="<?php echo htmlspecialchars($this->item->params); ?>" />
		<input type="hidden" id="returnFunc" name="returnFunc" value="<?php echo htmlspecialchars($input->get('returnFunc', '', 'cmd')); ?>" />
	<?php if ($input->get('layout', '', 'string') === 'modal') {
		echo $this->loadTemplate('mainmenu'); 
	} ?>
<div id="stylescontainer" style="min-width: 1300px;" class="animateck">
	<div id="stylescontainerleft" class="ckinterface">
		<label for="name" style="display: inline-block;"><?php echo \Sliderck\CKText::_('CK_NAME'); ?></label>
		<input type="text" id="name" name="name" value="<?php echo $this->item->name; ?>" />
		<div id="styleswizard_options" class="styleswizard">
			<div class="ckmenulink current" tab="tab_main"><?php echo \Sliderck\CKText::_('CK_SLIDER'); ?></div>
			<div class="ckmenulink" tab="tab_slide"><?php echo \Sliderck\CKText::_('CK_SLIDE'); ?></div>
			<div class="ckmenulink" tab="tab_caption"><?php echo \Sliderck\CKText::_('CK_CAPTION'); ?></div>
			<div class="ckmenulink" tab="tab_title"><?php echo \Sliderck\CKText::_('CK_TITLE'); ?></div>
			<div class="ckmenulink" tab="tab_description"><?php echo \Sliderck\CKText::_('CK_DESCRIPTION'); ?></div>
			<div class="ckmenulink" tab="tab_button"><?php echo \Sliderck\CKText::_('CK_BUTTON'); ?></div>
			<div class="ckmenulink" tab="tab_custom"><?php echo \Sliderck\CKText::_('CK_CUSTOM_CSS'); ?></div>
			<div class="clr"></div>
			<div class="cktab current hascol" id="tab_main">
				<div class="ckcol_left">
					<div class="ckmenulink2 current" tab="tab_mainslider"><?php echo \Sliderck\CKText::_('CK_SLIDER'); ?></div>
					<div class="ckmenulink2" tab="tab_mainpagination"><?php echo \Sliderck\CKText::_('CK_PAGINATION'); ?></div>
				</div>
				<div class="ckcol_right">
					<div class="cktab2 current menustylescustom" id="tab_mainslider" data-prefix="slider" data-rule=".swiper-container">
						<?php
						echo $this->interface->createMargins('slider');
						echo $this->interface->createBorders('slider');
						echo $this->interface->createShadow('slider');
						?>
					</div>
					<div class="cktab2" id="tab_mainpagination">
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
			<div class="cktab hascol" id="tab_slide">
				<div class="ckcol_left">
					<div class="ckmenulink2 current" tab="tab_slidenormal"><?php echo \Sliderck\CKText::_('CK_NORMAL_STATE'); ?></div>
					<div class="ckmenulink2" tab="tab_slidehover"><?php echo \Sliderck\CKText::_('CK_HOVER_STATE'); ?></div>
					<div class="ckmenulink2" tab="tab_slideactive"><?php echo \Sliderck\CKText::_('CK_ACTIVE_STATE'); ?></div>
					<div class="ckmenulink2" tab="tab_slideeffects"><?php echo \Sliderck\CKText::_('CK_EFFECTS'); ?></div>
				</div>
				<div class="ckcol_right">
					<div class="cktab2 current menustylescustom" id="tab_slidenormal" data-prefix="slide" data-rule=".swiper-slide">
						<?php
						echo $this->interface->createBorders('slide');
						echo $this->interface->createRoundedCorners('slide');
						echo $this->interface->createShadow('slide');
						?>
					</div>
					<div class="cktab2 menustylescustom" id="tab_slidehover" data-prefix="slidehover" data-rule=".swiper-slide:hover">
						<?php
						echo $this->interface->createBorders('slidehover');
						echo $this->interface->createRoundedCorners('slidehover');
						echo $this->interface->createShadow('slidehover');
						?>
					</div>
					<div class="cktab2 menustylescustom" id="tab_slideactive" data-prefix="slideactive" data-rule=".swiper-slide.swiper-slide-active">
						<?php
						echo $this->interface->createBorders('slideactive');
						echo $this->interface->createRoundedCorners('slideactive');
						echo $this->interface->createShadow('slideactive');
						?>
					</div>
					<div class="cktab2" id="tab_slideeffects">
						<?php
						echo $this->interface->createEffects('slide');
						?>
					</div>
				</div>
				<div style="clear:both;"></div>
			</div>
			<div class="cktab menustylescustom" id="tab_caption" data-prefix="caption" data-rule=".swiper-caption">
				<?php echo $this->interface->createAll('caption'); ?>
			</div>
			<div class="cktab menustylescustom" id="tab_title" data-prefix="title" data-rule=".swiper-title">
				<?php echo $this->interface->createAll('title'); ?>
			</div>
			<div class="cktab menustylescustom" id="tab_description" data-prefix="description" data-rule=".swiper-desc">
				<?php echo $this->interface->createAll('description'); ?>
			</div>
			<div class="cktab menustylescustom" id="tab_button" data-prefix="button" data-rule=".swiper-button">
				<?php echo $this->interface->createAll('button'); ?>
			</div>
			<div class="cktab" id="tab_custom">
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
<?php echo $this->loadTemplate('importexport'); ?>
</div>
<script type="text/javascript">
	var URIROOT = "<?php echo \Sliderck\CKUri::root(true); ?>";
	var URIBASE = "<?php echo \Sliderck\CKUri::base(true); ?>";
	var cktoken = '<?php echo \Joomla\CMS\Factory::getSession()->getFormToken() ?>';

	jQuery(document).ready(function($){
		CKBox.initialize({});
		CKBox.assign($('a.modal'), {
			parse: 'rel'
		});

		// manage the tabs
		function ckInitTabsStyles() {
			$('#styleswizard_options div.cktab:not(.current)').hide();
			$('.ckmenulink', $('#styleswizard_options')).each(function(i, tab) {
				$(tab).click(function() {
					$('#styleswizard_options div.cktab').hide();
					$('.ckmenulink', $('#styleswizard_options')).removeClass('current');
					if ($('#' + $(tab).attr('tab')).length)
						$('#' + $(tab).attr('tab')).show();
					$ck(this).addClass('current');
				});
			});
			// sub tabs
			$ck('#styleswizard_options div.cktab2:not(.current)').hide();
			$ck('.ckmenulink2', $ck('#styleswizard_options')).each(function(i, tab) {
				$ck(tab).click(function() {
					var parent_cont = $ck(tab).parents('.cktab')[0];
					$ck('.cktab2', parent_cont).hide();
					$ck('.ckmenulink2', parent_cont).removeClass('current');
					if ($ck('#' + $ck(tab).attr('tab')).length)
						$ck('#' + $ck(tab).attr('tab')).show();
					$ck(this).addClass('current');
				});
			});
		}
		ckInitTabsStyles();

		// launch the preview when the user do a change
		$('#styleswizard_options input,#styleswizard_options select,#styleswizard_options textarea').change(function() {
			ckPreviewStylesparams();
		});
		jQuery('.hasTip').tooltip({"html": true,"container": "body"});
		ckApplyStylesparams();
		ckSetFloatingOnPreview();
	});

	var JoomlaCK = {};
	JoomlaCK.submitbutton = Joomla.submitbutton;
	Joomla.submitbutton = function(task) {
		ckSaveStylesparams();
	}
</script>
