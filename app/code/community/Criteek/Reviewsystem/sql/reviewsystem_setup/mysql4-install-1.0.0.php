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
	$connection->insert($installer->getTable('cms/block'), array(
		'title'             => 'Criteek Js Code',  
		'identifier'        => 'criteek-js-code',
		'content'           => '{{block type="core/template" template="reviewsystem/reviewcode.phtml"}}',
		'creation_time'     => now(),
		'update_time'       => now(),
	));
	
	$connection->insert($installer->getTable('cms/block_store'), array(
		'block_id'   => $connection->lastInsertId(),
		'store_id'  => 0
	));
	
	$installer->run(" 
	-- DROP TABLE IF EXISTS {$this->getTable('criteek_reviewsystem_category')};
	CREATE TABLE {$this->getTable('criteek_reviewsystem_category')} (
		`id` int(11) unsigned NOT NULL auto_increment,
		`category_id` int(10) NOT NULL,
		`interval` int(10)  NULL,
		PRIMARY KEY (`id`),
		KEY `IDX_CRITEEK_REVIEWSYSTEM_CATEGORY_CATEGORY_ID` (`category_id`),
		CONSTRAINT `FK_W3C_HTSH_CTGR_CTGR_ID_CAT_CTGR_ENTT_ENTT_ID` FOREIGN KEY (`category_id`) REFERENCES `{$this->getTable('catalog_category_entity')}` (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
		 
		-- 	DROP TABLE IF EXISTS {$this->getTable('criteek_reviewsystem_queue')};
		CREATE TABLE  {$this->getTable('criteek_reviewsystem_queue')} (
		`id` int( 10 ) unsigned NOT NULL auto_increment,
		`product_id` int( 10 ) unsigned NOT NULL DEFAULT 0,
		`product_name` varchar (64) default NULL,
		`product_sku` varchar (64) default NULL,
		`order_id` int( 10 ) unsigned NOT NULL DEFAULT 0,
		`email_time` datetime NOT NULL default '0000-00-00 00:00:00',
		`customer_email` varchar (64) default NULL,
		`customer_name` varchar (64) default NULL,
		`status` tinyint (10) unsigned NOT NULL DEFAULT 0,
		`uploaded_review` tinyint (10) unsigned NOT NULL DEFAULT 0,
		`reopened` tinyint (10) unsigned NOT NULL DEFAULT 0,
		PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;	
				");
				
		$statusTable        = $installer->getTable('sales/order_status');
		$statusStateTable   = $installer->getTable('sales/order_status_state');
		//$statusLabelTable   = $installer->getTable('sales/order_status_label');
		
		$data = array(
			array('status' => 'shipped', 'label' => 'Shipped')
		);
		
		$installer->getConnection()->insertArray($statusTable, array('status', 'label'), $data);
		$installer->getConnection()->insertArray(
    	$statusStateTable,  array('status', 'state', 'is_default'),array( array(
      	'status' => 'shipped', 
        'state' => 'complete', 
        'is_default' => 0
        )
      )
   	);
?>