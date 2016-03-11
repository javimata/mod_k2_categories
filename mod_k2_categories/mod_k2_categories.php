<?php
/**
 * @version		1.0.x
 * @package		Categories for K2
 * @author		@Javi_Mata http://www.javimata.com
 * @copyright	Copyright (c) 2016 - All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die ;

if (K2_JVERSION != '15')
{
    $language = JFactory::getLanguage();
    $language->load('mod_k2.j16', JPATH_ADMINISTRATOR, null, true);
}

require_once (dirname(__FILE__).DS.'helper.php');

// Params
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
$getTemplate = $params->get('getTemplate', 'Default');

// Get component params
$componentParams = JComponentHelper::getParams('com_k2');


$items = modK2CategoriesHelper::getItems($params);

if (count($items))
{
    require (JModuleHelper::getLayoutPath('mod_k2_categories', $getTemplate.DS.'default'));
}
