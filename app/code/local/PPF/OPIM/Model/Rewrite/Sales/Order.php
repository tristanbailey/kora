<?php

if (class_exists('Ebizmarts_SagePayServer_Model_Sales_Order'))
	{ abstract class PPF_OPIM_Model_Rewrite_Sales_Order_Abstract extends Ebizmarts_SagePayServer_Model_Sales_Order { } }
else { abstract class PPF_OPIM_Model_Rewrite_Sales_Order_Abstract extends Mage_Sales_Model_Order { } }

class PPF_OPIM_Model_Rewrite_Sales_Order extends PPF_OPIM_Model_Rewrite_Sales_Order_Abstract
{
	public function eIdExists($eID = null)
	{
		return $this->_getResource()->eIdExists($eID);
	}

	public function getExportIds($already_exported = false, $minId = null, $minDate = null, $status = null, $stores = null)
	{
		return $this->_getResource()->getExportIds($already_exported, $minId, $minDate, $status, $stores);
	}

	public function setPpfopimExportDate()
	{
		$this->setData('ppfopim_export_date', date('Y-m-d H:i:s'));
		return $this;
	}
}

?>
