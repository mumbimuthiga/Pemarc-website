<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// no direct access
defined('_JEXEC') or die;
MobilemenuckHelper::loadCkbox();

// check the joomla! version
if (version_compare(JVERSION, '3.0.0') > 0) {
	$jversion = '3';
} else {
	$jversion = '2';
}

$doc = \Mobilemenuck\CKFof::getDocument();
$doc->addScript(MOBILEMENUCK_MEDIA_URI . '/assets/admin.js');
$doc->addStylesheet(MOBILEMENUCK_MEDIA_URI . '/assets/ckframework.css');

// vars
$input	= \Mobilemenuck\CKFof::getApplication()->input;
$modal = $input->get('layout', '') == 'modal' ? true : false;

$user = \Joomla\CMS\Factory::getUser();
$userId = $user->get('id');
$layout = $input->get('layout', '', 'string');
$function = $input->get('returnFunc', 'ckMobilemenuSelectModule', 'string');
$appendUrl = $layout ? '&layout=' . $layout . '&tmpl=component' : '';
$style = $layout ? 'style="padding:10px;"' : '';

// for ordering
$listOrder = $this->state->get('filter_order', 'a.id');
$listDirn = $this->state->get('filter_order_Dir', 'ASC');
$filter_search = $this->state->get('filter_search', '');
$limitstart = $this->state->get('limitstart', 0);
$limit = $this->state->get('limit', 20);
?>
<div class="ckinterface">
	<form action="<?php echo \Joomla\CMS\Router\Route::_('index.php?option=com_mobilemenuck&view=items'.$appendUrl); ?>" method="post" name="adminForm" id="adminForm" <?php echo $style ?>>
		<div id="filter-bar" class="btn-toolbar input-group">
			<div class="filter-search btn-group pull-left">
				<label for="filter_search" class="element-invisible"><?php echo \Mobilemenuck\CKText::_('JSEARCH_FILTER_LABEL'); ?></label>
				<input type="text" name="filter_search" id="filter_search" placeholder="<?php echo \Mobilemenuck\CKText::_('JSEARCH_FILTER'); ?>" value="<?php echo addslashes($this->state->get('filter_search')); ?>" class="cktip form-control" title="" />
			</div>
			<div class="input-group-append btn-group pull-left hidden-phone">
				<button type="submit" class="btn btn-primary cktip" title="<?php echo \Mobilemenuck\CKText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i><?php echo ($jversion === '2' ? \Mobilemenuck\CKText::_('JSEARCH_FILTER_SUBMIT') : ''); ?></button>
				<button type="button" class="btn btn-secondary cktip" title="<?php echo \Mobilemenuck\CKText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.getElementById('filter_search').value = '';
						this.form.submit();"><i class="icon-remove"></i><?php echo ($jversion === '2' ? \Mobilemenuck\CKText::_('JSEARCH_FILTER_CLEAR') : ''); ?></button>
			</div>
			<?php if ($jversion === '3') { ?>
			<div class="btn-group pull-right hidden-phone ordering-select">
				<label for="limit" class="element-invisible"><?php echo \Mobilemenuck\CKText::_('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC'); ?></label>
				<?php echo $this->pagination->getLimitBox(); ?>
			</div>
			<?php } ?>
		</div>
		<table class="table table-striped" id="templateckList">
			<thead>
				<tr>
					<?php if (! $layout) { ?>
					<th width="1%" style="display:none;">
						<input type="checkbox" name="checkall-toggle" title="<?php echo \Mobilemenuck\CKText::_('JGLOBAL_CHECK_ALL'); ?>" value="" onclick="Joomla.checkAll(this)" />
					</th>
					<?php } ?>
					<th class='left'>
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGLOBAL_TITLE', 'a.module', $listDirn, $listOrder); ?>
					</th>
					<th width="15%" class="nowrap">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CK_TYPE', 'a.module', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center">
						<?php echo \Mobilemenuck\CKText::_('CK_MOBILE_ENABLED'); ?>
					</th>
					<th width="15%" class="nowrap">
						<?php echo \Mobilemenuck\CKText::_('CK_STYLE'); ?>
					</th>
					<th width="1%" class="nowrap">
						<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="10">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				foreach ($this->items as $i => $item) :
					$item->params = new \Mobilemenuck\CKRegistry($item->params);
					$style = $item->params->get('mobilemenuck_styles');
					$styleName = \Mobilemenuck\Helper::getStyleNameById($style);
					$link = 'index.php?option=com_modules&task=module.edit&id=' . $item->id;
	//				$stylelink = 'index.php?option=com_mobilemenuck&view=style&layout=modal&tmpl=component&id=' . $style;
					?>
					<tr class="row<?php echo $i % 2; ?> ckrow" data-id="<?php echo (int) $item->id; ?>">
						<?php if (! $layout) { ?>
						<td class="center" style="display:none;">
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.id', $i, $item->id); ?>
						</td>
						<?php } ?>
						<td>
							<a href="javascript:void(0)" onclick="window.parent.<?php echo $function ?>('<?php echo $item->id; ?>', '<?php echo addslashes($item->title); ?>')"><?php echo $item->title; ?></a>
						</td>
						<td class="">
							<span class="label">
								<?php echo $item->module; ?>
							</span>
						</td>
						<td class="center">
							<div class="btn-group">
								<span class="icon-<?php echo ($item->published ? '' : 'un'); ?>publish" style="font-size:12px;"></span>
								<?php //echo \Joomla\CMS\HTML\HTMLHelper::_('modules.state', $item->published, $i, false, 'cb'); 
								
								?>
							</div>
						</td>
						<td class="center">
							<div class="btn-group">
								<span data-id="<?php echo (int) $item->id; ?>" data-state="<?php echo ($item->params->get('mobilemenuck_enable', '0') ? '1' : '0'); ?>" class="ckstate icon-<?php echo ($item->params->get('mobilemenuck_enable', '0') ? '' : 'un'); ?>publish" style="font-size:12px;"></span>
							</div>
						</td>
						<td class="">
							<?php if ($style) : ?>
							<span data-id="<?php echo $style ?>"><span class="stylename"><?php echo $styleName; ?></span></span>
							<span class="label styleid">
							ID <?php echo $style; ?>
							</span>
							<?php else : ?>
							<span class="stylename"></span>
							<span class="label styleid">
							<?php echo \Mobilemenuck\CKText::_('JNONE'); ?>
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
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<input type="hidden" name="state_request" value="1" />
		<?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
	</form>
</div>