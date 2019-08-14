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

class Criteek_Reviewsystem_Adminhtml_CategoryController extends Mage_Adminhtml_Controller_action {
	
	
	/**
	 * _initAction
	 * 
	 * @return object
	 */
	protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('reviewsystem/reviewsystem_category_int')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Synchronize Categories'), Mage::helper('adminhtml')->__('Synchronize Categories'));
		return $this;
	}

	/**
	 * indexAction
	 * 
	 * @return 
	 */
	public function indexAction() {
		
		$this->_initAction();
		$this->renderLayout();
	}
	
	
/**
	 * gridAction
	 * 
	 * @return object
	 */
	public function gridAction()
	{

	    $this->loadLayout();
	    $this->getResponse()->setBody(
	    $this->getLayout()->createBlock('reviewsystem/adminhtml_category_grid')->toHtml()
	    );
	}
	
	/**
	 * editAction
	 * 
	 * @return object
	 */

	 public function editAction()
    {
		 $this->loadLayout();
		 $this->renderLayout();
    }
	/**
	 * saveAction
	 * 
	 * @return object
	 */
	
  public function saveAction()
    {
        if ($data = $this->getRequest()->getPost())
        {
           $categoryId = $this->getRequest()->getParam('id');
			 $collectForId = Mage::getModel('reviewsystem/category')->getCollection()->addFieldToFilter('category_id',$categoryId)->addFieldToSelect('id')->getData();
		
			$id= $collectForId[0]['id'];
		 $model =Mage::getModel('reviewsystem/category')->load($id);
			$interval= $this->getRequest()->getParam('interval');
            try {
                if ($model) {
					$model
					->setInterval($interval)
					->save();
                }
              
 
                if (!$model->getId()) {
                    Mage::throwException(Mage::helper('reviewsystem')->__('Error saving interval'));
                }
 
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Interval is saved successfully.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
				$this->_redirect('*/*/');
 
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                if ($model && $model->getId()) {
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                } else {
                    $this->_redirect('*/*/');
                }
            }
 
            return;
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('reviewsystem')->__('No data found to save'));
        $this->_redirect('*/*/');
    }
	
	
	public function massIntervelAction() {
			
		$helper = Mage::helper('reviewsystem');	
		$categoryIds = $this->getRequest()->getParam('id');	
		foreach(  $categoryIds as $categoryId) {
		  $data = array('interval'=>10);
	      $colId =Mage::getModel('reviewsystem/category')->getCollection()->addFieldToFilter('category_id', $categoryId)->getFirstItem()->getId();
		  Mage::getModel('reviewsystem/category')->load($colId)->addData($data)->save();
		
	     }
		   Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('reviewsystem')->__('Default Interval is saved successfully.'));
          Mage::getSingleton('adminhtml/session')->setFormData(false);
				$this->_redirect('*/*/');
		}
}