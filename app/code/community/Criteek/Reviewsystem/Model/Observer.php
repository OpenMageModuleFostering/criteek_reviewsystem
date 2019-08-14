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
	public function getOrder(Varien_Event_Observer $observer)
	{
      if(Mage::helper('reviewsystem')->isModuleEnable()){
		 $order = $observer->getEvent()->getOrder();
		 $orderId= $order->getId();
		 $orderStatus = $order->getStatus();
		 $status = Mage::getStoreConfig('reviewsystem/product_review_config/allowed_status');
		 $customerEmail = $order->getCustomerEmail();
		 $customerName = $order->getCustomerName();
		
		if($orderStatus == $status ){
		  foreach( $order->getAllVisibleItems() as $item ) {
				$itemProductId = $item->getProductId();
				$product = Mage::getModel('catalog/product')->load($itemProductId);
				$cats = $product->getCategoryIds();
				$categoryId =array();
			foreach ($cats as $category_id) {
				$category = Mage::getModel('catalog/category')->load($category_id);
				//each category has a path attribute
				$path = $category->getPath(); //should look like 1/3/14/23/55.
				//split the path by slash
				$pathParts = explode('/', $path);
			if (count($pathParts) == 3) {
			//it means the category is already a top level category
				$categoryname = $category->getName();
				$categoryId[]= $category->getId();
			}
			elseif (isset($pathParts[2])) {
				$topCategory = Mage::getModel('catalog/category')->load($pathParts[2]);
				$categoryname = $topCategory->getName();
				$categoryId[]= $topCategory->getId();
			}	
			}
		if(!empty($categoryId)){
					$Interval =array();
		foreach($categoryId as $topId)
					{
				$model = Mage::getModel('reviewsystem/category');
				$model->load($topId, 'category_id');
		$Interval[$topId]= $model->getInterval();
					}
				if(count(array_keys($Interval, min($Interval)))==1)
				{
					
			$cat_id=array_keys($Interval, min($Interval));

			$catModel=Mage::getModel('reviewsystem/category')->getCollection()->addFieldToFilter('category_id', $cat_id[0])->addFieldToSelect('interval')->getData();
		
		}else{
			$catModel=Mage::getModel('reviewsystem/category')->getCollection()->addFieldToFilter('category_id', $categoryId[0])->addFieldToSelect('interval')->getData();
		}
				$interval_days = $catModel[0]['interval'];
				$email_date=  date('Y-m-d H:i:s', strtotime('+'.$interval_days.' days') );
				 $productModel = Mage::getModel('reviewsystem/emailQueue');
				$productModel->setProductId($itemProductId)
					->setOrderId($orderId)
					->setStatus(0)
					->setProductSku($item->getSku())
					->setProductName($item->getName())
					->setEmailTime($email_date)
					->setCustomerEmail($customerEmail)
					->setCustomerName($customerName)
					->save();
		 }
		  }
		 }
	  }
		  return $observer;
	}
	
	public function getCategory($observer)
	{ if(Mage::helper('reviewsystem')->isModuleEnable()){ 
		 $event      = $observer->getEvent();
	     $category   = $event->getCategory();
		 $interval =10;
		 $category_id= $category->getId();
		 $model = Mage::getModel('reviewsystem/category');
			$categories = Mage::getModel('reviewsystem/category')->getCollection()
					;
					$catId =array();
		foreach($categories as $category){
			$catId[] = $category->getCategoryId();
			
				}
	
				if(in_array($category_id,$catId))
			{
			
			}else{
					$category = Mage::getModel('catalog/category')->load($category_id);
					$path = $category->getPath(); //should look like 1/3/14/23/55.
				    //split the path by slash
				    $pathParts = explode('/', $path);
					if (count($pathParts) == 3) {
					$categoryname = $category->getName();
					$category_id= $category->getId();
					$model->setCategoryId($category_id)
					->setInterval($interval)
					->save();
					}
		}}
			return $observer;
				
		}
		public function adminSystemConfigChangedSection()
		{
			if(Mage::getStoreConfig('manage_widget/manage_widget_config/widget_synch')==1){
				$value="on";
				Mage::helper('reviewsystem')->setDomainOnOff($value);
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Widget is ON for Domain.'));
			}else{
				$value="off";
				Mage::helper('reviewsystem')->setDomainOnOff($value);
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Widget is OFF for Domain.'));
			}
	   return true;
		}
		public function getproductData($observer)
		{
			 if(Mage::helper('reviewsystem')->isModuleEnable()){
			$currentUrl =$_SERVER['REQUEST_URI'];
			if (strpos($currentUrl,'?criteek=') !== false) {
				$customer_email= $_GET['criteek'];

				$collection =Mage::getModel('reviewsystem/emailQueue')->getCollection()
				->addFieldToFilter('status', array('eq'=>1))
				->addFieldToFilter('id', array('eq'=>$customer_email))
				->addFieldToSelect('*');
				if($collection->getData())
				{
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
		
	public function adminSystemConfigApiAuthenticationSection()
		{

			if(Mage::getStoreConfig('reviewsystem/product_review_config/criteek_synch')==1){
		
				if(Mage::helper('reviewsystem')->getToken()==""){
					return Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewsystem')->__('Check API key and API secret again'));
				}
			}
	   return ;
		}
}