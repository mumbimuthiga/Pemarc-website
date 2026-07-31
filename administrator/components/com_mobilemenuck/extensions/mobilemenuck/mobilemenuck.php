<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 */

defined('_JEXEC') or die;


use Joomla\Module\Menu\Site\Helper\MenuHelper;
if (version_compare(JVERSION, '4') < 0) {
	include_once JPATH_ROOT . '/modules/mod_menu/helper.php';
}

/*
 * Module Chrome function specific to encapsulate the module
 */ 
// function modChrome_mobilemenuck($module, &$params, &$attribs)
// {
	
// }

/*
 * Class of the plugin
 */
class PlgSystemMobilemenuck extends \Joomla\CMS\Plugin\CMSPlugin {

	private $styles = Array();

	private $scripts = Array();

	private $stylesLoaded = Array();

//	private $scriptsLoaded = Array();

	private $modulesbar = Array();

	private $modulestop = Array();

	private $modulesbottom = Array();

	private $modules = Array();

	public function __construct(&$subject, $config = array()) {
		include_once JPATH_ROOT . '/administrator/components/com_mobilemenuck/helpers/defines.php';

		parent::__construct($subject, $config);
	}

	private function callLibraries() {
//		include_once(dirname(__FILE__) . '/defines.php');
		include_once(dirname(__FILE__) . '/helpers/helper.php');
		include_once(dirname(__FILE__) . '/helpers/menu.php');
		include_once(dirname(__FILE__) . '/helpers/function.php');
	}

	private function shallLoad() {
		$app = \Mobilemenuck\CKFof::getApplication();
		if (! $app->isClient('site') && ! $app->isClient('administrator'))
		{
			return false;
		}
		if ($app->isClient('cli'))
		{
			return false;
		}
		if ($app->isClient('api'))
		{
			return false;
		}

		$doc = \Mobilemenuck\CKFof::getDocument();
		$doctype = $doc->getType();
		// $document = \Mobilemenuck\CKFof::getApplication()->getDocument();

		if ($doctype !== 'html')
		{
			return false;
		}

		return true;
	}

	public function onContentPrepareForm($form, $data) {
		if (! $this->shallLoad()) return;
		$this->callLibraries();
		if (
			($form->getName() != 'com_modules.module' && $form->getName() != 'com_advancedmodules.module' 
				|| (($form->getName() == 'com_modules.module' || $form->getName() == 'com_advancedmodules.module') && $data && isset($data->module) && $data->module != 'mod_maximenuck' && $data->module != 'mod_accordeonck' && $data->module != 'mod_accordeonmenuck' && $data->module != 'mod_menu')
				)
			&& ($form->getName() != 'com_menus.item' && $form->getName() != 'com_menumanagerck.itemedition')
			)
			return;

		\Joomla\CMS\Form\Form::addFormPath(MOBILEMENUCK_PATH .'/params');
		\Joomla\CMS\Form\Form::addFieldPath(MOBILEMENUCK_PATH . '/elements');

		// get the language
		$this->loadLanguage();

		// load the additional options in the module
		if ($form->getName() == 'com_modules.module' || $form->getName() == 'com_advancedmodules.module') {
			$form->loadFile('mobilemenuck_params', false);
		}

		// menu item options
		if ($form->getName() == 'com_menus.item' || $form->getName() == 'com_menumanagerck.itemedition') {
			$form->loadFile('mobilemenuck_itemparams', false);
		}
	}

