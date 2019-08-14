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
 
class Criteek_Reviewsystem_Model_Observer
{
	/**
	 * sendOrderToCriteek
	 *
	 * @param object
	 *
	 * @return object
	 */
	public function sendOrderToCriteek(Varien_Event_Observer $observer)
	{
		if(Mage::helper('reviewsystem')->isModuleEnable() && Mage::helper('reviewsystem')->isMailAfterPurchaseEnable()){
			try {
				ini_set('max_execution_time', 300);
				$result = null;
				$order = $observer->getEvent()->getOrder();
				$store =  Mage::app()->getStore($order->getStoreId());
				$storeCode = $store->getCode();
				$storeId = $store->getId();
				$currentStore = $storeCode;
	
				if (Mage::getStoreConfig('reviewsystem/product_review_config/api_app_key', $currentStore) == null or Mage::getStoreConfig('reviewsystem/product_review_config/api_app_secret', $currentStore) == null){
					Mage::log('Please make sure you insert your APP KEY and SECRET and save configuration before trying to export past orders');
					return $observer;   
				}
	
				$token = Mage::helper('reviewsystem')->getToken();
				
				if ($token == null) 
				{                
					Mage::log("Please make sure the APP KEY and SECRET you've entered are correct");
					return $observer;
				}
	
				$today = time();
				$last = $today - (60*60*24*90); //90 days ago
				$from = date("Y-m-d", $last);
				$offset = 0;
				$salesModel = Mage::getModel("sales/order");

				$orderStatuses = Mage::getStoreConfig('reviewsystem/product_review_config/allowed_status', $currentStore);

				if ($orderStatuses == null) {
					$orderStatuses = array('complete');
				} else {
					$orderStatuses = strtolower($orderStatuses);
				}

				if($order->getStatus()!=$orderStatuses){
					return $observer;
				}

				$orderData = array();
				$orderData["name"] = str_replace(' ','',$order->getCustomerName());
				$orderData["email"] = $order->getCustomerEmail();
				$orderData["orderdate"] = $order->getCreatedAtDate()->toString('yyyy-MM-dd');                        
				$orderData['orderid'] = $order->getIncrementId();
				$orderData['domainid'] = (int)Mage::helper('reviewsystem')->getDomainId();

				$orderData['product'] = Mage::helper('reviewsystem')->prepareProductsData($order);
				
				$orders[] = $orderData;
				
				if (count($orders) > 0){
					Mage::helper('reviewsystem')->massCreateOrders($orders, $token, $storeId);
				}
							
			} catch(Exception $e) {
				Mage::log('Failed to export past orders. Error: '.$e);
			}
	  }
		return $observer;
	}
	
	/**
	 * adminSystemConfigChangedSection
	 *
	 * @return bool
	 */
	public function adminSystemConfigChangedSection()
	{
		if(Mage::getStoreConfig('manage_widget/manage_widget_config/widget_synch') == 1){
			$value = "on";
			Mage::helper('reviewsystem')->setDomainOnOff($value);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Widget is ON for Domain.'));
		} else {
			$value = "off";
			Mage::helper('reviewsystem')->setDomainOnOff($value);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Widget is OFF for Domain.'));
		}
	  return true;
	}
	
	/**
	 * getproductData
	 *
	 * @param object
	 */	
	public function getproductData($observer)
	{
		if(Mage::helper('reviewsystem')->isModuleEnable()){
			$currentUrl = $_SERVER['REQUEST_URI'];
			if (strpos($currentUrl,'?criteek=') !== false) {
				$customer_email = $_GET['criteek'];

				$collection = Mage::getModel('reviewsystem/emailQueue')->getCollection()
					->addFieldToFilter('status', array('eq'=>1))
					->addFieldToFilter('id', array('eq'=>$customer_email))
					->addFieldToSelect('*');
				
				if($collection->getData()){
					foreach($collection as $data)
					{
						$data->setReopened(1);
						$data->save();
					}
				}
			}	 
		}
    return $observer;
	}
	
	/**
	 * adminSystemConfigApiAuthenticationSection
	 *
	 * @return null
	 */	
	public function adminSystemConfigApiAuthenticationSection()
	{
		if(Mage::getStoreConfig('reviewsystem/product_review_config/criteek_synch') == 1){
			if(Mage::helper('reviewsystem')->getToken() == ""){
				return Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewsystem')->__('Check API key and API secret again'));
			}
		}
		
		if(Mage::getStoreConfig('reviewsystem/manage_widget_config/widget_synch') == 1){
			$value = "on";
			Mage::helper('reviewsystem')->setDomainOnOff($value);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Widget is ON for Domain.'));
		} else {
			$value = "off";
			Mage::helper('reviewsystem')->setDomainOnOff($value);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Widget is OFF for Domain.'));
		}
	  return true;
	  return ;
	}
}