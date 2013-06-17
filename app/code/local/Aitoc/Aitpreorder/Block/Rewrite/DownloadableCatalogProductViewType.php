<?php
/**
 * Product:     Pre-Order
 * Package:     Aitoc_Aitpreorder_1.1.23_332852
 * Purchase ID: MpavTKfgbcrRPJ88sBMFRhGGqYnzRuVQc7WznQkxPL
 * Generated:   2012-07-19 20:16:28
 * File path:   app/code/local/Aitoc/Aitpreorder/Block/Rewrite/DownloadableCatalogProductViewType.data.php
 * Copyright:   (c) 2012 AITOC, Inc.
 */
?>
<?php if(Aitoc_Aitsys_Abstract_Service::initSource(__FILE__,'Aitoc_Aitpreorder')){ ejpEThjwkeYBDhem('e15febb1ab937c542fa8fe68e476e387'); ?><?php
/**
* @copyright  Copyright (c) 2009 AITOC, Inc. 
*/

class Aitoc_Aitpreorder_Block_Rewrite_DownloadableCatalogProductViewType extends Mage_Downloadable_Block_Catalog_Product_View_Type
{
    protected function _toHtml()
    {               
        if($this->getProduct()->getPreorder())
        {
            $result=parent::_toHtml();
            $descript=$this->getProduct()->getPreorderdescript();
            if($descript=="")
            {
                $descript=$this->__('Pre-order');
            }
            $result=str_ireplace($this->__('In Stock')," ".$descript,$result);
            $result=str_ireplace($this->__('Out Stock')," ".$this->__('not Available'),$result);
            return($result);
        }
        else
        {   
            return (parent::_toHtml());
        }
    }
} } 