	/**
	 * Event launched when the module is rendered in the page
	 *
	 * @param       object   The module element.
	 * @param       array    The attributes to render the module.
	 * @return      void
	 */
	public function onRenderModule($module, &$attribs) {
		if (! $this->shallLoad()) return;

		$app = \Mobilemenuck\CKFof::getApplication();
		$input = $app->input;

		if ($app->isClient('administrator')) {
			return;
		}

		// exit if we are in one of these cases
		if ($input->get('option', '', 'string') == 'com_modulesmanagerck' 
			|| $input->get('option', '', 'string') == 'com_media' 
			|| $input->get('option', '', 'string') == 'com_ajax' 
			|| $input->get('format', '', 'string') == 'raw'
			|| $input->get('option', '', 'string') == 'com_config'
			|| $input->get('ck', '', 'string') === '1'
			) {
			return;
		}

		// exit if there is no possibility to get the params from the module (unknown reason)
		if (! isset($module->params)) return;

		// $loadAssets = false;
		$moduleParams = new \Mobilemenuck\CKRegistry($module->params);
		// find a module enabled for mobile
		if ($moduleParams->get('mobilemenuck_enable', 0, 'int')) {
			$attribs['style'] .= ' mobilemenuck';
			// $loadAssets = true;

			if (!empty ($module->content)) {
//				include_once(dirname(__FILE__) . '/defines.php');
				include_once JPATH_ROOT . '/administrator/components/com_mobilemenuck/helpers/defines.php';
				include_once(dirname(__FILE__) . '/helpers/helper.php');
				include_once(dirname(__FILE__) . '/helpers/menu.php');
				include_once(dirname(__FILE__) . '/helpers/function.php');

				$id = \Mobilemenuck\Helper::createIdForModule($module);
				if ($module->module == 'mod_maximenuck') {
					$selector = '#' . $id;
					$menuselector = 'ul.maximenuck';
				} else if ($module->module == 'mod_accordeonmenuck') {
					$selector = '#' . $id . '-wrap';
					$html = '<div id="' . $id . '-wrap">
								' . $module->content . '
							</div>';
					$module->content = $html;
					$menuselector = 'ul.menu';
				} else {
					if (version_compare(JVERSION, '4') >= 0) {
						$selector = '#' . $id . '-wrap ul.nav';
					} else {
						$selector = '#' . $id . '-wrap ul.nav';
					}
					$html = '<div id="' . $id . '-wrap">
								' . $module->content . '
							</div>';

					$module->content = $html;
					$menuselector = 'ul';
				}
				$styleid = $moduleParams->get('mobilemenuck_styles');
				$menubarbuttoncontent = '&#x2261;';
				$topbarbuttoncontent = '×';
				if ($styleid) {
					$styleParams = json_decode(\Mobilemenuck\Helper::getStyleById($styleid, 'a.params'));
					if (! isset($styleParams->menubarbuttoncontent)) $styleParams->menubarbuttoncontent = 'hamburger';
					if (! isset($styleParams->topbarbuttoncontent)) $styleParams->topbarbuttoncontent = \Mobilemenuck\CKText::_('JCLOSE');
					$menubarbuttoncontent = \Mobilemenuck\Menu::getButtonContent($styleParams->menubarbuttoncontent, $styleParams);
					$topbarbuttoncontent = \Mobilemenuck\Menu::getButtonContent($styleParams->topbarbuttoncontent, $styleParams);
				}

				// translations
				\Mobilemenuck\CKText::script('PLG_MOBILEMENUCK_SEARCH');
				\Mobilemenuck\CKText::script('PLG_MOBILEMENUCK_BAR_BUTTON_LABEL');
				\Mobilemenuck\CKText::script('PLG_MOBILEMENUCK_LOCK_BUTTON_LABEL');
				\Mobilemenuck\CKText::script('PLG_MOBILEMENUCK_MENU_BUTTON_LABEL');
				\Mobilemenuck\CKText::script('PLG_MOBILEMENUCK_SEARCH_LABEL');
				\Mobilemenuck\CKText::script('PLG_MOBILEMENUCK_TOGGLER_ICON_LABEL');
				\Mobilemenuck\CKText::script('PLG_MOBILEMENUCK_SEARCH_RESET_LABEL');

				// loads the language files
				$lang	= \Mobilemenuck\CKFof::getLanguage();
				$lang->load('plg_system_mobilemenuck', JPATH_SITE . '/plugins/system/mobilemenuck', $lang->getTag(), false);
				$lang->load('plg_system_mobilemenuck', JPATH_ADMINISTRATOR, $lang->getTag(), false);

				$merge = $moduleParams->get('mobilemenuck_merge', '');
				$mergeorder = $moduleParams->get('mobilemenuck_mergeorder', '');
				if ($merge) {
					$mergemodule = \Mobilemenuck\Helper::getModuleById($merge);
					$merge = \Mobilemenuck\Helper::createIdForModule($mergemodule);
				}

				// manage the general options
				$options = array(
						'menuid' => $id
						,'menubarbuttoncontent' => $menubarbuttoncontent
						,'topbarbuttoncontent' => $topbarbuttoncontent
						,'showmobilemenutext' => $moduleParams->get('mobilemenuck_showmobilemenutext', 'default')
						,'mobilemenutext' => \Mobilemenuck\CKText::_($moduleParams->get('mobilemenuck_mobilemenutext', 'PLG_MOBILEMENUCK_MENU'))
						,'container' => $moduleParams->get('mobilemenuck_container', 'body')
						,'detectiontype' => $moduleParams->get('mobilemenuck_detectiontype', 'resolution')
						,'resolution' => $moduleParams->get('mobilemenuck_resolution', '640')
						,'usemodules' => $moduleParams->get('mobilemenuck_usemodules', '0')
						,'useimages' => $moduleParams->get('mobilemenuck_useimages', '0')
						,'showlogo' => $moduleParams->get('mobilemenuck_showlogo', '1')
						,'showdesc' => $moduleParams->get('mobilemenuck_showdesc', '0')
						,'displaytype' => $moduleParams->get('mobilemenuck_displaytype', 'accordion')
						,'displayeffect' => $moduleParams->get('mobilemenuck_displayeffect', 'normal')
						,'menuwidth' => $moduleParams->get('mobilemenuck_menuwidth', '300')
						,'openedonactiveitem' => $moduleParams->get('mobilemenuck_openedonactiveitem', '0')
						,'mobilebackbuttontext' => \Mobilemenuck\CKText::_($moduleParams->get('mobilemenuck_mobilebackbuttontext', 'PLG_MOBILEMENUCK_MOBILEBACKBUTTON'))
						,'langdirection' => \Mobilemenuck\CKFof::getDocument()->getDirection()
						,'menuselector' => $menuselector
						,'merge' => $merge
						,'beforetext' => addslashes($moduleParams->get('mobilemenuck_beforetext', ''))
						,'aftertext' => addslashes($moduleParams->get('mobilemenuck_aftertext', ''))
						,'mergeorder' => $mergeorder
						,'tooglebarevent' => $moduleParams->get('mobilemenuck_tooglebarevent', 'click')
						,'tooglebaron' => $moduleParams->get('mobilemenuck_tooglebaron', 'all')
						// Logo options
						,'logo_where' => implode(',', $moduleParams->get('mobilemenuck_logo_where', array(0 => '1')))					// 1, 2, 3
						,'logo_source' => $moduleParams->get('mobilemenuck_logo_source', 'maximenuck')		// maximenuck, custom
						,'logo_image' => $moduleParams->get('mobilemenuck_logoimage', '')						// the image src
						,'logo_link' => $moduleParams->get('mobilemenuck_logolink', '')						// the link url
						,'logo_alt' => $moduleParams->get('mobilemenuck_logoalt', '')							// the alt tag
						,'logo_position' => $moduleParams->get('mobilemenuck_logoposition', 'left')			// left, center, right
						,'logo_width' => $moduleParams->get('mobilemenuck_logowidth', '')						// image width
						,'logo_height' => $moduleParams->get('mobilemenuck_logoheight', '')					// image height
						,'logo_margintop' => $moduleParams->get('mobilemenuck_logomargintop', '')			// margin top
						,'logo_marginright' => $moduleParams->get('mobilemenuck_logomarginright', '')		// margin right
						,'logo_marginbottom' => $moduleParams->get('mobilemenuck_logomarginbottom', '')		// margin bototm
						,'logo_marginleft' => $moduleParams->get('mobilemenuck_logomarginleft', '')			// margin left
						,'lock_button' => $moduleParams->get('mobilemenuck_lock_button', '0')			
						,'lock_forced' => $moduleParams->get('mobilemenuck_lock_forced', '0')	
						,'topfixedeffect' => $moduleParams->get('mobilemenuck_topfixedeffect', 'always')
						,'accordion_use_effects' => $moduleParams->get('mobilemenuck_accordion_use_effects', '0')
						,'accordion_toggle' => $moduleParams->get('mobilemenuck_accordion_toggle', '0')
						,'show_icons' => $moduleParams->get('mobilemenuck_show_icons', '1')
						,'counter' => $moduleParams->get('mobilemenuck_counter', '0')
						,'hide_desktop' => $moduleParams->get('mobilemenuck_hide_desktop', '1')
						,'overlay' => $moduleParams->get('mobilemenuck_overlay', '0')
						,'custom_position' => $moduleParams->get('mobilemenuck_custom_position', '')
						,'search' => $moduleParams->get('mobilemenuck_search', '0')
					);

				// manage logo options
				$logoOptions = array();
				if ($moduleParams->get('mobilemenuck_logo_source', 'maximenuck') == 'custom') {
					$logoOptions = array(
						'logoimage' => $moduleParams->get('mobilemenuck_logoimage', '')
//						, 'container' => $moduleParams->get('mobilemenuck_container', 'body')
					);
				}

				$options = array_merge($options, $logoOptions);
				loadMobileMenuCK($selector, 
					$options
				);
			} else {
				return;
			}
		}
	}

