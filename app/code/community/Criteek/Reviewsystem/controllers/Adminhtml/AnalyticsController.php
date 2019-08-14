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
 
class Criteek_Reviewsystem_Adminhtml_AnalyticsController extends Mage_Adminhtml_Controller_action {
	
	public function _initAction()
	{

		$this->loadLayout()
		->_addBreadcrumb(Mage::helper('reviewsystem')->__('Email Analytics'), Mage::helper('reviewsystem')->__('Email Analytics'));
		return $this;
	}

	public function indexAction()
	{
		$this->_title($this->__('Email Analytics'))->_title($this->__('Email Analytics'))->_title($this->__('Email Analytics Report'));

		$this->_initAction()
	//	->_setActiveMenu('reviewsystem/report')
		->_addBreadcrumb(Mage::helper('reviewsystem')->__('Email Analytics'), Mage::helper('reviewsystem')->__('Email Analytics Report'));
		$this->getLayout()->getBlock('grid.filter.form');
		$this->renderLayout();

	}

	public function exportCsvAction()
    { 
	$datarow =array();
		if ($data = $this->getRequest()->getPost()) {
			$orders_csv_row ="Date,Product Name,Order Id,Customer Email,Customer Name,Email Send to Customer,Responded By Customer,Review uploaded By Customer";
			$orders_csv_row.="\n";
			$from = $_REQUEST['from'];
		$to = $_REQUEST['to'];		
		$from_date = date('Y-m-d' . ' 00:00:00', strtotime($from));
		$to_date = date('Y-m-d' . ' 23:59:59', strtotime($to));
			if(!empty($_REQUEST['from']) && !empty($_REQUEST['to'])){
		
		$orders_row = array();
		$filter_type = $_REQUEST['filter_type'];
		$from = $_REQUEST['from'];
		$to = $_REQUEST['to'];		
		$from_date = date('Y-m-d' . ' 00:00:00', strtotime($from));
		$to_date = date('Y-m-d' . ' 23:59:59', strtotime($to));
			$_orderCollections = Mage::getModel('reviewsystem/emailQueue')->getCollection()
			 ->addFieldToFilter('email_time', array('from'=>$from_date, 'to'=>$to_date))
                ->load();
		$result_order = $_orderCollections->count();
		$result_clicked =$_orderCollections->addFieldToFilter('reopened', array('eq'=>1))->getData();
	$result_status = $_orderCollections->addFieldToFilter('uploaded_review', array('eq'=>1))->getData();
		$i=0;
	foreach($_orderCollections as $key=>$single_order) {
			$clicked =$single_order->getReopened();
			$email =$single_order->getCustomerEmail();
			$streamid =$single_order->getProductSku();
			$helper = Mage::helper('reviewsystem');
		$status =$single_order->getStatus();
		$helper->fetchReviewersStatus($streamid ,$email)->message;
	
			if($clicked ==0)
			{
				$clicked= "No";
			}elseif($clicked==1)
			{
				$clicked= "Yes";
			}
			if($helper->fetchReviewersStatus($status ,$email)->message =="Reviewer present")
			{
				 Mage::getModel('reviewsystem/emailQueue')->load($single_order->getId())->addData(array('uploaded_review'=>1))->save();
				$upload= "Yes";
			}else
			{
				$upload= "No";
			}
			if($status ==0)
			{
				$status= "No";
			}else
			{
				$status= "Yes";
			}
     $order = Mage::getModel('sales/order')->load($single_order->getOrderId());
    $Incrementid = $order->getIncrementId();
				$datarow = array(date("m/d/Y",strtotime($single_order->getEmailTime())), $single_order->getProductName(),$Incrementid ,$email,$single_order->getCustomerName(),$status,$clicked,$upload);
		}
	}else{
			$orders_row = array();

			$_orderCollections = Mage::getModel('reviewsystem/emailQueue')->getCollection()
                ->load();
		$result_order = $_orderCollections->count();
		$result_clicked =$_orderCollections->addFieldToFilter('reopened', array('eq'=>1))->getData();
		$result_status = Mage::getModel('reviewsystem/emailQueue')->getCollection()
                ->load()->addFieldToFilter('uploaded_review', array('eq'=>1))->getData();
		$i=0;
		foreach($_orderCollections as $key=>$single_order) {
			$clicked =$single_order->getReopened();
			$email =$single_order->getCustomerEmail();
			$streamid =$single_order->getProductSku();
			$helper = Mage::helper('reviewsystem');
		$status =$single_order->getStatus();
		$helper->fetchReviewersStatus($streamid ,$email)->message;
	
			if($clicked ==0)
			{
				$clicked= "No";
			}elseif($clicked==1)
			{
				$clicked= "Yes";
			}
			if($helper->fetchReviewersStatus($status ,$email)->message =="Reviewer present")
			{
				 Mage::getModel('reviewsystem/emailQueue')->load($single_order->getId())->addData(array('uploaded_review'=>1))->save();
				$upload= "Yes";
			}else
			{
				$upload= "No";
			}
			if($status ==0)
			{
				$status= "No";
			}else
			{
				$status= "Yes";
			}
     $order = Mage::getModel('sales/order')->load($single_order->getOrderId());
    $Incrementid = $order->getIncrementId();
				$datarow = array(date("m/d/Y",strtotime($single_order->getEmailTime())), $single_order->getProductName(),$Incrementid ,$email,$single_order->getCustomerName(),$status,$clicked,$upload);
			$line = "";
					$comma = "";
					foreach($datarow as $titlename) {
						$line .= $comma . str_replace(array(','),array(""), $titlename);
						$comma = ",";
					}

					$line .= "\n";
					
					$orders_csv_row .=$line;
	
		}
		
	}
							}
			$fileName   = 'analytics_report.csv';
			$this->_sendUploadResponse($fileName, $orders_csv_row);
		
	}
		protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}