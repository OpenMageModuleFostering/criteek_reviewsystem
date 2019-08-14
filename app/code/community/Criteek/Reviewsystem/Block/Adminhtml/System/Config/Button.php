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
class Criteek_Reviewsystem_Block_Adminhtml_System_Config_Button extends Mage_Adminhtml_Block_System_Config_Form_Field
{
  /**
   * Set template
   */
  protected function _construct()
  {
    parent::_construct();
    $this->setTemplate('reviewsystem/system/config/button.phtml');
  }

  /**
   * Return element html
   *
   * @param  Varien_Data_Form_Element_Abstract $element
   * @return string
   */
  protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
  {
    return $this->_toHtml();
  }

  /**
   * Return ajax url for button
   *
   * @return string
   */
  public function getAjaxCollectUrl()
  {
    return Mage::helper('adminhtml')->getUrl('reviewsystem/adminhtml_reviewsystem/massMailOnPurchaseComplete');
  }

  /**
   * Generate button html
   *
   * @return string
   */
  public function getButtonHtml()
  {
    $button = $this->getLayout()->createBlock('adminhtml/widget_button')->setData(array(
      'id' => 'reviewsystem_generate_button',
      'label' => $this->helper('adminhtml')->__('Generate reviews for my past orders'),
      'onclick' => 'javascript:collectOrders(); return false;'
     ));
     return $button->toHtml();
  }
}
?>