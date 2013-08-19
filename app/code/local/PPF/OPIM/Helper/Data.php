<?php

class PPF_OPIM_Helper_Data extends Mage_Core_Helper_Data
{
	const MODULE_PATH_BASE          = 'ppfopim';

	public function isAPIEnabled($store_id = null)
	{
		return Mage::getStoreConfig(self::MODULE_PATH_BASE.'/general/active', $store_id);
	}

	public function getOrderStoreId($store_id = null)
	{
		return Mage::getStoreConfig(self::MODULE_PATH_BASE.'/general/store_id', $store_id);
	}

	public function getExtraFields($store_id = null)
	{
		$fields = trim(Mage::getStoreConfig(self::MODULE_PATH_BASE.'/general/extra_fields', $store_id));
		return (!empty($fields) ? explode(';', $fields) : false);
	}

	public function checkStatus($ss = null)
	{
		if (!is_object($ss)) return false;
		$active = (($this->isAPIEnabled() == '1') ? true : false);
		if ($active !== true) $ss->_fault('module_error', 'Site is under maintenance.');
		return $active;
	}

	public function objToArray($obj = null)
	{
		if (!is_object($obj)) return $obj;
		$obj = get_object_vars($obj);
		return array_map(array($this, 'objToArray'), $obj);
	}

	public function clean_data($data = null)
	{
		if (!is_array($data)) return false;
		foreach ($data as $key => $value)
		{
			if (!is_array($value) && !is_object($value) && (!empty($value) || $value == '0')) continue;
			if (is_object($value)) $value = $this->objToArray($value);
			if (is_array($value) && count($value) > 0)
			{
				$value = $this->clean_data($value);
				if (is_array($value) && count($value) > 0)
				{
					$data[$key] = $value;
					continue;
				}
			}
			unset($data[$key]);
		}
		return $data;
	}

	protected function stores()
	{
		static	$ws;
		if (isset($ws)) return $ws;
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$query = 'SELECT `store_id`, `code`, `website_id` FROM `core_store`';
		$r = $w->query($query);
		if ($r->rowCount())
		{
			$ws = array();
			$rslt = $r->fetchAll();
			foreach ($rslt as $data)
				$ws[$data['code']] = $data;
			return $ws;
		}
		return false;
	}

	public function getSetId($name = null)
	{
		static	$set;
		if (!is_array($set)) $set = array();
		if (array_key_exists($name, $set)) return $set[$name];
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$query = 'SELECT `attribute_set_id` FROM `eav_attribute_set` WHERE `attribute_set_name`='.$w->quoteInto('?', $name);
		$r = $w->query($query);
		if ($r->rowCount())
		{
			$rslt = $r->fetch();
			$set[$name] = $rslt['attribute_set_id'];
			return $set[$name];
		}
		return false;
	}

	public function product_types()
	{
		$options = Mage::getModel('catalog/product_type')->getOptionArray();
		if (is_array($options))
			return array_keys($options);
		return array('simple');
	}

	public function customer_groupid($name)
	{
		static	$groups;
		if (!is_array($groups)) $groups = array();
		if (array_key_exists($name, $groups)) return $groups[$name];
		$w = Mage::getSingleton('core/resource')->getConnection('core_write');
		$query = 'SELECT `customer_group_id` FROM `customer_group` WHERE `customer_group_code`='.$w->quoteInto('?', $name);
		$r = $w->query($query);
		if ($r->rowCount())
		{
			$rslt = $r->fetch();
			$groups[$name] = $rslt['customer_group_id'];
			return $groups[$name];
		}
		return false;
	}

	public function compMagentoVer($version)
	{
		return (version_compare($version, Mage::getVersion()) >= 0);
	}
}

?>
