<?php
/**
 * @copyright	Copyright (C) 2016 Cédric KEIFLIN alias ced1870
 * http://www.template-creator.com
 * http://www.joomlack.fr
 * @license		GNU/GPL
 * */

defined('_JEXEC') or die;
?>
<div class="swiper-container swiper-layout-<?php echo $params->get('sliderlayout', 'over') ?> <?php echo $moduleclass_sfx ?>" id="sliderck<?php echo $module->id ?>" dir="<?php echo $langdirection ?>" data-effect="<?php echo $effect ?>">
	<div class="swiper-wrapper">
		<?php 
		$i = 1;
		foreach ($items as $item) { 
		// limit the number of slides
		if ($numberslides && $i > $numberslides) break;
		// B/C for V1
		if (isset($item->path) && ! isset($item->image)) SliderckHelper::legacyUpdateItem($item);

		if ($params->get('lightboxautolinkimages', '0') == '1') {
			$item->link = $item->link ? $item->link : $item->image;
		}

		$caption = str_replace("|dq|", "\"", (string)$item->text);
		if ($params->get('content_prepare', 0)) $caption = \Joomla\CMS\HTML\HTMLHelper::_('content.prepare', $caption);
		$textlength = (int)$params->get('textlength', '0');
		if ($params->get('striptags', '0') == '1' && $item->texttype != 'pagebuilderck') {
			$caption = strip_tags($caption);
		}
		if ($textlength > 0) {
			$caption = SliderckHelper::substring($caption, $textlength, '...', false);
		}
		if ($params->get('fixhtml', '0') == '1' && trim($caption)) {
			// Parse the html code of the text into a fixer to avoid bad rendering issues
			$htmlfixer = new SliderCKHtmlFixer();
			$captionFixed = $htmlfixer->getFixedHtml(trim($caption));
			$caption = $captionFixed;
		}
		// set variables
		$link = $params->get('linkautoimage', '0') == '1' && $item->image && !$item->link ? $item->image : $item->link;
		$linkClass = ( $linkposition == 'button' ? $params->get('linkbuttonclass', 'btn') . ' swiper-button' : ' swiper-link' );
		$target = ($item->target == 'default') ? $params->get('linktarget') : $item->target;
		$linkTarget = ( $target == '_blank' ? ' target="_blank"' : '' );
		$datacaptiontitle = str_replace("|dq|", "\"", (string)$item->title);
//		$datacaptiontext = str_replace("|dq|", "\"", (string)$item->text);
		$datacaptionforlightbox = $datacaptiontitle . ( $caption ? '::' . $caption : '');
		$cleanedtitle = htmlspecialchars(str_replace("\"", "&quot;", str_replace(">", "&gt;", str_replace("<", "&lt;", $datacaptiontitle))));
		$datatitle = ($params->get('lightboxcaption', 'caption') != 'caption') ? ' title="' . htmlspecialchars(str_replace("\"", "&quot;", str_replace(">", "&gt;", str_replace("<", "&lt;", $datacaptionforlightbox)))) . '" ' : '';
		$album = ($params->get('lightboxgroupalbum', '0')) ? '[albumslideshowck' .$module->id .']' : '';
		$datarel = ($target == 'lightbox') ? ' rel="' . $lightboxAttribs . '" ' : '';
		$startLink = '<a aria-label="' . $cleanedtitle . '" class="' . $linkClass .'" href="' . $link . '"' . $datarel . $datatitle . $linkTarget . '>';
		?>
		<div class="swiper-slide">
			<?php // TODO : use height:calc(100% - <?php echo $captionheight  
//			if ($item->image) {
			?>
			<figure class="swiper-img-cont" style="background-image:url('<?php echo $item->image ?>')">
				<img class="swiper-img" alt="<?php echo $item->title ?>" src="<?php echo $item->image ?>" />
				<?php if ($item->video) { ?>
					<?php if (strpos($item->video, 'http') !== 0) {
					$autoplay = isset($item->videoautoplay) && $item->videoautoplay == '1' ? 'data-autoplay="1" muted="muted"' : '';
					$videoloop = isset($item->videoloop) && $item->videoloop == '1' ? ' loop' : '';
					$videocontrols = isset($item->videocontrols) && $item->videocontrols == '0' ? ($autoplay ? '' : ' onclick="this.play()"') : ' controls';
					?>
					<video src="<?php echo $item->video; ?>" width="100%" height="100%" playsinline <?php echo $autoplay ?><?php echo $videoloop ?><?php echo $videocontrols ?>>
						<source src="<?php echo $item->video ?>" >
					</video>
					<?php } else { ?>
					<iframe src="<?php echo $item->video; ?>" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
					<?php } ?>
				<?php
				}
				?>
			</figure>
			<?php 
//			}
			$showcaption = $params->get('showcaption', '1') == '1' && ($item->title || $item->text) && (($params->get('lightboxcaption', 'caption') != 'title' || $target != 'lightbox') || !$link);
			$showtitle = $params->get('showtitle', '1') == '1' && $item->title;
			$showdescription = $params->get('showdescription', '1') == '1' && $item->text;
			if ($showcaption) { ?>
			<div class="swiper-caption">
				<?php if ($showtitle) { ?>
				<div class="swiper-title">
					<?php if ($link && $linkposition == 'title') {
						echo $startLink . str_replace("|dq|", "\"", (string)$item->title) . '</a>';
					} else {
						echo $item->title;
					} ?>
				</div>
				<?php } ?>
				<?php if ($showdescription) { ?>
				<div class="swiper-desc">
					<?php 
					echo $caption;
					?>
				</div>
				<?php } ?>
				<?php
				if (isset($item->more) && count($item->more)) {
					foreach ($item->more as $m) {
						echo $m;
					}
				}
				?>
				<?php if ($link && $linkposition == 'caption') {
					echo $startLink . '</a>';
				} ?>
				<?php if ($link && $linkposition == 'button') { ?>
					<?php echo $startLink . \Sliderck\CKText::_($params->get('linkbuttontext', 'MOD_SLIDERCK_LINK_BUTTON_TEXT')) . '</a>'; ?>
				<?php } ?>
			</div>
			<?php } ?>
			<?php if ($link && $linkposition == 'fullslide') {
				echo $startLink . '</a>';
			} ?>
		</div>
		<?php 
		$i++;
		} ?>
	</div>
	<?php if ($params->get('pagination', 'bullets') != 'none') { ?>
	<div class="swiper-pagination"></div>
	<?php } ?>
	<?php if ($params->get('navigationbuttons', '1') == '1') { ?>
	<div class="swiper-button-next"></div>
	<div class="swiper-button-prev"></div>
	<?php } ?>
</div>

<?php if ($params->get('pagination', 'bullets') == 'thumbs') { ?>
<div class="swiper-container gallery-thumbs" id="sliderck<?php echo $module->id ?>thumbs" dir="<?php echo $langdirection ?>">
	<div class="swiper-wrapper">
		<?php 
		$i = 1;
		foreach ($items as $item) { 
			if ($numberslides && $i > $numberslides) break;
		?>
			<div class="swiper-slide" style="background-image:url('<?php echo $item->image ?>')"></div>
		<?php 
			$i++;
		} ?>
	</div>
</div>
<?php } ?>
