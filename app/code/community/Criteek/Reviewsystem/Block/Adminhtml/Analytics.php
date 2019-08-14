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
 
class Criteek_Reviewsystem_Block_Adminhtml_Analytics extends Mage_Adminhtml_Block_Widget_Grid_Container {

	public function __construct() {
		 $this->_blockGroup = 'reviewsystem';
         $this->_controller = 'adminhtml_analytics';
		 $this->_headerText = Mage::helper('reviewsystem')->__('Email Analytics');
		 parent::__construct();
		 $this->_removeButton('add');
}
}