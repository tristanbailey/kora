<?php

abstract class PPF_OPIM_Model_Api extends Mage_Api_Model_Resource_Abstract
{
	protected	$apiCaller;

	public function setApiCaller(Mage_Api_Model_Resource_Abstract $caller = null)
	{
		if (!is_object($caller)) return false;
		$this->apiCaller = $caller;
		return $this;
	}

	protected function _fault($code, $customMessage=null)
	{
		if (is_object($this->apiCaller))
			return $this->apiCaller->_fault($code, $customMessage);
		return parent::_fault($code, $customMessage);
	}

	protected function parseKeyValues($data = null)
	{
		if (!is_array($data)) return false;
		$keys = array_keys($data);
		$values = array_values($data);

		$info = array();
		for ($i = 0; $i < count($keys); $i++)
			$info[$i] = array('key' => $keys[$i], 'value' => $values[$i]);
		return $info;
	}

	protected function getAttributeOptions($obj, $code)
	{
		if (!is_object($obj) || empty($code)) return false;

		$rslt = $obj->getTypeInstance(true);
		if (is_object($rslt))
			$atts = $obj->getTypeInstance(true)->getEditableAttributes($obj);
		else if (is_callable(array($obj, 'getAttributes')))
			$atts = $obj->getAttributes();
		else return false;
		foreach ($atts as $attribute)
		{
			if ($attribute->getAttributeCode() != $code) continue;
			$options = array();
			foreach ($attribute->getSource()->getAllOptions() as $option)
			{
				if (empty($option['value'])) continue;
				$options[$option['value']] = $option['label'];
			}
			return $options;
		}
		return false;
	}
}

?>
