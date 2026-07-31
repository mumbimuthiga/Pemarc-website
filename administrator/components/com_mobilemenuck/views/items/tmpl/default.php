<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */
 
// no direct access
defined('_JEXEC') or die;

use Mobilemenuck\CKText;

MobilemenuckHelper::loadCkbox();

// check the joomla! version
if (version_compare(JVERSION, '3.0.0') > 0) {
	$jversion = '3';
} else {
	$jversion = '2';
}

$doc = \Mobilemenuck\CKFof::getDocument();
$doc->addStylesheet(MOBILEMENUCK_MEDIA_URI . '/assets/ckframework.css');
$doc->addScript(MOBILEMENUCK_MEDIA_URI . '/assets/jquery-uick-custom.js');
$doc->addScript(MOBILEMENUCK_MEDIA_URI . '/assets/admin.js');

// vars
$input	= \Mobilemenuck\CKFof::getApplication()->input;
$modal = $input->get('layout', '') == 'modal' ? true : false;

$user = \Joomla\CMS\Factory::getUser();
$userId = $user->get('id');
$isModal = $input->get('layout', '', 'string') == 'modal';
$function = $input->get('returnFunc', 'ckMobilemenuSelectStyle', 'string');
$appendUrl = $isModal ? '&modal=1&tmpl=component' : '';
$style = $isModal ? 'style="padding:10px;"' : '';

// for ordering
$listOrder = $this->state->get('filter_order', 'a.id');
$listDirn = $this->state->get('filter_order_Dir', 'ASC');
$filter_search = $this->state->get('filter_search', '');
$limitstart = $this->state->get('limitstart', 0);
$limit = $this->state->get('limit', 20);
?>

<script>
function ckMobilemenuSelectStyle(styleid, name, close) {
	var id = jQuery(jQuery('.editing').parents('.ckrow')[0]).attr('data-id');
	ckSetStyle(id, styleid, 'ckUpdateStyle');
}

function ckMobilemenuSelectCustomMenuStyle(styleid, name, close) {
	var id = jQuery(jQuery('.editing').parents('.ckrow')[0]).attr('data-id');
	ckSetCustomMenuStyle(id, styleid, 'ckUpdateStyle');
}

function ckRemoveStyle() {
	var id = jQuery(jQuery('.editing').parents('.ckrow')[0]).attr('data-id');
	ckSetStyle(id, 0, 'ckUpdateStyle');
}

function ckUpdateStyle(id, styleid, name) {
	if (! id) id = jQuery(jQuery('.editing').parents('.ckrow')[0]).attr('data-id');
	if (styleid > 0) {
		jQuery('.ckrow[data-id="' + styleid + '"]').find('.styleid').text('ID ' + styleid);
		var namehtml = '<a data-id="'+ styleid +'" onclick="if (jQuery(this).attr(\'data-id\'))  { CKBox.open({handler:\'iframe\', fullscreen: true, url:\'index.php?option=com_mobilemenuck&amp;view=style&amp;tmpl=component&amp;id=\' + jQuery(this).attr(\'data-id\')}) }" href="#">' + name + '</a>';
		jQuery('.ckrow[data-id="' + id + '"]').find('.stylename').html(namehtml).parent().attr('data-id', styleid);
		jQuery('.ckrow[data-id="' + id + '"]').removeClass('editing');
	} else {
		jQuery('.ckrow[data-id="' + id + '"]').find('.styleid').text('<?php echo \Mobilemenuck\CKText::_('CK_NONE') ?>');
		jQuery('.ckrow[data-id="' + id + '"]').find('.stylename').text('').parent().attr('data-id', '0');
		jQuery('.ckrow[data-id="' + id + '"]').removeClass('editing');
	}
	CKBox.close();
}

function ckUpdateMobileState(id, state) {
	if (! id) id = jQuery(jQuery('.editing').parents('.ckrow')[0]).attr('data-id');
	var stateclass = state == '1' ? 'publish' : 'unpublish';
	jQuery('.ckrow[data-id="' + id + '"]').find('.ckstate').removeClass('icon-publish').removeClass('icon-unpublish').addClass('icon-' + stateclass).attr('data-state', state);
	jQuery('.ckrow[data-id="' + id + '"]').removeClass('editing');
	CKBox.close();
}

