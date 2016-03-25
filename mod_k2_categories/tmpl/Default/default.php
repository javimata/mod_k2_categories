<?php
/**
 * @version		1.0.1
 * @package		Categories for K2
 * @author		@Javi_Mata http://www.javimata.com
 * @copyright	Copyright (c) 2016 - All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;
?>

<?php

	$cols = $params->get("colsCount",4);
	$classList ="";

	switch ($params->get('listType'))
	{

		case 'div' :
			$ancho_cada  = 100 / $cols;
			$inicia      = '<div>';
			$cierra      = '</div>';
			$cada_inicia = '<div style="width:' . round ($ancho_cada) . '%" class="itemList [CLASSLIST]">';
			$cada_cierra = '</div>';
			break;

		case 'bootstrap' :
			$ancho_cada  = 12 / $cols;
			$inicia      = '<div class="row">';
			$cierra      = '</div>';
			$cada_inicia = '<div class="col-sm-6 col-md-' . round ($ancho_cada) . ' itemList [CLASSLIST]">';
			$cada_cierra = '</div>';
			break;

		default :
			$inicia      = '<ul class="listCategories">';
			$cierra      = '</ul>';
			$cada_inicia = '<li style="width:' . round ($ancho_cada) . '%" class="itemList [CLASSLIST]">';
			$cada_cierra = '</li>';
			break;
	}
?>


  <?php echo $inicia; ?>

    <?php foreach ($items as $key=>$item):	?>

    	<?php
    	$classList = ($key%2) ? "odd" : "even"; 
    	if(count($items)==$key+1) $classList .= ' lastItem';
    	?>

    	<?php echo str_replace("[CLASSLIST]", $classList, $cada_inicia); ?>

			<?php if($params->get('itemTitle')!=0): ?>
			<div class="itemTitle">
				<?php if ($params->get('itemTitle')==2): ?>
				<a class="linkItemTitle" href="<?php echo $item->link; ?>">
					<?php echo $item->name; ?>
				</a>
				<?php else: ?>
					<?php echo $item->name; ?>					
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if($params->get('itemImage') || $params->get('itemIntroText')): ?>
			<div class="ItemIntrotext">
				<?php if($params->get('itemImage') && isset($item->image)): ?>
				<a class="moduleItemImage" href="<?php echo $item->link; ?>" title="<?php echo JText::_('K2_CONTINUE_READING'); ?> &quot;<?php echo K2HelperUtilities::cleanHtml($item->name); ?>&quot;">
				<img src="<?php echo $item->image; ?>" alt="<?php echo K2HelperUtilities::cleanHtml($item->name); ?>"/>
				</a>
				<?php endif; ?>

				<?php if($params->get('itemIntroText')): ?>
				<?php echo $item->introtext; ?>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if($params->get('itemReadMore')): ?>
			<a class="ItemReadMore" href="<?php echo $item->link; ?>">
				<?php echo JText::_('K2_READ_MORE'); ?>
			</a>
			<?php endif; ?>

    	<?php echo $cada_cierra; ?>
    <?php endforeach; ?>
  
  <?php echo $cierra; ?>
