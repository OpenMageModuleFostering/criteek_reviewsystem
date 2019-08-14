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
 
class Criteek_Reviewsystem_Model_Cron
{	

	/**
	 *
	 * Send Email via Cron - Send Email to review product
	 *
	 */
	
    public function sendEmail()                
      {		
	  if(Mage::helper('reviewsystem')->isModuleEnable()){
	 $sender_email= Mage::getStoreConfig('trans_email/ident_general/email');
	      $sender_name =Mage::getStoreConfig('trans_email/ident_general/name');

	   $collection = Mage::getModel('reviewsystem/emailQueue')->getCollection();  
     foreach($collection as $queue)
	  {$current_date =Mage::getModel('core/date')->date('Y-m-d');
			  $emailtime = $queue->getEmailTime();
			  $customer_email= $queue->getCustomerEmail();
			  $customerid =$queue->getId();
			  $customer_name = $queue->getCustomerName();
			  $product_id = $queue->getProductId();
			  $date_time = new DateTime($emailtime);
			  $date = $date_time->format('Y-m-d');
			  $time = $date_time->format('H:i:s');
			   $product = Mage::getModel('catalog/product')->load($product_id);
	 if( $current_date>= $date ){
          if($queue->getStatus()==0){
			   $emailTemplateVariables = array();	
			   $emailTemplateVariables['product_url'] = Mage::getBaseUrl().Mage::getResourceSingleton('catalog/product')
			  ->getAttributeRawValue($product_id, 'url_path', Mage::app()->getStore())."?criteek=".$customerid ;
				$emailTemplateVariables['product_name'] = $product->getName();
			   $emailTemplateVariables['customer_name'] = $customer_name;  
			   $emailTemplate  = Mage::getModel('core/email_template')
								->loadDefault('product_review_email_template');
			   $emailTemplate->setSenderName($sender_name);				  
			   $emailTemplate->setSenderEmail($sender_email);
				$processedTemplate = $emailTemplate->getProcessedTemplate($emailTemplateVariables);
				$emailTemplate->send($customer_email,$customer_name, $emailTemplateVariables);
				$data = array('status'=>1);
				$status= Mage::getModel('reviewsystem/emailQueue')->load($queue->getId())->addData($data)->save();
			
		
  }
	 }
	 }

	 
	  }
	  }
 }