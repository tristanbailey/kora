<?php

class PPF_OPIM_Model_Mysql4_Rewrite_Sales_Order extends Mage_Sales_Model_Mysql4_Order
{
	public function eIdExists($eID)
	{
		return $this->_getReadAdapter()->fetchOne('select entity_id from '.$this->getMainTable().' where entity_id=?', $eID);
	}

	public function getExportIds($already_exported = false, $minId = null, $minDate = null, $status = null, $stores = null)
	{
		$select = $this->_getReadAdapter()->select();
		$select->from($this->getMainTable(), 'entity_id')->distinct(true);
		if ($already_exported !== true)
			$select->where('ppfopim_export_date IS NULL');
		if ((int) $minId > 0)
			$select->where('entity_id>=?', $minId);
		if (!empty($minDate))
			$select->where('created_at>=?', date('Y-m-d H:i:s', strtotime($minDate)));
		if (!empty($status))
			$select->where('status=?', $status);
		if (is_array($stores) && count($stores) > 0)
			$select->where('store_id IN (?)', $stores);
		return $this->_getReadAdapter()->fetchCol($select);
	}
}

?>
