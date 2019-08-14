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
 
class Criteek_Reviewsystem_Block_Adminhtml_Category_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
   public function __construct()
    {
        parent::__construct();
 
        $this->_objectId = 'id';
        $this->_blockGroup = 'reviewsystem';
        $this->_controller = 'adminhtml_category';
        $this->_mode = 'edit';
		$this->_removeButton('delete');
        $this->_updateButton('save', 'label', Mage::helper('reviewsystem')->__('Save Interval for Category'));
 
    
    }
 
    public function getHeaderText()
    {   $categoryId =$this->getRequest()->getParam('id');

        if ($categoryId )
        { $category=Mage::getModel('catalog/category')->load($categoryId);
            return Mage::helper('reviewsystem')->__('Set Interval for category "%s"', $this->htmlEscape($category->getName()));
        } else {
            return Mage::helper('reviewsystem')->__('Set Interval for category');
        }
    }
	

    
}