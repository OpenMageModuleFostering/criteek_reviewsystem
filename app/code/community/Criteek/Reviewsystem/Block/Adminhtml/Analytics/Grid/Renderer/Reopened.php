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
 
class Criteek_Reviewsystem_Block_Adminhtml_Analytics_Grid_Renderer_Reopened extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
 	 public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    } 
    protected function _getValue(Varien_Object $row)
    {       
       $val = $row->getData($this->getColumn()->getIndex());
	  if($val==1)
	  {$val="Yes";
	  }else{
		  $val="No";
	  }
        return $val;
    }
}