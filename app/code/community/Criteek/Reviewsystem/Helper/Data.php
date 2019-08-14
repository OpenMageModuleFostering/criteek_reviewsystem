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
 
class Criteek_Reviewsystem_Helper_Data extends Mage_Core_Helper_Abstract
{
  
	private $apiUrl = 'https://api.criteek.tv/api/v1/';
    private $widgetJsUrl = 'https://widget.criteek.tv/servicewidget_v1.js';
	
	/**
	 * get Api key - Get api key from config
	 *
	 * @return string
	 */
   function getApiKey(){
	   return Mage::getStoreConfig('reviewsystem/product_review_config/api_app_key'); // Api app Key
   }
	
  /**
   * check module is enabled or not
   *
   * @return bool
   */
  function isModuleEnable(){
    if(Mage::getStoreConfig('reviewsystem/product_review_config/criteek_synch')){
      return true;
    }else{
      return false;
    }
  }

  /**
   * Check if Mail After Purchase feature is enabled
   *
   */
  function isMailAfterPurchaseEnable(){
    if(Mage::getStoreConfig('reviewsystem/product_review_config/enable_mailpurchase')){
      return true;
    }else{
      return false;
    }
  }


  /**
   * get Api secret - Get api secret from config
   *
   * @return string
   */
  function getApiSecretKey(){
    return Mage::getStoreConfig('reviewsystem/product_review_config/api_app_secret'); // Api app seceret
  }
	
	/**
   * get Api secret - Get api secret from config
   *
   * @return string
   */
  function getModeUrl(){
		return $this->apiUrl;
  }
	
	/**
   * get Api secret - Get api secret from config
   *
   * @return string
   */
  function getWidgetjsUrl(){
      return $this->widgetJsUrl;
  }
	
  /**
   * sendRequest for token - initiate curl request
   * 
   * @return token
   */
  function getToken(){
    $json = '';	
    $url = $this->getModeUrl()."login";
    $curl = curl_init($url);
    $postData = array("apikey" => $this->getApiKey(), "secretkey" => $this->getApiSecretKey());

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

    $response = curl_exec($curl);

    if (empty($response)) {
      throw new Exception(Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewsystem')->__('Check API key and API secret again')));
      curl_close($curl); // close cURL handler
    } else {
      $info = curl_getinfo($curl);
      if (empty($info['http_code'])) {		
        Mage::log("No HTTP code was returned", null, 'reviewsystem.log');
      } else {
        // load the HTTP codes
        if($info['http_code']!=200){
          Mage::log("HTTP code was not 200", null, 'reviewsystem.log');
        } else {
          $json = json_decode($response);	
        }
      }
    }
		if($json->message->token){
    	$token = $json->message->token;
    	return $token;
		}
		else{
			return null;
		}
  }
	
  /**
   * getdomainId - Send curl request to Api to get Domain Id
   *
   * @return string
   */
  function getDomainId() {
    $json = '';	
   	$token = $this->getToken();
    $page = 'mydomain';
		$params = "name=".$_SERVER['HTTP_HOST']."&token=".$token;
		
		$url = $this->getModeUrl().$page."?".$params;
    $headers = array("token:".$token);
    $curl = curl_init($url);
    
		curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);	
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		
		if (empty($response)) {
      throw new Exception(Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewsystem')->__('Check API key and API secret again')));
      curl_close($curl); // close cURL handler
    } else {
      $info = curl_getinfo($curl);
      if (empty($info['http_code'])) {		
        Mage::log("No HTTP code was returned", null, 'reviewsystem.log');
      } else {
		    // load the HTTP codes
        if($info['http_code']!=200){
          Mage::log("HTTP code was not 200", null, 'reviewsystem.log');
        } else {
          $json = json_decode($response);	
        }
      }
		}
		
    $domain_id = $json->message->domainid;
    return $domain_id ;
  }
				
  /**
   * setDoamin On Off - Send curl request to huuto to fetch products Details
   *
   * @param $value
   * 
	 * @return string
   */
  function setDomainOnOff($value) {    
		$json = '';	
    $doaminId = $this->getDomainId();
		$token = $this->getToken();
    $putData = array("default"=>$value);
    $url = $this->getModeUrl()."domain/".$doaminId;
		$headers = array("token:".$token);
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    // Instead of POST fields use these settings
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($putData));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);	
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
		
		if (empty($response)) {
			throw new Exception(Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewsystem')->__('Check API key and API secret again')));
      curl_close($curl); // close cURL handler
		} else {
      $info = curl_getinfo($curl);
      if (empty($info['http_code'])) {		
        Mage::log("No HTTP code was returned", null, 'reviewsystem.log');
      } else {
        // load the HTTP codes
        if($info['http_code']!=200){
          Mage::log("HTTP code was not 200", null, 'reviewsystem.log');
        }
        else{
          $json = json_decode($response);	
        }
      }
    }
	  return $json ;
  }
	
	/**
   * massCreateOrders - send orders data to api
   *
   * @param $orders, $token, $storeId
   * 
	 * @return string
   */
	
	public function massCreateOrders($orders, $token, $storeId)
	{
		$url = $this->getModeUrl().'emailmarketing';
		$count = count($orders);
		$json = '';	
		$postData = array('count'=>$count, 'data'=>$orders);
		$headers= array("token:".$token);
		
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 30);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');

    $response = curl_exec($curl);

    if (empty($response)) {
      $message = Mage::helper('reviewsystem')->__('Check API key and API secret again');
      curl_close($curl); // close cURL handler
			return $message;
    } else {
      $info = curl_getinfo($curl);
      if (empty($info['http_code'])) {		
        Mage::log("No HTTP code was returned", null, 'reviewsystem.log');
      } else {
        // load the HTTP codes
        if($info['http_code']!=200){
          Mage::log("HTTP code was not 200", null, 'reviewsystem.log');
        } else {
          $json = json_decode($response);
						
        }
      }
    }
		
		Mage::getConfig()->saveConfig('reviewsystem/product_review_config/criteek_order_sent', '1');
	  return 1;
	}
	
	/**
   * prepareProductsData
   *
   * @param $order
   * 
	 * @return array
   */	
	public function prepareProductsData($order) 
	{
   	Mage::app()->setCurrentStore($order->getStoreId());
    $products = $order->getAllVisibleItems(); //filter out simple products
		$productsArr = array();
		
		foreach ($products as $product) {
			//use configurable product instead of simple if still needed
      $full_product = Mage::getModel('catalog/product')->load($product->getProductId());
			$configurable_product_model = Mage::getModel('catalog/product_type_configurable');
      $parentIds = $configurable_product_model->getParentIdsByChild($full_product->getId());
      
			if (count($parentIds) > 0) {
      	$full_product = Mage::getModel('catalog/product')->load($parentIds[0]);
      }

			$productData = array();

			$productData['name'] = $full_product->getName()!='' ? addslashes($full_product->getName()) : "no_name";
			$productData['price'] = (int)$product->getPrice();
			$productData['quantity'] = (int)$product->getQtyOrdered();
			$productData['productid'] = $product->getId();
			$productData['url'] =  $full_product->getProductUrl();

			$productsArr[] = $productData;
		}
		return $productsArr;
	}
}