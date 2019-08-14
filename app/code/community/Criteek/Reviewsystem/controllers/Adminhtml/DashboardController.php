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
 
class Criteek_Reviewsystem_Adminhtml_DashboardController extends Mage_Adminhtml_Controller_action {

	/**
	 * indexAction
	 * 
	 * @return 
	 */
	public function indexAction() {

		$domainID = Mage::helper('reviewsystem')->getDomainId();
		if($domainID)
			$this->_redirectUrl('http://www.criteek.tv/widget/widgetProducts/userProducts/did/'.$domainID);
		else
			$this->_redirectUrl('http://www.criteek.tv/user/login');
	}

}