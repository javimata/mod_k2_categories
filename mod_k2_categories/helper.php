<?php
/**
 * @version		1.0.x
 * @package		Categories for K2
 * @author		@Javi_Mata http://www.javimata.com
 * @copyright	Copyright (c) 2016 - All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'route.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'helpers'.DS.'utilities.php');

class modK2CategoriesHelper
{

	public static function getItems(&$params, $format = 'html')
	{

		jimport('joomla.filesystem.file');
		$mainframe = JFactory::getApplication();
		$limit = $params->get('itemCount', 5);
		$cid = $params->get('category_id', NULL);
		$ordering = $params->get('itemsOrdering', '');
		$componentParams = JComponentHelper::getParams('com_k2');
		$limitstart = JRequest::getInt('limitstart');

		$user = JFactory::getUser();
		$aid = $user->get('aid');
		$db = JFactory::getDBO();

		$jnow = JFactory::getDate();
		$now =  K2_JVERSION == '15'?$jnow->toMySQL():$jnow->toSql();
		$nullDate = $db->getNullDate();


		// Inicia Query
		$query  = "SELECT c.*";
		$query .= " FROM #__k2_categories as c";
		$query .= " WHERE c.published = 1";

		if (!is_null($cid))
		{
			if (is_array($cid))
			{
				$count = 0;
				foreach ($cid as $cat) {
					if ( $count >= 1 ) { $sep = " OR "; } else { $sep = " AND "; }
					$query .= $sep ."c.id = " . $cat;
					$count++;
				}

			}
			else
			{
				$query .= " AND c.id = " . $cat;
			}
		}


		switch ($ordering)
		{

			case 'date' :
				$orderby = 'c.id ASC';
				break;

			case 'name' :
				$orderby = 'c.name ASC';
				break;


			case 'order' :
				$orderby = 'c.ordering ASC';
				break;

			case 'rand' :
				$orderby = 'RAND()';
				break;

			default :
				$orderby = 'c.id DESC';
				break;
		}

		$query .= " ORDER BY ".$orderby;
		$db->setQuery($query, 0, $limit);
		$items = $db->loadObjectList();

		$model = K2Model::getInstance('Item', 'K2Model');

		if (count($items))
		{

			foreach ($items as $item)
			{
			    $item->event = new stdClass;

				//Clean title
				$item->title = JFilterOutput::ampReplace($item->title);

				//Images
				if ($params->get('itemImage'))
				{

					$date = JFactory::getDate($item->modified);
					$timestamp = '?t='.$date->toUnix();

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XS.jpg'))
					{
						$item->imageXSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XS.jpg';
						if ($componentParams->get('imageTimestamp'))
						{
							$item->imageXSmall .= $timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_S.jpg'))
					{
						$item->imageSmall = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_S.jpg';
						if ($componentParams->get('imageTimestamp'))
						{
							$item->imageSmall .= $timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_M.jpg'))
					{
						$item->imageMedium = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_M.jpg';
						if ($componentParams->get('imageTimestamp'))
						{
							$item->imageMedium .= $timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_L.jpg'))
					{
						$item->imageLarge = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_L.jpg';
						if ($componentParams->get('imageTimestamp'))
						{
							$item->imageLarge .= $timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_XL.jpg'))
					{
						$item->imageXLarge = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_XL.jpg';
						if ($componentParams->get('imageTimestamp'))
						{
							$item->imageXLarge .= $timestamp;
						}
					}

					if (JFile::exists(JPATH_SITE.DS.'media'.DS.'k2'.DS.'items'.DS.'cache'.DS.md5("Image".$item->id).'_Generic.jpg'))
					{
						$item->imageGeneric = JURI::base(true).'/media/k2/items/cache/'.md5("Image".$item->id).'_Generic.jpg';
						if ($componentParams->get('imageTimestamp'))
						{
							$item->imageGeneric .= $timestamp;
						}
					}

					$image = 'image'.$params->get('itemImgSize', 'Small');
					if (isset($item->$image))
						$item->image = $item->$image;

				}

				//Category link
				// $item->categoryLink = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->id)));
				$item->link = urldecode(JRoute::_(K2HelperRoute::getCategoryRoute($item->id.':'.urlencode($item->alias))));

				// Introtext
				$item->text = '';
				if ($params->get('itemIntroText'))
				{
					// Word limit
					$item->text .= $item->description;
				}


                // Restore the intotext variable after plugins execution
                $item->introtext = $item->text;


				$rows[] = $item;
			}
			return $rows;

		}

	}

}
