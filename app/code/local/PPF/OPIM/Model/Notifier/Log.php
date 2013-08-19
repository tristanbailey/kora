<?php

class PPF_OPIM_Model_Notifier_Log extends Mage_Core_Model_Abstract
{
	protected function _construct()
	{
		$this->_init('ppfopim/notifier_log');
	}

	public function setParams($params)
	{
		if (is_array($params) || is_object($params))
			$params = print_r($params, true);
		$this->setData('params', $params);
		return $this;
	}

	public function setResponse($response)
	{
		if (is_array($response) || is_object($response))
			$response = print_r($response, true);
		$this->setData('response', $response);
		return $this;
	}
}

?>
