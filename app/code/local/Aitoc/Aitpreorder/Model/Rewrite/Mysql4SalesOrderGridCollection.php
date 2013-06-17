<?php
/**
 * Product:     Pre-Order
 * Package:     Aitoc_Aitpreorder_1.1.23_332852
 * Purchase ID: MpavTKfgbcrRPJ88sBMFRhGGqYnzRuVQc7WznQkxPL
 * Generated:   2012-07-19 20:16:28
 * File path:   app/code/local/Aitoc/Aitpreorder/Model/Rewrite/Mysql4SalesOrderGridCollection.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'Aitoc_Aitpreorder')){ mMhZCiMoDmYygima('57a021aecd6ddd64bdc48770853ef867'); ?><?php
/**
 * @copyright  Copyright (c) 2009 AITOC, Inc. 
 */
class Aitoc_Aitpreorder_Model_Rewrite_Mysql4SalesOrderGridCollection extends Mage_Sales_Model_Mysql4_Order_Grid_Collection
{
	protected function _afterLoad()
    {
    	parent::_afterLoad();
    	foreach ($this->getItems() as $item)
    	{
			if(!Mage::helper('aitpreorder')->checkSynchronization($item->getStatus(),$item->getStatusPreorder()))
			{
				$item->setStatusPreorder($item->getStatus());
				$item->save();
			}
			$item->setStatus($item->getStatusPreorder());
    	}
    	return $this;
    }
} } 