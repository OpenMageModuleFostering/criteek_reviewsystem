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
 

class Criteek_Reviewsystem_Block_Adminhtml_Category extends Mage_Adminhtml_Block_Widget_Grid_Container {

	/**
	 * constructor
	 *
	 */
	public function __construct() {
		$this->_controller = 'adminhtml_category';
		$this->_blockGroup = 'reviewsystem';
		$this->_headerText = 'Top Level Categories';
			$data = array(
               'label' =>  'Back',
               'onclick'   => "goToParent('".$this->getUrl('*/*/grid',  array('_current'=>false))."')",
               'id' => "back-to-parent",
			   				//'class'     =>  'back'
               );
		parent::__construct();
		$this->_removeButton('add');
		$this->_removeButton('back');
		
	}
}