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
	$installer = $this;
	 $connection = $installer->getConnection();
	$installer->startSetup();
	
	$installer->run(" 
		SET foreign_key_checks = 0;
		DROP TABLE IF EXISTS {$this->getTable('criteek_reviewsystem_category')};
		DROP TABLE IF EXISTS {$this->getTable('criteek_reviewsystem_queue')};
		SET foreign_key_checks = 1;
	");
	
	$setup = new Mage_Core_Model_Config();
  $setup->saveConfig('reviewsystem/product_review_config/criteek_order_sent', '0', 'default', 0);
	
	$installer->endSetup();
?>