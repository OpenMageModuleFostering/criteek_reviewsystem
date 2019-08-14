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

class Criteek_Reviewsystem_Block_Adminhtml_Category_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
	/**
  	 * Constructor
  	 *
  	 * @return unknown
  	 */
	public function __construct()
	{     
		parent::__construct();
		
		$this->setId('category_grid');
		$this->setUseAjax(true); 
		$this->setPagerVisibility(true);
        $this->setFilterVisibility(false);  
	}
	
	/**
	 * prepare collection for grid
	 *
	 */
	protected function _prepareCollection()
	{  
	
		$collection = new Varien_Data_Collection();
		
		$helper = Mage::helper('reviewsystem');

	 $model = Mage::getModel('reviewsystem/category')->getCollection();

		if($model){

			foreach(  $model as $category) {
				$categoryCollection=Mage::getModel('catalog/category')->load($category->getCategoryId());
				//$main_cat_title[] = $main_cat['title']; // Main Category title
				$rowObj = new Varien_Object();
				$rowObj->setName($categoryCollection->getName());
	
				$rowObj->setId($category->getCategoryId());
				$rowObj->setCategoryinterval($category->getInterval());
				$collection->addItem($rowObj);
			}
		}
		
		$this->setCollection($collection);
		return parent::_prepareCollection();	
	}
	
	/**
	 * prepare columns for grid
	 *
	 * @return object
	 */
	protected function _prepareColumns()
	{

		
		
				$this->addColumn('id', array(
				'header' => Mage::helper('reviewsystem')->__('Top Category Id'),
				'index' => 'id',	
		));
		$this->addColumn('name', array(
			'header' => Mage::helper('reviewsystem')->__('Top Category Name'),
			'index' => 'name',	
		));
	$this->addColumn('categoryinterval', array(
			'header' => Mage::helper('reviewsystem')->__('Interval'),
			'index' => 'categoryinterval',	
		));
		$this->addColumn('interval',array(
			'header'    => Mage::helper('reviewsystem')->__('edit'),
			'type'      => 'action', 
			'getter'     => 'getId', 
			'actions'   => array(
			array(
				'caption' => Mage::helper('reviewsystem')->__('edit'),
				'url'     => array(
				'base'=>'*/*/edit'),
				'field'   => 'id',
				
			)),
			'filter'    => false,
			'sortable'  => false,
			'index'     => 'interval',
			'is_system' => true,
		));
		return parent::_prepareColumns();
	
	}
	
	/**
	 * Prepare for mass action for selected rows 
	 */
	protected function _prepareMassaction()
	{
	
		$this->setMassactionIdField('id');
		$this->getMassactionBlock()->setFormFieldName('id');
	
		$this->getMassactionBlock()->addItem('interval', array(
			'label'    => Mage::helper('reviewsystem')->__('Add Default Intervel'),
			'url'      => $this->getUrl('*/*/massIntervel', array('id' =>$this->getRequest()->getParam('id'))),
			'confirm'  => Mage::helper('reviewsystem')->__('Are you sure?')
		));
	
	
		return $this;
	}
	

	   public function getGridUrl() {
    return $this->getUrl('*/*/grid', array('_current' => true));
}
	

}