	public function onAfterRender() {
		if (! $this->shallLoad()) return;

		// exit if in admin
		if (! \Mobilemenuck\CKFof::getApplication()->isClient('site')) return;

		// get the page body
		if (version_compare(JVERSION, '4') >= 0) {
			$body = \Mobilemenuck\CKFof::getApplication()->getBody(); 
		} else {
			$body = JResponse::getBody();
		}
		$html = '';

		if (! empty($this->modulesbar)) {
			$html .= '<div id="mobilemenuck-bar-module" style="display:none;">';
			foreach ($this->modulesbar as $module) {
				$html .= $module;
			}
			$html .= '</div>';
		}
		if (! empty($this->modulestop)) {
			$html .= '<div id="mobilemenuck-top-module" style="display:none;">';
			foreach ($this->modulestop as $module) {
				$html .= $module;
			}
			$html .= '</div>';
		}
		if (! empty($this->modulesbottom)) {
			$html .= '<div id="mobilemenuck-bottom-module" style="display:none;">';
			foreach ($this->modulesbottom as $module) {
				$html .= $module;
			}
			$html .= '</div>';
		}

		if ($html) {
			// add the html code
			$body = str_replace("</body>", $html . "\n</body>", $body);

			if (version_compare(JVERSION, '4') >= 0) {
				$body = \Mobilemenuck\CKFof::getApplication()->setBody($body); 
			} else {
				JResponse::setBody($body);
			}
		}
	}

