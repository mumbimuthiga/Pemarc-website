<?php
/**
 * @name		Slider CK
 * @package		com_sliderck
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - http://www.template-creator.com - http://www.joomlack.fr
 */

// No direct access
defined('_JEXEC') or die;

use Sliderck\CKController;
use Sliderck\CKFof;

class SliderckControllerMenus extends CKController {

	function ajaxShowMenuItems() {
		// security check
		CKFof::checkAjaxToken();

		$app = \Sliderck\CKFof::getApplication();
		$input = $app->input;
		$parentId = $input->get('parentid', 0, 'int');
		$menutype = $input->get('menutype', '', 'string');

		$model = $this->getModel('Menus', '', array());
		$items = $model->getChildrenItems($menutype, $parentId);

		$links = array();
		$imagespath = SLIDERCK_MEDIA_URI .'/images/';
		?>
		<div class="cksubfolder">
		<?php
		foreach ($items as $item) {
			$aliasId = $item->id;
			if ($item->type == 'alias') {
				$itemParams = new \Sliderck\CKRegistry($item->params);
				$aliasId = $itemParams->get('aliasoptions', 0);
			}
			$Itemid = substr($item->link,-7,7) == 'Itemid=' ? $aliasId : '&Itemid=' . $aliasId;
		?>
			<div class="ckfoldertree parent">
				<div class="ckfoldertreetoggler <?php if ($item->rgt - $item->lft <= 1) { echo 'empty'; } ?>" onclick="ckToggleTreeSub(this, <?php echo $item->id ?>)" data-menutype="<?php echo $item->menutype; ?>"></div>
				<div class="ckfoldertreename hasTip" title="<?php echo $item->link . $Itemid ?>" onclick="ckSetMenuItemUrl('<?php echo $item->link . $Itemid ?>')"><img src="<?php echo $imagespath ?>folder.png" /><?php echo $item->title; ?></div>
			</div>
		<?php
		}
		?>
		</div>
		<?php
		exit;
	}
}