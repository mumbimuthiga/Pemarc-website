<?php
/*
 *  @package Module UserWay for Joomla! 3.10.3
 *  @version userway.php: 199 you radik
 *  @author UserWay Development Team
 *  @copyright (C) 2021 - UserWay Inc.
 *  @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die('No direct access');
jimport('joomla.plugin.plugin');

class plgSystemUserway extends JPlugin
{

  public function __construct(&$subject, $config)
  {
    parent::__construct($subject, $config);
  }

  function onBeforeCompileHead()
  {

    try {
      $db = JFactory::getDbo();
      $selectQuery = $db->getQuery(true);
      $selectQuery->select($db->quoteName(array('account_id', 'state')))
          ->from($db->quoteName('#__userway'));

      $db->setQuery($selectQuery);
      $db->query();
      $entry = $db->loadObject();

      if (is_null($entry) || !$entry->state) {
        return;
      }

      JHtml::script('https://cdn.userway.org/widget.js?account=' . $entry->account_id);
    } catch (Exception $e) {

    }
  }
}