	public function getModulesFromPosition($name) {
		$modules = \Joomla\CMS\Helper\ModuleHelper::getModules($name);

		return $modules;
	}
	/**
	 * Event launched when the module is rendered in the page
	 *
	 * @param       object   The module element.
	 * @param       array    The attributes to render the module.
	 * @return      void
	 */
	public function onAfterModuleList($modules) {
		$this->callLibraries();
		$app = \Mobilemenuck\CKFof::getApplication();
		$input = $app->input;

		if (! $app->isClient('site')) {
			return;
		}

		// exit if we are in one of these cases
		if ($input->get('option', '', 'string') == 'com_modulesmanagerck' 
			|| $input->get('option', '', 'string') == 'com_media' 
			|| $input->get('option', '', 'string') == 'com_ajax' 
			|| $input->get('format', '', 'string') == 'raw'
			|| $input->get('option', '', 'string') == 'com_config'
			|| $input->get('ck', '', 'string') === '1'
			) {
			return;
		}

		$loadAssets = false;
		foreach ($modules as $module) {
			$moduleParams = new \Mobilemenuck\CKRegistry($module->params);
			// find a module to use
			if ($moduleParams->get('mobilemenuck_enable', 0, 'int')) {
				$loadAssets = true;
				$this->loadAssets($module);
				$this->modules[$module->id] = $module;
			}
		}
		if (! count($this->styles)) return;
		$styles = implode("\n", $this->styles);
		$styles .= '.mobilemenuck-logo { text-align: center; }';
		$styles .= '.mobilemenuck-logo-left { text-align: left; }';
		$styles .= '.mobilemenuck-logo-right { text-align: right; }';
		$styles .= '.mobilemenuck-logo a { display: inline-block; }';
		$doc = \Mobilemenuck\CKFof::getDocument();
		// css
		$doc->addStyleDeclaration($styles);
		foreach ($this->styles as $id => $css) {
			$this->stylesLoaded[] = $id;
		}
	}

	/**
	 * Add a word into a string
	 */
	private function addWord($string, $toggler) {
		$s = explode(' ', $string);
		if (!in_array($toggler, $s)) {
			array_push($s, $toggler);
		}

		$s = implode(' ', $s);
		return $s;
	}

	/**
	 * Remove a word from a string
	 */
	private function removeWord($string, $toggler) {
		$s = explode(' ', $string);
		if (in_array($toggler, $s)) {
			$s = array_diff($s, array($toggler));
		}

		$s = implode(' ', $s);
		return $s;
	}

