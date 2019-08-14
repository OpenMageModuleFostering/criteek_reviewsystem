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
 
class Criteek_Reviewsystem_Model_Mysql4_EmailQueue_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
  	 * Constructor
  	 *
  	 * @return unknown
  	 */
	public function _construct()
    {
        parent::_construct();
        $this->_init('reviewsystem/emailQueue');
    }
	protected function _joinFields($from = '', $to = '')
	{
	 $this->addFieldToFilter('email_time' , array("from" => $from, "to" => $to, "datetime" => true));
    //    $this->getSelect()->group('email_time');
	$this->getSelect();
		return $this;
	}

	public function setDateRange($from, $to)
	{
		$this->_reset()
		->_joinFields($from, $to);
		return $this;
	}

	public function load($printQuery = false, $logQuery = false)
	{
		if ($this->isLoaded()) {
			return $this;
		}
		parent::load($printQuery, $logQuery);
		return $this;
	}

	public function setStoreIds($storeIds)
	{
		return $this;
	}
	
}