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
 
class Criteek_Reviewsystem_Model_Source_Order_Status
{
  public function toOptionArray()
  {   
    $orderStatusCollection = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
    $status = array();
    $status = array('-1'=>'Please Select..');

    foreach($orderStatusCollection as $orderStatus) {
      $status[] = array ('value' => $orderStatus['status'], 'label' => $orderStatus['label']);
    }
    return $status;
  }
}