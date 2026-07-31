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

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\Component\Content\Administrator\Extension\ContentComponent;
use Joomla\Component\Content\Site\Helper\RouteHelper;

$com_path = JPATH_SITE.'/components/com_content/';
// require_once $com_path.'router.php';
// require_once $com_path.'helpers/route.php';

include_once dirname(__FILE__).'/base.php';

class OlArticleShowHelper extends BaseHelper
{
	public static function getList(&$params)
	{
		$app = Factory::getApplication();

		/** @var \Joomla\Component\Content\Site\Model\ArticlesModel $model */
		$model = $app->bootComponent('com_content')
			->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);

		// Set application parameters in model
		$appParams = $app->getParams();
		$model->setState('params', $appParams);

		$model->setState('list.start', 0);
		$model->setState('filter.published', 1);
		$model->setState('filter.condition', ContentComponent::CONDITION_PUBLISHED);

		// Set the filters based on the module params
		$model->setState('list.limit', (int) $params->get('count', 5));

		// This module does not use tags data
		$model->setState('load_tags', false);

		// Access filter
		$access     = !ComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = Access::getAuthorisedViewLevels(Factory::getUser()->get('id'));
		$model->setState('filter.access', $access);

		// Category filter
		$model->setState('filter.category_id', $params->get('catid', array()));

		// Filter by language
		$model->setState('filter.language', $app->getLanguageFilter());

		// Filer by tag
		$model->setState('filter.tag', $params->get('tag', array()));

		// Featured switch
		$featured = $params->get('show_front', '');

		if ($featured === 'show')
		{
			$model->setState('filter.featured', 'show');
		}
		elseif ($featured === 'only')
		{
			$model->setState('filter.featured', 'only');
		}
		else
		{
			$model->setState('filter.featured', 'hide');
		}

		// Filter by id in case it should be excluded
		if ($params->get('exclude_current', true)
			&& $app->input->get('option') === 'com_content'
			&& $app->input->get('view') === 'article')
		{
			// Exclude the current article from displaying in this module
			$model->setState('filter.article_id', $app->input->get('id', 0, 'UINT'));
			$model->setState('filter.article_id.include', false);
		}

		// Set ordering
		$ordering = $params->get('article_ordering', 'a.publish_up');
		$model->setState('list.ordering', $ordering);

		if (trim($ordering) === 'rand()')
		{
			$model->setState('list.ordering', Factory::getDbo()->getQuery(true)->rand());
		}
		else
		{
			$direction = $params->get('article_ordering_direction', 'ASC') ? 'DESC' : 'ASC';
			$model->setState('list.direction', $params->get('article_ordering_direction', 'ASC'));
			$model->setState('list.ordering', $ordering);
		}

		// Check if we should trigger additional plugin events
		$triggerEvents = $params->get('triggerevents', 1);

		// Retrieve Content
		$items = $model->getItems();

		foreach ($items as &$item)
		{
			$item->readmore = \strlen(trim($item->fulltext));
			$item->slug     = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid ? $item->catid .':'.$item->category_alias : $item->catid;
			$item->catlink = Route::_( RouteHelper::getCategoryRoute($item->catslug) );

			if ($access || \in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link     = Route::_(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language));
				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE');
			}
			else
			{
				$item->link = new Uri(Route::_('index.php?option=com_users&view=login', false));
				$item->link->setVar('return', base64_encode(RouteHelper::getArticleRoute($item->slug, $item->catid, $item->language)));
				$item->linkText = Text::_('MOD_ARTICLES_NEWS_READMORE_REGISTER');
			}

			$item->introtext = HTMLHelper::_('content.prepare', $item->introtext, '', 'mod_articles_news.content');

			// Show the Intro/Full image field of the article
			if ($params->get('img_intro_full') !== 'none')
			{
				$images = json_decode($item->images);
				$item->imageSrc = '';
				$item->imageAlt = '';
				$item->imageCaption = '';

				if ($params->get('img_intro_full') === 'intro' && !empty($images->image_intro))
				{
					$item->imageSrc = htmlspecialchars($images->image_intro, ENT_COMPAT, 'UTF-8');
					$item->imageAlt = htmlspecialchars($images->image_intro_alt, ENT_COMPAT, 'UTF-8');

					if ($images->image_intro_caption)
					{
						$item->imageCaption = htmlspecialchars($images->image_intro_caption, ENT_COMPAT, 'UTF-8');
					}
				}
				elseif ($params->get('img_intro_full') === 'full' && !empty($images->image_fulltext))
				{
					$item->imageSrc = htmlspecialchars($images->image_fulltext, ENT_COMPAT, 'UTF-8');
					$item->imageAlt = htmlspecialchars($images->image_fulltext_alt, ENT_COMPAT, 'UTF-8');

					if ($images->image_intro_caption)
					{
						$item->imageCaption = htmlspecialchars($images->image_fulltext_caption, ENT_COMPAT, 'UTF-8');
					}
				}
			}

			if ($triggerEvents)
			{
				$item->text = '';
				$app->triggerEvent('onContentPrepare', array('com_content.article', &$item, &$params, 0));

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
		}

		return $items;
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
