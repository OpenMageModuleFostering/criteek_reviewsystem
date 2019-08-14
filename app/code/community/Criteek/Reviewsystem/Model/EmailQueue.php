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

class Criteek_Reviewsystem_Model_EmailQueue extends Mage_Core_Model_Abstract {
 public function _construct() {
         parent::_construct();
        $this->_init('reviewsystem/emailQueue');
    }
	}