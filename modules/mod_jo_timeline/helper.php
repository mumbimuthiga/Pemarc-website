<?php
/**
 * @package     JO Timeline
 * @subpackage  mod_jo_timeline
 *
 * @copyright   Copyright (C) 2025 Your Name. All rights reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 */
 
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;

class ModJoTimelineHelper
{
    public static function getTimelineData($params)
    {
        $source = $params->get('source', 'custom');

        if ($source === 'custom') {
            // Return custom entries if source is "Custom"
            return self::getCustomEntries($params);
        } elseif ($source === 'articles') {
            // Return Joomla articles if source is "Articles"
            return self::getArticles($params);
        }

        return [];
    }

    public static function getCustomEntries($params)
    {
        $customEntries = $params->get('custom_entries', []);
        $timelineData = [];

        foreach ($customEntries as $entry) {
            // Access the properties of the entry as object properties
            $timelineData[] = [
                'time' => $entry->time,
                'title' => $entry->title,
                'description' => $entry->description,
                'image' => $entry->image,
            ];
        }

        return $timelineData;
    }

public static function getArticles($params)
{
    $db = Factory::getDbo();
    $query = $db->getQuery(true);

    // Select fields
    $query->select($db->quoteName([
        'id',
        'title',
        'introtext',
        'fulltext',
        'publish_up',
        'images',
    ]));

    // From the articles table
    $query->from($db->quoteName('#__content'));

    // Filter by category
    $catids = $params->get('catid', []);
    if (!empty($catids)) {
        $query->where($db->quoteName('catid') . ' IN (' . implode(',', $catids) . ')');
    }

    // Filter by state (published articles only)
    $query->where($db->quoteName('state') . ' = 1');

    // Get the selected ordering and order direction
    $ordering = $params->get('ordering', 'p_dsc');
    $articlesOrder = $params->get('articles_order', 'desc'); // Default is DESC

    // Determine the order direction
    $orderDirection = strtoupper($articlesOrder); // Ensure it's uppercase (ASC or DESC)

    // Apply the ordering based on the selected option
    switch ($ordering) {
        case 'c_dsc':
            $query->order($db->quoteName('created') . ' ' . $orderDirection);
            break;
        case 'm_dsc':
            $query->order($db->quoteName('modified') . ' ' . $orderDirection);
            break;
        case 'p_dsc':
            $query->order($db->quoteName('publish_up') . ' ' . $orderDirection);
            break;
        case 'random':
            $query->order('RAND()'); // Random order ignores ASC/DESC
            break;
    }

    // Limit the number of articles
    $count = $params->get('count', 5);
    $query->setLimit($count);

    // Execute the query
    $db->setQuery($query);
    $articles = $db->loadObjectList();

    // Get the default image path
    $defaultImage = $params->get('default_image', '');

    // Get the date-time format (default is Y-m-d H:i)
    $dateFormat = $params->get('date_format', 'Y-m-d H:i');

    // Format the data for the timeline
    $timelineData = [];
    foreach ($articles as $article) {
        // Parse the images field
        $images = json_decode($article->images);
        $image = isset($images->image_fulltext) && !empty($images->image_fulltext)
            ? $images->image_fulltext
            : '';

        // Use the default image if no article image is available
        if (empty($image) && !empty($defaultImage)) {
            $image = Uri::root(true) . '/' . $defaultImage; // Add root path to the default image
        }

        // Format the publish date using the user-defined date-time format
        $formattedTime = HTMLHelper::_('date', $article->publish_up, $dateFormat);

        $timelineData[] = [
            'time' => $formattedTime,
            'title' => $article->title,
            'description' => $article->fulltext ?: $article->introtext,
            'image' => $image,
        ];
    }

    return $timelineData;
}
}