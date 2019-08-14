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
 

class Criteek_Reviewsystem_Block_Adminhtml_Category_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
	   $form = new Varien_Data_Form(array(
                                      'id' => 'edit_form',
                                      'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
                                      'method' => 'post'
        							  							
                                   )
      );

	   $fieldset = $form->addFieldset('category_form', array(
             'legend' =>Mage::helper('reviewsystem')->__('Category information')
        ));
		$fieldset->addField('interval', 'text', array(
             'label'     => Mage::helper('reviewsystem')->__('Set Interval'),
             'class'     => 'required-entry',
             'required'  => true,
             'name'      => 'interval',
			 'value' =>10,
             'note'     => Mage::helper('reviewsystem')->__('Set the interval in days here.'),
        ));
     
      $form->setUseContainer(true);
      $this->setForm($form);
      return parent::_prepareForm();
  }
}