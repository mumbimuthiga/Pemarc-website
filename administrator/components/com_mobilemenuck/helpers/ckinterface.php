<?php
/**
 * @copyright	Copyright (C) 2017. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class CKInterface extends \Mobilemenuck\CKObject {

	public $imagespath;
	
	public $colorpicker_class = 'color {required:false,pickerPosition:\'top\',pickerBorder:2,pickerInset:3,hash:true}';

	public function __construct($properties = null) {
		$this->imagespath = \Mobilemenuck\CKUri::root(true) . '/media/com_mobilemenuck/images';
	}

	public function createAll($prefix, $textshadow = false) {
		?>
		<div class="ckheading"><?php echo \Mobilemenuck\CKText::_('CK_TEXT_LABEL'); ?></div>
		<?php
		$this->createText($prefix);
		if ($textshadow) $this->createTextShadow($prefix);
		?>
		<div class="ckheading"><?php echo \Mobilemenuck\CKText::_('CK_APPEARANCE_LABEL'); ?></div>
		<?php
		$this->createBackgroundColor($prefix);
		$this->createBackgroundImage($prefix);
		$this->createBorders($prefix);
		$this->createRoundedCorners($prefix);
		$this->createShadow($prefix);
		?>
		<div class="ckheading"><?php echo \Mobilemenuck\CKText::_('CK_DIMENSIONS_LABEL'); ?></div>
		<?php
		$this->createMargins($prefix);
		$this->createDimensions($prefix);
		/*
		?>
		<div class="ckheading"><?php echo \Mobilemenuck\CKText::_('CK_ANIMATIONS_LABEL'); ?></div>
		<?php
	$this->createAnimations($prefix);
		 */
	}

	public function createBackgroundColor($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>bgcolor1"><?php echo \Mobilemenuck\CKText::_('CK_BGCOLOR_LABEL'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<input type="text" id="<?php echo $prefix; ?>bgcolor1" name="<?php echo $prefix; ?>bgcolor1" class="cktip <?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo \Mobilemenuck\CKText::_('CK_BGCOLOR_DESC'); ?>"/>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<input type="text" id="<?php echo $prefix; ?>bgcolor2" name="<?php echo $prefix; ?>bgcolor2" class="cktip <?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" title="<?php echo \Mobilemenuck\CKText::_('CK_BGCOLOR2_DESC'); ?>" onchange="ckCheckGradientImageConflict(this, '<?php echo $prefix; ?>bgimage')"/>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/layers.png" />
		<input type="text" id="<?php echo $prefix; ?>bgopacity" name="<?php echo $prefix; ?>bgopacity" class="cktip <?php echo $prefix; ?>" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_BGOPACITY_DESC'); ?>"/>
	</div>
	<?php
	}
	
	public function createBackgroundImage($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>bgimage"><?php echo \Mobilemenuck\CKText::_('CK_BACKGROUNDIMAGE_LABEL'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/image.png" />
		<div class="ckbutton-group">
			<input type="text" id="<?php echo $prefix; ?>bgimage" name="<?php echo $prefix; ?>bgimage" class="cktip <?php echo $prefix; ?>" title="<?php echo \Mobilemenuck\CKText::_('CK_BACKGROUNDIMAGE_DESC'); ?>" onchange="ckCheckGradientImageConflict(this, '<?php echo $prefix; ?>bgcolor2')" style="max-width: none; width: 150px;"/>
			<a class="ckmodal ckbutton" href="<?php echo \Mobilemenuck\CKUri::base(true) ?>/index.php?option=com_mobilemenuck&view=browse&tmpl=component&field=<?php echo $prefix; ?>bgimage" rel="{handler: 'iframe'}" ><?php echo \Mobilemenuck\CKText::_('CK_SELECT'); ?></a>
			<a class="ckbutton" href="javascript:void(0)" onclick="$ck(this).parent().find('input').val('');"><?php echo \Mobilemenuck\CKText::_('CK_CLEAR'); ?></a>
		</div>
	</div>
	<div class="ckrow">
		<label></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>bgpositionx" name="<?php echo $prefix; ?>bgpositionx" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_BACKGROUNDPOSITIONX_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>bgpositiony" name="<?php echo $prefix; ?>bgpositiony" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_BACKGROUNDPOSITIONY_DESC'); ?>" /></span>
		<div class="ckbutton-group">
			<input class="" type="radio" value="repeat" id="<?php echo $prefix; ?>bgimagerepeatrepeat" name="<?php echo $prefix; ?>bgimagerepeat" class="<?php echo $prefix; ?>" />
			<label class="ckbutton first" for="<?php echo $prefix; ?>bgimagerepeatrepeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="repeat-x" id="<?php echo $prefix; ?>bgimagerepeatrepeat-x" name="<?php echo $prefix; ?>bgimagerepeat" />
			<label class="ckbutton"  for="<?php echo $prefix; ?>bgimagerepeatrepeat-x"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-x.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="repeat-y" id="<?php echo $prefix; ?>bgimagerepeatrepeat-y" name="<?php echo $prefix; ?>bgimagerepeat" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>bgimagerepeatrepeat-y"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_repeat-y.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="no-repeat" id="<?php echo $prefix; ?>bgimagerepeatno-repeat" name="<?php echo $prefix; ?>bgimagerepeat" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>bgimagerepeatno-repeat"><img class="ckicon" src="<?php echo $this->imagespath ?>/bg_no-repeat.png" /></label>
		</div>
	</div>
	<?php
	}
	public function createRoundedCorners($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>roundedcornerstl"><?php echo \Mobilemenuck\CKText::_('CK_ROUNDEDCORNERS_LABEL'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tl.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>roundedcornerstl" name="<?php echo $prefix; ?>roundedcornerstl" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_ROUNDEDCORNERSTL_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_tr.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>roundedcornerstr" name="<?php echo $prefix; ?>roundedcornerstr" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_ROUNDEDCORNERSTR_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_br.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>roundedcornersbr" name="<?php echo $prefix; ?>roundedcornersbr" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_ROUNDEDCORNERSBR_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/border_radius_bl.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>roundedcornersbl" name="<?php echo $prefix; ?>roundedcornersbl" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_ROUNDEDCORNERSBL_DESC'); ?>" /></span>
	</div>
	<?php
	}
	public function createShadow($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>shadowcolor"><?php echo \Mobilemenuck\CKText::_('CK_SHADOW_LABEL'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>shadowcolor" name="<?php echo $prefix; ?>shadowcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_blur.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>shadowblur" name="<?php echo $prefix; ?>shadowblur" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_SHADOWBLUR_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_spread.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>shadowspread" name="<?php echo $prefix; ?>shadowspread" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_SHADOWSPREAD_DESC'); ?>" /></span>
	</div>
	<div class="ckrow">
		<label></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>shadowoffsetx" name="<?php echo $prefix; ?>shadowoffsetx" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_OFFSETX_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>shadowoffsety" name="<?php echo $prefix; ?>shadowoffsety" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_OFFSETY_DESC'); ?>" /></span>
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="0" id="<?php echo $prefix; ?>shadowinsetno" name="<?php echo $prefix; ?>shadowinset" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>shadowinsetno" style="width:auto;"><?php echo \Mobilemenuck\CKText::_('CK_OUT'); ?>
			</label><input class="<?php echo $prefix; ?>" type="radio" value="1" id="<?php echo $prefix; ?>shadowinsetyes" name="<?php echo $prefix; ?>shadowinset" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>shadowinsetyes" style="width:auto;"><?php echo \Mobilemenuck\CKText::_('CK_IN'); ?></label>
		</div>
	</div>
	<?php
	}
	public function createTextShadow($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>textshadowcolor"><?php echo \Mobilemenuck\CKText::_('CK_TEXTSHADOW_LABEL'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>textshadowcolor" name="<?php echo $prefix; ?>textshadowcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/shadow_blur.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>textshadowblur" name="<?php echo $prefix; ?>textshadowblur" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_SHADOWBLUR_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsetx.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>textshadowoffsetx" name="<?php echo $prefix; ?>textshadowoffsetx" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_OFFSETX_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/offsety.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>textshadowoffsety" name="<?php echo $prefix; ?>textshadowoffsety" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_OFFSETY_DESC'); ?>" /></span>
	</div>
	<?php
	}

	public function createDimensions($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>width"><?php echo \Mobilemenuck\CKText::_('CK_WIDTH_LABEL'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/width.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>width" name="<?php echo $prefix; ?>width" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_WIDTH_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/height.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>height" name="<?php echo $prefix; ?>height" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_HEIGHT_DESC'); ?>" /></span>
	</div>
	<?php
	}

	public function createMargins($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>margintop"><?php echo \Mobilemenuck\CKText::_('CK_MARGIN_LABEL'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_top.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>margintop" name="<?php echo $prefix; ?>margintop" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_MARGINTOP_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_right.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>marginright" name="<?php echo $prefix; ?>marginright" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_MARGINRIGHT_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_bottom.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>marginbottom" name="<?php echo $prefix; ?>marginbottom" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_MARGINBOTTOM_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/margin_left.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>marginleft" name="<?php echo $prefix; ?>marginleft" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_MARGINLEFT_DESC'); ?>" /></span>
	</div>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>paddingtop"><?php echo \Mobilemenuck\CKText::_('CK_PADDING_LABEL'); ?></label>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_top.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>paddingtop" name="<?php echo $prefix; ?>paddingtop" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_PADDINGTOP_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_right.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>paddingright" name="<?php echo $prefix; ?>paddingright" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_PADDINGRIGHT_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_bottom.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>paddingbottom" name="<?php echo $prefix; ?>paddingbottom" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_PADDINGBOTTOM_DESC'); ?>" /></span>
		<span><img class="ckicon" src="<?php echo $this->imagespath ?>/padding_left.png" /></span><span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>paddingleft" name="<?php echo $prefix; ?>paddingleft" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_PADDINGLEFT_DESC'); ?>" /></span>
	</div>
	<?php
	}

	public function createBorders($prefix) {
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>bordertopcolor"><?php echo \Mobilemenuck\CKText::_('CK_BORDERCOLOR_LABEL'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>bordertopcolor" name="<?php echo $prefix; ?>bordertopcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?> cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERCOLOR_DESC'); ?>"/></span>
		<span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>bordertopwidth" name="<?php echo $prefix; ?>bordertopwidth" class="<?php echo $prefix; ?> cktip" style="width:30px;border-top-color:#237CA4;" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERTOPWIDTH_DESC'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>bordertopstyle" name="<?php echo $prefix; ?>bordertopstyle" class="<?php echo $prefix; ?> cktip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>borderrightcolor" name="<?php echo $prefix; ?>borderrightcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?> cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERCOLOR_DESC'); ?>"/></span>
		<span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>borderrightwidth" name="<?php echo $prefix; ?>borderrightwidth" class="<?php echo $prefix; ?> cktip" style="width:30px;border-right-color:#237CA4;" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERRIGHTWIDTH_DESC'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>borderrightstyle" name="<?php echo $prefix; ?>borderrightstyle" class="<?php echo $prefix; ?> cktip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>borderbottomcolor" name="<?php echo $prefix; ?>borderbottomcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?> cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERCOLOR_DESC'); ?>"/></span>
		<span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>borderbottomwidth" name="<?php echo $prefix; ?>borderbottomwidth" class="<?php echo $prefix; ?> cktip" style="width:30px;border-bottom-color:#237CA4;" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERBOTTOMWIDTH_DESC'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>borderbottomstyle" name="<?php echo $prefix; ?>borderbottomstyle" class="<?php echo $prefix; ?> cktip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><input type="text" id="<?php echo $prefix; ?>borderleftcolor" name="<?php echo $prefix; ?>borderleftcolor" class="<?php echo $prefix; ?> <?php echo $this->colorpicker_class; ?> cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERCOLOR_DESC'); ?>"/></span>
		<span style="width:30px;"><input type="text" id="<?php echo $prefix; ?>borderleftwidth" name="<?php echo $prefix; ?>borderleftwidth" class="<?php echo $prefix; ?> cktip" style="width:30px;border-left-color:#237CA4;" title="<?php echo \Mobilemenuck\CKText::_('CK_BORDERLEFTWIDTH_DESC'); ?>" /></span>
		<span>
			<select id="<?php echo $prefix; ?>borderleftstyle" name="<?php echo $prefix; ?>borderleftstyle" class="<?php echo $prefix; ?> cktip" style="width: 70px; border-radius: 0px;">
				<option value="solid">solid</option>
				<option value="dotted">dotted</option>
				<option value="dashed">dashed</option>
			</select>
		</span>
	</div>
	<?php
	}

	public function createText($prefix) { 
	?>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>textgfont"><?php echo \Mobilemenuck\CKText::_('CK_FONTSTYLE_LABEL'); ?></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/font_add.png" />
		<input type="text" id="<?php echo $prefix; ?>textgfont" name="<?php echo $prefix; ?>textgfont" class="<?php echo $prefix; ?> cktip gfonturl" onchange="ckCleanGfontName(this);" title="<?php echo \Mobilemenuck\CKText::_('CK_GFONT_DESC'); ?>" style="max-width:none;width:250px;" />
		<input type="hidden" id="<?php echo $prefix; ?>textisgfont" name="<?php echo $prefix; ?>textisgfont" class="isgfont <?php echo $prefix; ?>" />
	</div>
	<div class="ckrow">
		<label></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/style.png" />
		<input type="text" id="<?php echo $prefix; ?>fontsize" name="<?php echo $prefix; ?>fontsize" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_FONTSIZE_DESC'); ?>" />
		<img class="ckicon" src="<?php echo $this->imagespath ?>/color.png" />
		<span><?php echo \Mobilemenuck\CKText::_('CK_NORMAL'); ?></span>
		<input type="text" id="<?php echo $prefix; ?>fontcolor" name="<?php echo $prefix; ?>fontcolor" class="<?php echo $prefix; ?> cktip <?php echo $this->colorpicker_class; ?>" title="<?php echo \Mobilemenuck\CKText::_('CK_FONTCOLOR_DESC'); ?>" />
	</div>
	<div class="ckrow">
		<label for="">&nbsp;</label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/font.png" />
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="left" id="<?php echo $prefix; ?>textalignleft" name="<?php echo $prefix; ?>textalign" />
			<label class="ckbutton first" for="<?php echo $prefix; ?>textalignleft"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_left.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="center" id="<?php echo $prefix; ?>textaligncenter" name="<?php echo $prefix; ?>textalign" />
			<label class="ckbutton"  for="<?php echo $prefix; ?>textaligncenter"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_center.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="right" id="<?php echo $prefix; ?>textalignright" name="<?php echo $prefix; ?>textalign" />
			<label class="ckbutton last"  for="<?php echo $prefix; ?>textalignright"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_align_right.png" /></label>
		</div>
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="lowercase" id="<?php echo $prefix; ?>texttransformlowercase" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton first cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_LOWERCASE'); ?>" for="<?php echo $prefix; ?>texttransformlowercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_lowercase.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="uppercase" id="<?php echo $prefix; ?>texttransformuppercase" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_UPPERCASE'); ?>" for="<?php echo $prefix; ?>texttransformuppercase"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_uppercase.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="capitalize" id="<?php echo $prefix; ?>texttransformcapitalize" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_CAPITALIZE'); ?>" for="<?php echo $prefix; ?>texttransformcapitalize"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_capitalize.png" />
			</label><input class="<?php echo $prefix; ?>" type="radio" value="default" id="<?php echo $prefix; ?>texttransformdefault" name="<?php echo $prefix; ?>texttransform" />
			<label class="ckbutton cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_DEFAULT'); ?>" for="<?php echo $prefix; ?>texttransformdefault"><img class="ckicon" src="<?php echo $this->imagespath ?>/text_default.png" />
			</label>
		</div>
	</div>
	<div class="ckrow">
		<label for="<?php echo $prefix; ?>fontweightbold"></label>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/text_bold.png" />
		<div class="ckbutton-group">
			<input class="<?php echo $prefix; ?>" type="radio" value="bold" id="<?php echo $prefix; ?>fontweightbold" name="<?php echo $prefix; ?>fontweight" />
			<label class="ckbutton first cktip" title="" for="<?php echo $prefix; ?>fontweightbold" style="width:auto;"><?php echo \Mobilemenuck\CKText::_('CK_BOLD'); ?>
			</label><input class="<?php echo $prefix; ?>" type="radio" value="normal" id="<?php echo $prefix; ?>fontweightnormal" name="<?php echo $prefix; ?>fontweight" />
			<label class="ckbutton cktip" title="" for="<?php echo $prefix; ?>fontweightnormal" style="width:auto;"><?php echo \Mobilemenuck\CKText::_('CK_NORMAL'); ?>
			</label>
		</div>
		<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_align_middle.png" />
		<input type="text" id="<?php echo $prefix; ?>lineheight" name="<?php echo $prefix; ?>lineheight" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_LINEHEIGHT_DESC'); ?>" />
		<img class="ckicon" src="<?php echo $this->imagespath ?>/text_padding_left.png" />
		<input type="text" id="<?php echo $prefix; ?>textindent" name="<?php echo $prefix; ?>textindent" class="<?php echo $prefix; ?> cktip" style="width:30px;" title="<?php echo \Mobilemenuck\CKText::_('CK_TEXTINDENT_DESC'); ?>" />
	</div>
	<?php
	}

	public function createAnimations($prefix) {
	?>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>fontweightbold"><?php echo \Mobilemenuck\CKText::_('CK_DURATION'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/hourglass.png" />
			<input class="<?php echo $prefix; ?>" type="text" name="<?php echo $prefix; ?>animdur" id="<?php echo $prefix; ?>animdur" value="1" /> [s]
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>fontweightbold"><?php echo \Mobilemenuck\CKText::_('CK_DELAY'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/hourglass.png" />
			<input class="<?php echo $prefix; ?>" type="text" name="<?php echo $prefix; ?>animdelay" id="<?php echo $prefix; ?>animdelay" value="0" /> [s]
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>fontweightbold"><?php echo \Mobilemenuck\CKText::_('CK_FADE'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shading.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animfade" id="<?php echo $prefix; ?>animfade" value="" style="width: 100px;" >
				<option value="0"><?php echo \Mobilemenuck\CKText::_('JNO'); ?></option>
				<option value="1"><?php echo \Mobilemenuck\CKText::_('JYES'); ?></option>
			</select>
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>fontweightbold"><?php echo \Mobilemenuck\CKText::_('CK_MOVE'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_square_go.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animmove" id="<?php echo $prefix; ?>animmove" value="" style="width: 100px;" >
				<option value="0"><?php echo \Mobilemenuck\CKText::_('JNO'); ?></option>
				<option value="1"><?php echo \Mobilemenuck\CKText::_('JYES'); ?></option>
			</select>
			<select class="<?php echo $prefix; ?> cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_DIRECTION'); ?>" type="list" name="<?php echo $prefix; ?>animmovedir" id="<?php echo $prefix; ?>animmovedir" value="" style="width: 100px;" >
				<option value="ltrck"><?php echo \Mobilemenuck\CKText::_('CK_LEFT_TO_RIGHT'); ?></option>
				<option value="rtlck"><?php echo \Mobilemenuck\CKText::_('CK_RIGHT_TO_LEFT'); ?></option>
				<option value="ttbck"><?php echo \Mobilemenuck\CKText::_('CK_TOP_TO_BOTTOM'); ?></option>
				<option value="bttck"><?php echo \Mobilemenuck\CKText::_('CK_BOTTOM_TO_TOP'); ?></option>
			</select>
			<input class="<?php echo $prefix; ?> cktip" title="<?php echo \Mobilemenuck\CKText::_('CK_DISTANCE'); ?>" type="text" name="<?php echo $prefix; ?>animmovedist" id="<?php echo $prefix; ?>animmovedist" value="40" /> [px]
		</div>
		
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>fontweightbold"><?php echo \Mobilemenuck\CKText::_('CK_ROTATE'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_rotate_clockwise.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animrot" id="<?php echo $prefix; ?>animrot" value="" style="width: 100px;" >
				<option value="0"><?php echo \Mobilemenuck\CKText::_('JNO'); ?></option>
				<option value="1"><?php echo \Mobilemenuck\CKText::_('JYES'); ?></option>
			</select>
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animrotrad" id="<?php echo $prefix; ?>animrotrad" value="" style="width: 100px;" >
				<option value="45">45°</option>
				<option value="90">90°</option>
				<option value="180">180°</option>
				<option value="270">270°</option>
				<option value="360">360°</option>
			</select>
		</div>
		<div class="ckrow">
			<label for="<?php echo $prefix; ?>fontweightbold"><?php echo \Mobilemenuck\CKText::_('CK_SCALE'); ?></label>
			<img class="ckicon" src="<?php echo $this->imagespath ?>/shape_handles.png" />
			<select class="<?php echo $prefix; ?>" type="list" name="<?php echo $prefix; ?>animscale" id="<?php echo $prefix; ?>animscale" value="" style="width:100px;" >
				<option value="0"><?php echo \Mobilemenuck\CKText::_('JNO'); ?></option>
				<option value="1"><?php echo \Mobilemenuck\CKText::_('JYES'); ?></option>
			</select>
		</div>
		<div class="ckrow">
			<a class="ckbutton" href="javascript:void(0)" onclick="ckPlayAnimationPreview('<?php echo $prefix; ?>')"><i class="icon icon-play"></i><?php echo \Mobilemenuck\CKText::_('CK_PLAY_ANIMATION'); ?></a>
		</div>
	<?php
	}
}