	/**
	 * Load the scripts and styles
	 */
	protected function loadAssets($module) {
		// loads the helper in any case
		include_once('helpers/helper.php');
		$moduleParams = new \Mobilemenuck\CKRegistry($module->params);
		$doc = \Mobilemenuck\CKFof::getDocument();
		// check if a style has been selected
		$styleid = $moduleParams->get('mobilemenuck_styles');

		if ($this->params->get('include_meta', '0') == '1') {
			$doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');
		}
		if ($moduleParams->get('mobilemenuck_enable') == '1') {
			$id = \Mobilemenuck\Helper::createIdForModule($module);

			// look for the menu items options
			if ($moduleParams->get('mobilemenuck_show_icons', '1') === '1'
					&& $module->module === 'mod_menu') {
				if (version_compare(JVERSION, '4') >= 0) {
					$list = MenuHelper::getList($moduleParams);
				} else {
					$list = ModMenuHelper::getList($moduleParams);
				}

				$iconsList = array();
				foreach ($list as $l) {
					$p = $l->getParams();
					$iconType = $p->get('mobilemenuck_icontype', 'image');
					switch($iconType) {
						case 'svg' :
							$icon = $p->get('mobilemenuck_iconsvg', '');
							break;
						case 'css' :
							$icon = $p->get('mobilemenuck_iconcss', '');
							break;
						case 'image':
						default :
							$icon = $p->get('mobilemenuck_icon', '');
							break;
					}
					$text = $p->get('mobilemenuck_textreplacement', '');
//					if ($icon || $text) {
						$iconsList[$l->id] = array(
							'icon' => $icon
							, 'iconType' => $iconType
							, 'enabled' => $p->get('mobilemenuck_enablemobile', '1')
							, 'text' => htmlspecialchars($text)
						);
//					}
				}
				if (! empty($iconsList)) {
					$this->scripts['iconsList'] = 'var MobilemenuckSettings = MobilemenuckSettings || {};MobilemenuckSettings[\'' . $id . '\'] = ' . json_encode($iconsList);
					$doc->addScriptDeclaration($this->scripts['iconsList']);
				}
			}
			if ($styleid) {
				$styles = $this->getStylesCss($styleid);
				if ($styles->state == '1') {
					$module->mobilemenuck_params = new \Mobilemenuck\CKRegistry($styles->params);
					// add standard css rules
					$layoutcss = '|ID| .mobilemenuck-item-counter {
	display: inline-block;
	margin: 0 5px;
	padding: 10px;
	font-size: 12px;
	line-height: 0;
	background: rgba(0,0,0,0.3);
	color: #eee;
	border-radius: 10px;
	height: 20px;
	transform: translate(10px,-3px);
	box-sizing: border-box;
}

|ID| .mobilemenuck-backbutton svg {
	width: 14px;
	fill: #fff;
	position: relative;
	left: -5px;
	top: -2px;
}
';
					$layoutcss .= $styles->layoutcss;
					\Mobilemenuck\Helper::makeCssReplacement($layoutcss);

					// $fieldcss = str_replace('|ID|', '.mobilemenuck.' . $fieldClass, $fieldcss);
					$layoutcss .= $this->getIconsStyles($moduleParams);
					$layoutcss .= $this->getOverlayStyles($moduleParams);
					$layoutcss .= $this->getSearchStyles($moduleParams);
					$layoutcss = str_replace('|ID|', '[data-id="' . $id . '"]', $layoutcss);
					// $layoutcss = str_replace('|ID|', '', $layoutcss);
					// $fieldcss = str_replace('.fields-container', '.fields-container.' . $fieldClass, $fieldcss);
					// $fieldcss = str_replace('"field-entry"]', '"field-entry"].' . $fieldClass, $fieldcss);
					
					$this->styles[$id] = $layoutcss;

					global $ckcustomgooglefontslist;
					global $ckfontawesomeisloaded;
					// load the Font Awesome library
					if ($moduleParams->get('mobilemenuck_loadfa') == '1' && !$ckfontawesomeisloaded) {
						$doc->addStylesheet(\Mobilemenuck\CKUri::root(true) . '/media/com_mobilemenuck/assets/font-awesome.min.css');
						$ckfontawesomeisloaded = true;
					}
					if ($module->mobilemenuck_params->get('texttextisgfont') && $module->mobilemenuck_params->get('texttextgfont')) {
						$ckcustomgooglefontslist[] = $module->mobilemenuck_params->get('texttextgfont');
		//				$doc->addStylesheet('https://fonts.googleapis.com/css?family=' . $field->mobilemenuck_params->get('texttextgfont'));
					}
					if ($module->mobilemenuck_params->get('titletextisgfont') && $module->mobilemenuck_params->get('titletextgfont')) {
						$ckcustomgooglefontslist[] = $module->mobilemenuck_params->get('titletextgfont');
		//				$doc->addStylesheet('https://fonts.googleapis.com/css?family=' . $field->mobilemenuck_params->get('titletextgfont'));
					}
				}
			} else {
				// $id = 'mobilemenuck-' . $module->id;
				$layoutcss = $this->getLayoutCss();
				\Mobilemenuck\Helper::makeCssReplacement($layoutcss);
				$layoutcss .= $this->getIconsStyles($moduleParams);
				$layoutcss .= $this->getOverlayStyles($moduleParams);
				$layoutcss .= $this->getSearchStyles($moduleParams);
				$layoutcss = str_replace('|ID|', '[data-id="' . $id . '"]', $layoutcss);
				$this->styles[$id] = $layoutcss;
			}
		}
	}

	protected function getIconsStyles($moduleParams) {
		$css = '';
		if ($moduleParams->get('mobilemenuck_show_icons', '1') === '1') {
			$iconWidth = $this->testUnit($moduleParams->get('mobilemenuck_icon_width', '32px'));
			$iconHeight = $this->testUnit($moduleParams->get('mobilemenuck_icon_height', '32px'));
			$iconMargin = $this->testUnit($moduleParams->get('mobilemenuck_icon_margin', '5px'));

			// for images
			$css .='|ID| img.mobilemenuck-icon {
width: ' . $iconWidth . ';
height: ' . $iconHeight . ';
margin: ' . $iconMargin . ';
}';
			// for font icons
			$css .='|ID| i.mobilemenuck-icon {
font-size: ' . $iconWidth . ';
margin: ' . $iconMargin . ';
}';
			// for maximenu icons
			$css .='|ID| .mobilemenuck-item .maximenuiconck {