// needed for ajax
var CKTOKEN = '<?php echo MobilemenuckHelper::getToken() ?>=1';
</script>
<div class="ckinterface">
	<div>
		<div class="ckinterfacetablink current" data-tab="tab_modules" data-group="main"><?php echo \Mobilemenuck\CKText::_('CK_EXISTING_MODULES'); ?></div>
		<div class="ckinterfacetablink" data-tab="tab_custom_menus" data-group="main"><?php echo \Mobilemenuck\CKText::_('CK_CUSTOM_MENUS'); ?></div>
		<div class="ckclr" style="clear:both;"></div>
	</div>
	<div class="ckinterfacetab current" id="tab_modules" data-group="main">
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
			<div class="ckclr" style="clear:both;"></div>
			<table class="cktable cktable-striped cktable-hover cktable-single-bordered" id="ckitemslist">
				<thead>
					<tr>
						<?php if (! $isModal) { ?>
						<th width="1%" style="display:none;">
							<input type="checkbox" name="checkall-toggle" title="<?php echo \Mobilemenuck\CKText::_('JGLOBAL_CHECK_ALL'); ?>" value="" onclick="Joomla.checkAll(this)" />
						</th>
						<?php } ?>
						<th width="15%" class='left'>
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JGLOBAL_TITLE', 'a.module', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap">
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CK_TYPE', 'a.module', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap">
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'CK_POSITION', 'a.position', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap center">
							<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap center">
							<?php echo \Mobilemenuck\CKText::_('CK_MOBILE_ENABLED'); ?>
						</th>
						<th width="20%" class="nowrap">
							<?php echo \Mobilemenuck\CKText::_('CK_STYLE'); ?>
						</th>
						<th width="15%" class="nowrap">
							<?php echo \Mobilemenuck\CKText::_('CK_MERGED_WITH'); ?>
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
						$mergeid = $item->params->get('mobilemenuck_merge');
						$stylespanstyle = $mergeid ? 'display: none;' : 'display: inline-block;';
						$link = 'index.php?option=com_modules&task=module.edit&id=' . $item->id;
		//				$stylelink = 'index.php?option=com_mobilemenuck&view=style&layout=modal&tmpl=component&id=' . $style;
						?>
						<tr class="row<?php echo $i % 2; ?> ckrow" data-id="<?php echo (int) $item->id; ?>">
							<?php if (! $isModal) { ?>
							<td class="center" style="display:none;">
								<?php echo \Joomla\CMS\HTML\HTMLHelper::_('grid.id', $i, $item->id); ?>
							</td>
							<?php } ?>

							<td>
								<a href="<?php echo \Mobilemenuck\CKUri::root(true) . '/administrator/' . $link ?>"><?php echo $item->title; ?></a>
							</td>
							<td class="">
								<span class="cklabel">
									<?php echo $item->module; ?>
								</span>
							</td>
							<td class="">
								<span class="cklabel">
									<?php echo $item->position ? $item->position : '::' . \Mobilemenuck\CKText::_('CK_NONE') . '::'; ?>
								</span>
							</td>
							<td class="center" style="text-align: center;">
								<div class="ckbutton-group">
									<span class="icon-<?php echo ($item->published ? '' : 'un'); ?>publish" style="font-size:12px;"></span>
									<?php //echo \Joomla\CMS\HTML\HTMLHelper::_('modules.state', $item->published, $i, false, 'cb'); 

									?>
								</div>
							</td>
							<td class="center" style="text-align: center;">
								<div class="">
									<span data-id="<?php echo (int) $item->id; ?>" data-state="<?php echo ($item->params->get('mobilemenuck_enable', '0') ? '1' : '0'); ?>" class="ckstate icon-<?php echo ($item->params->get('mobilemenuck_enable', '0') ? '' : 'un'); ?>publish" style="font-size:12px;" ></span>
								</div>
							</td>
							<td class="style">
								<a href="https://www.joomlack.fr/en/joomla-extensions/mobile-menu-ck" target="_blank"><?php echo Joomla\CMS\Language\Text::_('CK_ONLY_PRO') ?></a>
							</td>
							<td class="">
								<a href="https://www.joomlack.fr/en/joomla-extensions/mobile-menu-ck" target="_blank"><?php echo Joomla\CMS\Language\Text::_('CK_ONLY_PRO') ?></a>
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
	<div class="ckinterfacetab" id="tab_custom_menus" data-group="main">
		<p><a href="https://www.joomlack.fr/en/joomla-extensions/mobile-menu-ck" target="_blank"><?php echo Joomla\CMS\Language\Text::_('CK_ONLY_PRO') ?></a></p>
	</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
	ckMakeItemsSortable();
	ckInitInterfaceTabs();
});

function CKsubmitform(action) {
	if (action == 'menu.delete') {
		var c = confirm('<?php echo CKText::_('Are you sure to want to delete ?') ?>');
		if (c == false) return;
	}

	var form = document.getElementById('customMenusForm');
	// var selector = document.getElementsByName('action')[0];
	var selector = form.task;
	selector.value = action;
	var selection = CKgetcheckedItems();
	if (! selection.length) {
		alert('Please select an item');
		return;
	}
	form.submit();
}

function CKgetcheckedItems() {
	var form = document.getElementById('customMenusForm');
	var list = form.querySelectorAll('[name="cid[]"]');
	var results = [];
	for(var i = 0; i < list.length; i++){
		list[i].checked ? results.push(list[i]):"";
	}
	return results;
}
</script>