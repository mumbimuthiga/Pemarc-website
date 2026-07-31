<?php
/**
* @title			mod_ol_articleshow
* @version   		3.0.1
* @copyright   		Copyright (C) 2021 olwebdesign.com, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.olwebdesign.com/
* @developers   	olwebdesign.com
*/

defined('_JEXEC') or die;

$com_path = JPATH_SITE.'/components/com_content/';
require_once $com_path.'router.php';
require_once $com_path.'helpers/route.php';
if(!class_exists('JModelLegacy')){
	class JModelLegacy extends JModel{
	}
}
JModelLegacy::addIncludePath($com_path . '/models', 'ContentModel');
include_once dirname(__FILE__).'/base.php';

class OlArticleShowHelper extends BaseHelper
{
	public static function getList(&$params)
	{
		$db = JFactory::getDbo();
		// Get an instance of the generic articles model
		$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		// Set application parameters in model
		
		$articles->setState(
				'list.select',
				'a.id, a.title, a.alias, a.introtext, a.fulltext, ' .
				'a.checked_out, a.checked_out_time, ' .
				'a.catid, a.created, a.created_by, a.created_by_alias, ' .
				// use created if modified is 0
				'CASE WHEN a.modified = ' . $db->q($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
				'a.modified_by, uam.name as modified_by_name,' .
				// use created if publish_up is 0
				'CASE WHEN a.publish_up = ' . $db->q($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END as publish_up,' .
				'a.publish_down, a.images, a.urls, a.attribs, a.metadata, a.metakey, a.metadesc, a.access, ' .
				'a.hits, a.xreference, a.featured'
		);
				
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		
		$articles->setState('params', $appParams);
		// Set the filters based on the module params
		$articles->setState('list.start', 0);
		$articles->setState('list.limit', (int) $params->get('count', 0));
		$articles->setState('filter.published', 1);
		
		// This module does not use tags data
		$articles->setState('load_tags', false);
		// Filer by tag
		$articles->setState('filter.tag', $params->get('tag', array()));

		// Access filter
		$access = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$articles->setState('filter.access', $access);
		
		// Category filter
		$catids = $params->get('catid');
		if ($catids != null) {
			if ($params->get('show_child_category_articles', 0) && (int) $params->get('levels', 0) > 0) {
				// Get an instance of the generic categories model
				$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
				$categories->setState('params', $appParams);
				$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
				$categories->setState('filter.get_children', $levels);
				$categories->setState('filter.published', 1);
				$categories->setState('filter.access', $access);
				$additional_catids = array();
			
				foreach($catids as $catid)
				{
					$categories->setState('filter.parentId', $catid);
					$recursive = true;
					$items = $categories->getItems($recursive);
			
					if ($items)
					{
						foreach($items as $category)
						{
							$condition = (($category->level - $categories->getParent()->level) <= $levels);
							if ($condition) {
								$additional_catids[] = $category->id;
							}
			
						}
					}
				}
			
				$catids = array_unique(array_merge($catids, $additional_catids));
			}
			$articles->setState('filter.category_id', $catids);
		
		// Ordering
		$articles->setState('list.ordering', $params->get('article_ordering', 'a.ordering'));
		$articles->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));

// 		// New Parameters
		$articles->setState('filter.featured', $params->get('show_front', 'show'));

		// Filter by language
		$articles->setState('filter.language', $app->getLanguageFilter());
		
		// Check if we should trigger additional plugin events
		$triggerEvents = $params->get('triggerevents', 1);
		
		$items = $articles->getItems();

		// Find current Article ID if on an article page
		$option = $app->input->get('option');
		$view = $app->input->get('view');

		if ($option === 'com_content' && $view === 'article') {
			$active_article_id = $app->input->getInt('id');
		}
		else {
			$active_article_id = 0;
		}

		// Prepare data for display using display options
		foreach ($items as &$item)
		{
			$item->slug = $item->id.':'.$item->alias;
			$item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;
			$item->catlink = JRoute::_( ContentHelperRoute::getCategoryRoute($item->catslug) );
			if ($triggerEvents)
			{
				$item->text = '';
				$app->triggerEvent('onContentPrepare', array ('com_content.article', &$item, &$params, 0));

				$results                 = $app->triggerEvent('onContentAfterTitle', array('com_content.article', &$item, &$params, 0));
				$item->afterDisplayTitle = trim(implode("\n", $results));

				$results                    = $app->triggerEvent('onContentBeforeDisplay', array('com_content.article', &$item, &$params, 0));
				$item->beforeDisplayContent = trim(implode("\n", $results));

				$results                   = $app->triggerEvent('onContentAfterDisplay', array('com_content.article', &$item, &$params, 0));
				$item->afterDisplayContent = trim(implode("\n", $results));
			}
			else
			{
				$item->afterDisplayTitle    = '';
				$item->beforeDisplayContent = '';
				$item->afterDisplayContent  = '';
			}			
			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			else
			{
				$app  = JFactory::getApplication();
				$menu = $app->getMenu();
				$menuitems = $menu->getItems('link', 'index.php?option=com_users&view=login');
				if (isset($menuitems[0]))
				{
					$Itemid = $menuitems[0]->id;
				}
				elseif ($app->input->getInt('Itemid') > 0)
				{
					// Use Itemid from requesting page only if there is no existing menu
					$Itemid = $app->input->getInt('Itemid');
				}
				$item->link = JRoute::_('index.php?option=com_users&view=login&Itemid='.$Itemid);
			}
			self::getAImages($item, $params);
			$item->introtext = self::_cleanText($item->introtext);
		}
		return $items;
		}
	}

	public static function groupBy($list, $fieldName, $article_grouping_direction, $fieldNameToKeep = null)
	{
		$grouped = array();
	
		if (!is_array($list)) {
			if ($list == '') {
				return $grouped;
			}
	
			$list = array($list);
		}
	
		foreach($list as $key => $item)
		{
			if (!isset($grouped[$item->$fieldName])) {
				$grouped[$item->$fieldName] = array();
			}
	
			if (is_null($fieldNameToKeep)) {
				$grouped[$item->$fieldName][$key] = $item;
			}
			else {
				$grouped[$item->$fieldName][$key] = $item->$fieldNameToKeep;
			}
	
			unset($list[$key]);
		}
	
		$article_grouping_direction($grouped);
	
		return $grouped;
	}
	
	public static function groupByDate($list, $type, $article_grouping_direction, $month_year_format = 'F Y')
	{
		$grouped = array();
	
		if (!is_array($list)) {
			if ($list == '') {
				return $grouped;
			}
	
			$list = array($list);
		}
	
		foreach($list as $key => $item)
		{
			switch($type)
			{
				case 'month_year':
					$month_year = JString::substr($item->created, 0, 7);
	
					if (!isset($grouped[$month_year])) {
						$grouped[$month_year] = array();
					}
	
					$grouped[$month_year][$key] = $item;
					break;
	
				case 'year':
				default:
					$year = JString::substr($item->created, 0, 4);
	
					if (!isset($grouped[$year])) {
						$grouped[$year] = array();
					}
	
					$grouped[$year][$key] = $item;
					break;
			}
	
			unset($list[$key]);
		}
	
		$article_grouping_direction($grouped);
	
		if ($type === 'month_year') {
			foreach($grouped as $group => $items)
			{
				$date = new JDate($group);
				$formatted_group = $date->format($month_year_format);
				$grouped[$formatted_group] = $items;
				unset($grouped[$group]);
			}
		}
	
		return $grouped;
	}
	
}
