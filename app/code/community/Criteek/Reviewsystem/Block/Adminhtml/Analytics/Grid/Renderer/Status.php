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

class Criteek_Reviewsystem_Block_Adminhtml_Analytics_Grid_Renderer_Status extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 	 public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    } 
    protected function _getValue(Varien_Object $row)
    {       
       $val = $row->getData();
	   $cutomer_email =$val['customer_email'];
	   $customer_time = $val['email_time'];
	   $dt = new DateTime($customer_time);
       $date_mail = $dt->format('Y-m-d');
	   $product_id = $val['product_id'];
	   $customer_name = $val['customer_name'];
	   $collection= Mage::getModel('catalog/product')->getCollection()->addFieldToFilter('entity_id', $product_id)->getFirstItem();
	   $sku =$collection->getSku();
	  if ($col =Mage::getModel('reviewsystem/product')->getCollection()->addFieldToFilter('product_sku', $sku)->getFirstItem())
	  {
		 $pid =$col->getPid();
		 $i=0;
		 foreach(Mage::helper('reviewsystem')->fetchReviewersInfo($pid)->message as $info)
		 {
	
	      $reviewerEmail =$info->reviewer_email;
		  $reviewerName =$info->reviewer_name;

		  if(strcasecmp($reviewerEmail, $cutomer_email)==0 && (strtotime(substr($info->reviewdate,0,11))>strtotime($date_mail))){ 
		   $i++;
		 }
		
		 }
		 if($i>0){
		 $value= Mage::helper('reviewsystem')->__('Yes(').$i.")";
		 }else{
			 $value= Mage::helper('reviewsystem')->__('No(').$i.")";
		 }
	  }
        return $value;
    }
}