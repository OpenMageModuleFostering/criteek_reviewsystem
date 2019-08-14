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

class Criteek_Reviewsystem_Block_Adminhtml_Analytics_Grid extends  Mage_Adminhtml_Block_Report_Grid
{
	
	public function __construct()
	{
		parent::__construct();
		$this->setId('gridAnalytics');
		 $this->setTemplate('reviewsystem/grid.phtml');
		$this->setDefaultSort('id');

	}

	protected function _prepareCollection()
	{
		parent::_prepareCollection();
		 $this->_prepareTotals('customer_name,reopened'); 
		return $this->getCollection()->initReport('reviewsystem/emailQueue_collection');

	}

	protected function _prepareColumns()
	{
		$this->addColumn('product_name', array(
            'header'    =>Mage::helper('reviewsystem')->__('Product Name'),
            'index'     =>'product_name',
            'sortable'  => false,
			'filter' => false,
		));

		$this->addColumn('customer_name', array(
            'header'    =>Mage::helper('reviewsystem')->__('Customern Name'),
            'index'     =>'customer_name',
            'sortable'  => false,
			'filter' => false,
		));

		$this->addColumn('customer_email', array(
            'header'    =>Mage::helper('reviewsystem')->__('Email received'),
            'index'     =>'customer_email',
            'sortable'  => false,
			'filter' => false,
		));
		
		$this->addColumn('reopened', array(
            'header'    =>Mage::helper('reviewsystem')->__('Responded by Customer'),
            'index'     =>'reopened',
            'sortable'  => false,
			'renderer'  => 'Criteek_Reviewsystem_Block_Adminhtml_Analytics_Grid_Renderer_Reopened',
			'filter' => false,
		));

		$this->addColumn('status', array(
            'header'    =>Mage::helper('reviewsystem')->__('Review Uploaded by Customer'),
            'index'     =>'status',
			'renderer'  => 'Criteek_Reviewsystem_Block_Adminhtml_Analytics_Grid_Renderer_Status',
            ));

            $this->addExportType('*/*/exportSimpleCsv', Mage::helper('reviewsystem')->__('CSV'));

            return parent::_prepareColumns();
	}

	protected function _prepareTotals($columns = null){
  $columns=explode(',',$columns);
  if(!$columns){
    return;
  }
  $this->_countTotals = true;   
  $totals = new Varien_Object();
  $fields = array();
  foreach($columns as $column){
    $fields[$column]    = 0;    
  } 
  foreach ($this->getCollection() as $item) {
    foreach($fields as $field=>$value){
      $fields[$field]+=$item->getData($field);
    }
  }
  $totals->setData($fields);
  $this->setTotals($totals);
  return;
}
	

}