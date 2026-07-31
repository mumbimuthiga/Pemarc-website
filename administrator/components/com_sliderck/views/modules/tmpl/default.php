<?php
/**
 * @name		Maximenu CK params
 * @package		com_maximenuck
 * @copyright	Copyright (C) 2014. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
// no direct access
defined('_JEXEC') or die;

// check the joomla! version
// if (version_compare(JVERSION, '3.0.0') > 0) {
	// $jversion = '3';
// } else {
	// $jversion = '2';
// }

// load the tooltip on the correct css class for joomla 2.5
// if ($jversion === '2') {
	// \Joomla\CMS\HTML\HTMLHelper::_('behavior.tooltip', '.hasTooltip');
// }
// ?>
<div style="display:flex;flex-wrap:wrap;">
	<div style="flex: 0 0 250px">
	<?php SliderckHelper::addSidebar(); ?>
	</div>
	<div style="flex: 1 1 auto">
		<table class="table table-striped" id="templateckList">
			<thead>
				<tr>
					<th class='left'>
						<?php echo \Sliderck\CKText::_('JGLOBAL_TITLE'); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo \Sliderck\CKText::_('JSTATUS'); ?>
					</th>
					<th width="15%" class="nowrap hidden-phone">
					<?php echo \Sliderck\CKText::_('CK_STYLE'); ?>
					</th>
					<th width="15%" class="nowrap hidden-phone">
					<?php echo \Sliderck\CKText::_('CK_MODULE_POSITION'); ?>
					</th>
					<th width="1%" class="nowrap">
					<?php echo \Sliderck\CKText::_('JGRID_HEADING_ID'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
				foreach ($this->items as $i => $item) :
					$item->params = new \Sliderck\CKRegistry($item->params);
					$styleName = SliderckHelper::getStyleNameById($item->params->get('styles'));
					$link = 'index.php?option=com_modules&task=module.edit&id=' . $item->id;
					?>
					<tr class="row<?php echo $i % 2; ?>">
						<td>
							<a href="<?php echo \Sliderck\CKUri::root(true) . '/administrator/' . $link ?>"><?php echo $item->title; ?></a>
						</td>
						<td class="center">
							<div class="btn-group">
								<span class="icon-<?php echo ($item->published ? '' : 'un'); ?>publish" style="font-size:12px;"></span>
								<?php //echo \Joomla\CMS\HTML\HTMLHelper::_('modules.state', $item->published, $i, false, 'cb'); 
								
								?>
							</div>
						</td>
						<td class="">
							<?php if ($item->params->get('styles')) : ?>
							<?php echo $styleName; ?>
							<span class="label">
							ID <?php echo $item->params->get('styles'); ?>
							</span>
							<?php else : ?>
							<span class="label">
							<?php echo \Sliderck\CKText::_('JNONE'); ?>
							</span>
							<?php endif; ?>
						</td>

						<td class="small">
							<?php if ($item->position) : ?>
							<span class="label label-info">
							<?php echo $item->position; ?>
							</span>
							<?php else : ?>
							<span class="label">
							<?php echo \Sliderck\CKText::_('JNONE'); ?>
							</span>
							<?php endif; ?>
						</td>

						<?php if (isset($this->items[0]->id)) {
							?>
							<td class="center">
							<?php echo (int) $item->id; ?>
							</td>
						<?php } ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>