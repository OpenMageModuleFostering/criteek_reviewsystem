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
	/**
	 * get Api key - Get api key from config
	 *
	 * @return string
	 */
	function getApiUsername(){
		return Mage::getStoreConfig('reviewsystem/product_review_config/api_app_key'); // Api app Key
	}
	function isModuleEnable(){
		if(Mage::getStoreConfig('reviewsystem/product_review_config/criteek_synch'))
		{
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
	function getApiPassword(){
		return Mage::getStoreConfig('reviewsystem/product_review_config/api_app_secret'); // Api app seceret
	}
	
	/**
	 * sendRequest for token - initiate curl request
	 * 
	 * 
	 * 
	 * @return token
	 */
	function getToken(){
		$json = '';	
		$url="http://api.criteek.tv/api/v1/login";
		$curl = curl_init($url);
		$postData= array("apikey" => $this->getApiUsername(), "secretkey" => $this->getApiPassword());
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

		        }
		        else{
		        	$json = json_decode($response);	
		        }
		    }
		}
		//echo $info['http_code'];
		$token =$json->message->token;
		return $token;
	}
	/**
	 * getdomainId - Send curl request to Api to get Domain Id
	 *
	 * 
	 * @return array
	 */
	function getDomainId() {
		
		$json = '';	
		$token = $this->getToken();
		$url="http://api.criteek.tv/api/v1/mydomain?name=".$_SERVER['HTTP_HOST']."&token=".$token;
		$headers= array("token:".$token);
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
		        }
		        else{
		        	$json = json_decode($response);	
		        }
		    }
		}
		$domain_id =$json->message->domainid;
		return $domain_id ;
	}
		/**
	 * fetchProductsInfo - Send curl request to huuto to fetch products Details
	 *
	 * 
	 * 
	 */
	
		function fetchProductInfo() {
		$json = '';	
		$token = $this->getToken();
		$doaminId= $this->getDomainId();
		$url="http://api.criteek.tv/api/v1/widgetproduct?domainid=".$doaminId."&token=".$token;
		$headers= array("token:".$token);
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
		        }
		        else{
		        	$json = json_decode($response);	
		        }
		    }
		}
		$productData =$json->message;
		return $productData ;
		}
		
				/**
	 * setDoamin On Off - Send curl request to huuto to fetch products Details
	 *
	 * param value for domain value
	 * 
	 */
	
		function setDomainOnOff($value) {
					$json = '';	
		$token = $this->getToken();
		$doaminId= $this->getDomainId();
		$url="http://api.criteek.tv/api/v1/domain/".$doaminId;
		$putData= array("default"=>$value);
		$headers= array("token:".$token);
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
	 * set product widget On Off - Send curl request to huuto to fetch products Details
	 *
	 * param value for id and value
	 * 
	 */
	 function setProductOnOff($id,$value) {
		$json = '';	
		$token = $this->getToken();
		$doaminId= $this->getDomainId();
		$url="http://api.criteek.tv/api/v1/widgetproduct/".$id;
		$putData= array("domainid"=>$doaminId, "widget"=>$value);
		$headers= array("token:".$token);
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
	 * fetchReviwersInfo - Send curl request to huuto to fetch reviews Details
	 *
	 * 
	 * 
	 */
	
		function fetchReviewersInfo($productid) {
		$json = '';	
		$token = $this->getToken();
		$doaminId= $this->getDomainId();
		$url="http://api.criteek.tv/api/v1/widgetproductreviewers?domainid=".$doaminId."&productid=".$productid;
		$headers= array("token:".$token);
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
		        }
		        else{
		        	$json = json_decode($response);	
		        }
		    }
		}
		return $json ;
		}
		
		function setProductDeleteArchive($id,$value) {
		$json = '';	
		$token = $this->getToken();
		$doaminId= $this->getDomainId();
		$putData= array("productid"=>array($id), "widget"=>$value);
		$url="http://api.criteek.tv/api/v1/widgets?data=".$putData;
		
		$headers= array("token:".$token);
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
			function fetchReviewersStatus($streamId,$email) {
		$json = '';	
		$token = $this->getToken();
		$url="http://api.criteek.tv/api/v1/widgetproductcheckreviewers?streamingid=".$streamId."&emailid=".$email;
		$headers= array("token:".$token);
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
		        }
		        else{
		        	$json = json_decode($response);	
		        }
		    }
		}
		return $json ;
		}
}