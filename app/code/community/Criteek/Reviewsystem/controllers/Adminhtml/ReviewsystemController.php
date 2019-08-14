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
 
class Criteek_Reviewsystem_Adminhtml_ReviewsystemController extends Mage_Adminhtml_Controller_Action {
	
	//max amount of orders to export
  const MAX_ORDERS_TO_EXPORT = 5000;
  const MAX_BULK_SIZE        = 200;
	
	/**
	 * massMailOnPurchaseCompleteAction
	 *
	 */
	public function massMailOnPurchaseCompleteAction() {
		
  	try {
    	ini_set('max_execution_time', 300);
      $result = null;
			$storeCode = Mage::app()->getRequest()->getParam('store');
			$currentStore = null;
			
			foreach (Mage::app()->getStores() as $store) {
      	if ($store->getCode() == $storeCode) {
        	global $currentStore;
          $currentStore = $store;
          break;
        }
      }

      $storeId = $currentStore->getId();

			/*if(Mage::getStoreConfig('reviewsystem/product_review_config/criteek_order_sent')){
				Mage::app()->getResponse()->setBody('You have already sent the past orders!');
				return;
			}*/
			
      if (Mage::getStoreConfig('reviewsystem/product_review_config/api_app_key', $currentStore) == null or Mage::getStoreConfig('reviewsystem/product_review_config/api_app_secret', $currentStore) == null){
      	Mage::app()->getResponse()->setBody('Please make sure you insert your APP KEY and SECRET and save configuration before trying to export past orders');
        return;   
      }

      $token = Mage::helper('reviewsystem')->getToken();

	if ($token == null)
      {                
				Mage::app()->getResponse()->setBody("Please make sure the APP KEY and SECRET you've entered are correct");
       	return ;
      }

	if(!mage::helper('reviewsystem')->isMailAfterPurchaseEnable()){
		Mage::app()->getResponse()->setBody("Mail After Purchase feature is disabled. Please enable it and Try again!");
		return;
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
					$orderStatuses = array_map('strtolower', explode(' ', $orderStatuses));
			}
           
			$salesCollection = $salesModel->getCollection()
							->addFieldToFilter('status', $orderStatuses)
							->addFieldToFilter('store_id', $storeId)
							->addAttributeToFilter('created_at', array('gteq' =>$from))
							->addAttributeToSort('created_at', 'DESC')
							->setPageSize(self::MAX_BULK_SIZE);

			$pages = $salesCollection->getLastPageNumber();

      do {
				try {
					$offset++;
					$salesCollection->setCurPage($offset)->load();
					$orders = array();
					foreach($salesCollection as $order){
						$orderData = array();
						$orderData["name"] = str_replace(' ','',$order->getCustomerName());
						$orderData["email"] = $order->getCustomerEmail();
						$orderData["orderdate"] = $order->getCreatedAtDate()->toString('yyyy-MM-dd');                        
						$orderData['orderid'] = $order->getIncrementId();
						$orderData['domainid'] = (int)Mage::helper('reviewsystem')->getDomainId();

						$orderData['product'] = Mage::helper('reviewsystem')->prepareProductsData($order);
						
						$orders[] = $orderData;
					}

					if (count($orders) > 0){
						$ret = Mage::helper('reviewsystem')->massCreateOrders($orders, $token, $storeId);
						if($ret == 1){
							Mage::app()->getResponse()->setBody(1);
							return;
						}
						else{
							Mage::app()->getResponse()->setBody($ret);
        			return; 
						}
					}
					else{
						Mage::app()->getResponse()->setBody('No orders found to export.');
        		return; 
					}
				} catch (Exception $e) {
					Mage::log('Failed to export past orders. Error: '.$e);    
				}
				$salesCollection->clear();
			} while ($offset <= (self::MAX_ORDERS_TO_EXPORT / self::MAX_BULK_SIZE) && $offset < $pages);
		} catch(Exception $e) {
    	Mage::log('Failed to export past orders. Error: '.$e);
    }
		
	} 
}