font-size: ' . $iconWidth . ';
margin: ' . $iconMargin . ';
}';
		}
		return $css;
	}

	public function getOverlayStyles($moduleParams) {
		$css = '';
		if ($moduleParams->get('mobilemenuck_overlay', '0') === '1') {
			$css .='|ID| + .mobilemenuck-overlay {
	position: fixed;
	top: 0;
	background: ' . $moduleParams->get('mobilemenuck_overlay_color', '#000000') . ';
	opacity: ' . $moduleParams->get('mobilemenuck_overlay_opacity', '0.3') . ';
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 9;
}';
		}
		return $css;
	}

	public function getSearchStyles($moduleParams) {
		$css = '';
		
		return $css;
	}

	/**
	 * Get the css rules from the styles
	 *
	 * @param int $id
	 * @return string
	 */
	protected function getStylesCss($id) {
		$db = \Mobilemenuck\CKFof::getDbo();
		$q = "SELECT params,layoutcss,state from #__mobilemenuck_styles WHERE id = " . (int)$id;
		$db->setQuery($q);
		$styles = $db->loadObject();

		return $styles;
	}

	/**
	 * Test if there is already a unit, else add the px
	 *
	 * @param string $value
	 * @return string
	 */
	public static function testUnit($value) {
		if ((stristr($value, 'px')) OR (stristr($value, 'em')) OR (stristr($value, '%')) OR (stristr($value, 'auto')) ) {
			return $value;
		}

		if ($value == '') {
			$value = 0;
		}

		return $value . 'px';
	}

	/**
	 * Check if we need to load the styles in the page
	 */
	public function onBeforeRender() {
		$this->callLibraries();
		// si pas en frontend, on sort
		if (! \Mobilemenuck\CKFof::getApplication()->isClient('site')) return;

		$doc = \Mobilemenuck\CKFof::getDocument();
		$doctype = $doc->getType();
		if ($doctype !== 'html')
		{
			return;
		}

		// load the custom menus  if pro version
		if (\Mobilemenuck\Helper::checkIsProVersion()) $this->loadMobileMenus();

		if (! empty($this->modules)) {
			foreach ($this->modules as $module) {
				$this->loadAssets($module);
			}
			foreach ($this->styles as $id => $css) {
				if (in_array($id, $this->stylesLoaded)) unset($this->styles[$id]);
			}
			$doc = \Mobilemenuck\CKFof::getDocument();
			if (! empty($this->styles)) {
				$styles = implode("\n", $this->styles);
				$styles .= '.mobilemenuck-logo { text-align: center; }';
				$styles .= '.mobilemenuck-logo-left { text-align: left; }';
				$styles .= '.mobilemenuck-logo-right { text-align: right; }';
				$styles .= '.mobilemenuck-logo a { display: inline-block; }';
				
				// css
				$doc->addStyleDeclaration($styles);
				$this->styles = Array();
			}
		}

		if (! empty($this->scripts['iconsList'])) {
			$doc->addScriptDeclaration($this->scripts['iconsList']);
		}

		// try to load the module positions for the mobile menu
		$modulesBar = $this->getModulesFromPosition('mobilemenuck-bar');
		if (! empty($modulesBar)) {
			foreach ($modulesBar as $moduleBar) {
				$attribs = array();
				$attribs['style'] = 'none';

				$this->modulesbar[] = \Joomla\CMS\Helper\ModuleHelper::renderModule($moduleBar, $attribs);
			}
		}

		$modulesTop = $this->getModulesFromPosition('mobilemenuck-top');
		if (! empty($modulesTop)) {
			foreach ($modulesTop as $moduleTop) {
				$attribs = array();
				$attribs['style'] = 'none';

				$this->modulestop[] = \Joomla\CMS\Helper\ModuleHelper::renderModule($moduleTop, $attribs);
			}
		}

		$modulesBottom = $this->getModulesFromPosition('mobilemenuck-bottom');
		if (! empty($modulesBottom)) {
			foreach ($modulesBottom as $moduleBottom) {
				$attribs = array();
				$attribs['style'] = 'none';

				$this->modulesbottom[] = \Joomla\CMS\Helper\ModuleHelper::renderModule($moduleBottom, $attribs);
			}
		}
	}

	/**
	 * Load the fonts only if not already registered by another extension
	 */
	public function loadCustomGoogleFontsList() {
		global $ckcustomgooglefontslist;

		if (! empty($ckcustomgooglefontslist)) {
			$doc = \Mobilemenuck\CKFof::getDocument();
			foreach ($ckcustomgooglefontslist as $ckcustomgooglefont) {
				$doc->addStylesheet('//fonts.googleapis.com/css?family=' . $ckcustomgooglefont);
			}
		}
	}

	private function loadMobileMenus() {
		$db = \Mobilemenuck\CKFof::getDbo();
		$query = $db->getQuery(true)
			->select('*')
			->from('#__mobilemenuck_menus')
			->where('state = 1');

		$db->setQuery($query);
		$menus = $db->loadObjectList();

		if (! empty($menus)) {
			include_once('helpers/helper.php');
			include_once('helpers/menu.php');
			include_once('helpers/function.php');
		}
		foreach ($menus as $menu) {
			if ($menu->params) {
				$params = new \Mobilemenuck\CKRegistry(unserialize($menu->params));
			}

			// if no selector given, continue because the menu can not work 
			if (! $params->get('selector', '')) continue;

			// get the language
			$this->loadLanguage();

			$styleid = $menu->style;
			$id = $params->get('menuid', 'mobilemenuck-' . (int) (microtime(true) * 100));
			if ($styleid) {
				$styles = \Mobilemenuck\Helper::getStyleById($styleid, 'params,layoutcss,state', 'object');
				if ($styles->state == '1') {
//					$module->mobilemenuck_params = new \Mobilemenuck\CKRegistry($styles->params);
					$layoutcss = $styles->layoutcss;
					\Mobilemenuck\Helper::makeCssReplacement($layoutcss);

					// $fieldcss = str_replace('|ID|', '.mobilemenuck.' . $fieldClass, $fieldcss);
//					$layoutcss = str_replace('|ID|', '[data-id="' . $id . '"]', $layoutcss);
					// $layoutcss = str_replace('|ID|', '', $layoutcss);
					// $fieldcss = str_replace('.fields-container', '.fields-container.' . $fieldClass, $fieldcss);
					// $fieldcss = str_replace('"field-entry"]', '"field-entry"].' . $fieldClass, $fieldcss);

//					$this->styles[$id] = $layoutcss;
					include_once('helpers/' . MOBILEMENUCK_PLATFORM . '/loader.php');
//					\Mobilemenuck\CKLoader::loadStyleDeclaration($layoutcss);
				}
			} else {
				$layoutcss = $this->getLayoutCss();
				\Mobilemenuck\Helper::makeCssReplacement($layoutcss);
//				$layoutcss = str_replace('|ID|', '[data-id="' . $id . '"]', $layoutcss);
				// $this->styles[$id] = $layoutcss;
				include_once('helpers/' . MOBILEMENUCK_PLATFORM . '/loader.php');
//				\Mobilemenuck\CKLoader::loadStyleDeclaration($layoutcss);
			}
			$layoutcss .= $this->getSearchStyles($params);
			$layoutcss = str_replace('|ID|', '[data-id="' . $id . '"]', $layoutcss);
				\Mobilemenuck\CKLoader::loadStyleDeclaration($layoutcss);

			// create a unique ID for the menu
			// $menuid = 'mobilemenuck-' . (int) (microtime(true) * 100);
			$menubarbuttoncontent = '&#x2261;';
			$topbarbuttoncontent = '×';
			if ($styleid) {
				// $styleParams = json_decode(\Mobilemenuck\Helper::getStyleById($styleid, 'a.params'));

				// $menubarbuttoncontent = \Mobilemenuck\Menu::getButtonContent($styleParams->menubarbuttoncontent, $styleParams);
				// $topbarbuttoncontent = \Mobilemenuck\Menu::getButtonContent($styleParams->topbarbuttoncontent, $styleParams);
			}

			\Mobilemenuck\Menu::load($params->get('selector', ''), 
				array(
					'menuid' => $id
					,'menubarbuttoncontent' => $menubarbuttoncontent
					,'topbarbuttoncontent' => $topbarbuttoncontent
					 ,'showmobilemenutext' => $params->get('showmobilemenutext', 'default')
					 ,'mobilemenutext' => \Mobilemenuck\CKText::_($params->get('mobilemenutext', 'PLG_MOBILEMENUCK_MENU'))
					 ,'container' => $params->get('container', 'body')
					 ,'detectiontype' => $params->get('detectiontype', 'resolution')
					 ,'resolution' => $params->get('resolution', '640')
					 ,'usemodules' => $params->get('usemodules', '0')
					 ,'useimages' => $params->get('useimages', '0')
					 ,'showlogo' => $params->get('showlogo', '1')
					 ,'showdesc' => $params->get('showdesc', '0')
					 ,'displaytype' => $params->get('displaytype', 'accordion')
					 ,'displayeffect' => $params->get('displayeffect', 'normal')
					 ,'menuwidth' => $params->get('menuwidth', '300')
					 ,'openedonactiveitem' => $params->get('openedonactiveitem', '0')
					 ,'mobilebackbuttontext' => \Mobilemenuck\CKText::_($params->get('mobilebackbuttontext', 'PLG_MOBILEMENUCK_MOBILEBACKBUTTON'))
					 ,'menuselector' => $params->get('menuselector', 'ul')
					 ,'langdirection' => \Mobilemenuck\CKFof::getDocument()->getDirection()
					 ,'childselector' => $params->get('childselector', 'li')
					 ,'beforetext' => addslashes($params->get('beforetext', ''))
					 ,'aftertext' => addslashes($params->get('aftertext', ''))
//					 // Logo options
					,'logo_where' => implode(',', $params->get('logo_where', array(0 => '1'), 'array'))	// 1, 2, 3
					,'logo_source' => $params->get('logo_source', 'maximenuck')	// maximenuck, custom
					,'logo_image' => $params->get('logoimage', '')				// the image src
					,'logo_link' => $params->get('logolink', '')				// the link url
					,'logo_alt' => $params->get('logoalt', '')				// the alt tag
					,'logo_position' => $params->get('logoposition', 'left')		// left, center, right
					,'logo_width' => $params->get('logowidth', '')				// image width
					,'logo_height' => $params->get('logoheight', '')				// image height
					,'logo_margintop' => $params->get('logomargintop', '')			// margin top
					,'logo_marginright' => $params->get('logomarginright', '')		// margin right
					,'logo_marginbottom' => $params->get('logomarginbottom', '')		// margin bototm
					,'logo_marginleft' => $params->get('logomarginleft', '')		// margin left
					,'lock_button' => $params->get('lock_button', '0')
					,'lock_forced' => $params->get('lock_forced', '0')
					,'topfixedeffect' => $params->get('topfixedeffect', 'always')
					,'accordion_use_effects' => $params->get('accordion_use_effects', '0')
					,'accordion_toggle' => $params->get('accordion_toggle', '0')
					,'show_icons' => $params->get('show_icons', '1')
					,'counter' => $params->get('counter', '0')
					,'hide_desktop' => $params->get('hide_desktop', '1')
					,'overlay' => $params->get('overlay', '0')
					,'search' => $params->get('search', '0')
					,'custom_position' => $params->get('custom_position', '')
				)
			);
		}
	}

	public static function getLayoutCss() {
		$doc = \Mobilemenuck\CKFof::getDocument();
		$overrideSrc = null;
		if (isset($doc->template) && $doc->template) {
			$template = $doc->template;
		} else {
			$db = \Joomla\CMS\Factory::getDBO();
			$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
			$db->setQuery($query);
			$template = $db->loadResult();
		}
		$overrideSrc = JPATH_ROOT . '/templates/' . $template . '/css/mobilemenuck.css';
		$overrideSrc2 = JPATH_ROOT . '/media/templates/site/' . $template . '/css/mobilemenuck.css';
		if (file_exists($overrideSrc)) {
			$layoutcss = file_get_contents($overrideSrc);
		} else if (file_exists($overrideSrc2)) {
			$layoutcss = file_get_contents($overrideSrc2);
		} else {
			$layoutcss = file_get_contents(MOBILEMENUCK_PATH . '/default.txt');
		}

		return $layoutcss;
	}
}