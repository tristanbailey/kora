<?php
/**
 * Product:     Pre-Order
 * Package:     Aitoc_Aitpreorder_1.1.23_332852
 * Purchase ID: MpavTKfgbcrRPJ88sBMFRhGGqYnzRuVQc7WznQkxPL
 * Generated:   2012-07-19 20:16:28
 * File path:   app/code/local/Aitoc/Aitpreorder/Block/Rewrite/CatalogSearchResult.data.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'Aitoc_Aitpreorder')){ jDqPRwDiEjSeZwjM('6e12b9bf6cfc47ff8f2fab9e810993b6'); ?><?php
/**
* @copyright  Copyright (c) 2009 AITOC, Inc. 
*/
class Aitoc_Aitpreorder_Block_Rewrite_CatalogSearchResult extends Mage_CatalogSearch_Block_Result
{
    public function getResultCount()
    {
        #$this->_getProductCollection()->load();
        return parent::getResultCount();
    }
} } 