<?php
/**
 *
 * NOTICE OF LICENSE
 *
 *
 * @category   Criteek
 * @package    Criteek_Reviewsystem
 * @copyright  Copyright (c) 2016 Criteek Comm LLC.
 * @author	   Criteek Developer
 * @license    https://www.criteek.tv/terms-of-use CRITEEK TERMS OF USE
 */
      $installer = $this;
		$categories = Mage::getModel('catalog/category')->getCollection()
    ->addAttributeToSelect('*')//or you can just add some attributes
    ->addAttributeToFilter('level', 2)//2 is actually the first level
    ->addAttributeToFilter('is_active', 1)//if you want only active categories
; 
     $interval=10;
	
	foreach(  $categories as $category) {
		 $model = Mage::getModel('reviewsystem/category');
		$model->setCategoryId($category->getId())
					->setInterval($interval)
					->save();
	}
	
?>