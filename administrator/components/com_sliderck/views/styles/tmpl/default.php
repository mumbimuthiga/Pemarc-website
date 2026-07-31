<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// no direct access
defined('_JEXEC') or die;

use Sliderck\CKFof;

// load the lightbox
SliderckHelper::loadCkbox();

// for ordering
$listOrder = $this->state->get('filter_order', 'a.id');
$listDirn = $this->state->get('filter_order_Dir', 'ASC');
$filter_search = $this->state->get('filter_search', '');
$limitstart = $this->state->get('limitstart', 0);
$limit = $this->state->get('limit', 20);
CKFof::addStyleSheet(SLIDERCK_MEDIA_URI . '/assets/adminlist.css');

$isModal = $this->input->get('layout', '') == 'modal' ? true : false;
$function = $this->input->get('returnFunc', 'ckSelectStyle', 'string');
$appendUrl = $isModal ? '&layout=modal&tmpl=component' : '';
?>
		<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_sliderck&view=styles'.$appendUrl); ?>" method="post" name="adminForm" id="adminForm">
			<?php if ($isModal) { ?>
				<a class="btn btn-small btn-success" href="<?php echo \Sliderck\CKUri::root(true) ?>/administrator/index.php?option=com_sliderck&view=style&layout=modal&tmpl=component&id=0&returnFunc=ckSelectStyle">
				<span class="icon-new icon-white"></span>
				<?php echo \Sliderck\CKText::_('JTOOLBAR_NEW'); ?>
				</a>
			<?php } ?>
			<div id="filter-bar" class="btn-toolbar input-group">
				<div class="filter-search btn-group pull-left">
					<label for="filter_search" class="element-invisible"><?php echo \Sliderck\CKText::_('JSEARCH_FILTER_LABEL'); ?></label>
					<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo \Sliderck\CKText::_('JSEARCH_FILTER'); ?>" value="<?php echo $this->state->get('filter_search'); ?>" class="form-control" />
				</div>
				<div class="input-group-append btn-group pull-left hidden-phone">
					<button type="submit" class="btn btn btn-primary hasTooltip" title="<?php echo \Sliderck\CKText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
					<button type="button" class="btn btn-secondary hasTooltip" title="<?php echo \Sliderck\CKText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="this.form.filter_search.value = '';
							this.form.submit();"><i class="icon-remove"></i></button>
				</div>
				<div class="btn-group pull-right hidden-phone ordering-select">
					<label for="limit" class="element-invisible"><?php echo \Sliderck\CKText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
				</div>
			</div>
			<table class="table table-striped" id="itemsList">
				<thead>
					<tr>
						<?php if (! $isModal) { ?>
						<th width="1%">
							<input type="checkbox" name="checkall-toggle" title="<?php echo \Sliderck\CKText::_('JGLOBAL_CHECK_ALL'); ?>" value="" onclick="Joomla.checkAll(this)" />
						</th>
						<?php } ?>
						<th class='left'>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGLOBAL_TITLE', 'a.name', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap">
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach ($this->items as $i => $item) :
						$link = 'index.php?option=com_sliderck&view=style&layout=modal&tmpl=component&id=' . $item->id;
						$name = $item->name ? $item->name : 'style' . $item->id;
						?>
						<tr class="row<?php echo $i % 2; ?>">
							<?php if (! $isModal) { ?>
							<td class="center">
								<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.id', $i, $item->id); ?>
							</td>
							<?php } ?>
							<td>
								<?php if ($isModal) { ?>
								<a href="javascript:void(0)" onclick="window.parent.<?php echo $function ?>('<?php echo $item->id; ?>', '<?php echo $name; ?>')"><?php echo $name; ?></a>
								<?php /*<a href="<?php echo \Sliderck\CKUri::root(true) . '/administrator/' . $link ?>" class="btn"><?php echo \Sliderck\CKText::_('CK_EDIT'); ?></a>*/ ?>
								<?php } else { ?>
								<a onclick="CKBox.open({handler:'iframe', fullscreen: true, url:'<?php echo \Sliderck\CKUri::root(true) . '/administrator/' . $link ?>'})" href="#"><?php echo $name; ?></a>
								<?php } ?>
							</td>
							<td class="center">
							<?php echo (int) $item->id; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
	<?php echo $this->pagination->getListFooter() ?>
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
				<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
			</div>
		</form>
