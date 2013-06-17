<?php
/**
 * Product:     Pre-Order
 * Package:     Aitoc_Aitpreorder_1.1.23_332852
 * Purchase ID: MpavTKfgbcrRPJ88sBMFRhGGqYnzRuVQc7WznQkxPL
 * Generated:   2012-07-19 20:16:28
 * File path:   app/code/local/Aitoc/Aitpreorder/Model/Rewrite/SourceBackorders.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'Aitoc_Aitpreorder')){ ariVfprqOaYZkpae('e1881b6ce888fe7c0be884bc9f832391'); ?><?php
/**
 * @copyright  Copyright (c) 2011 AITOC, Inc.
 */
class Aitoc_Aitpreorder_Model_Rewrite_SourceBackorders extends Mage_CatalogInventory_Model_Source_Backorders
{
	const BACKORDERS_YES_PREORDERS = 30;
	public function toOptionArray()
    {
        $options = parent::toOptionArray();

		$options[] = array(
			'value' => self::BACKORDERS_YES_PREORDERS,
			'label'=>Mage::helper('cataloginventory')->__('Preorders')
		);

		return $options;
    }
}
 } ?>