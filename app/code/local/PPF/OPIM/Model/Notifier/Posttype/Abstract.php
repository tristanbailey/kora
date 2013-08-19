<?php

class PPF_OPIM_Model_Notifier_Posttype_Abstract extends Mage_Core_Model_Abstract
{
	const MODULE_PATH_BASE          = 'ppfopim';
	protected	$_client;
	protected	$_exception;

	public function getConfigPath($key = null)
	{
		if (empty($key)) $key = $this->getConfigKey();
		return self::MODULE_PATH_BASE.'/'.$key.'/';
	}
	public function getPostUrl()
	{
		return Mage::getStoreConfig($this->getConfigPath('notifier').'posturl', $this->getStore());
	}
	public function getPostType()
	{
		return Mage::getStoreConfig($this->getConfigPath('notifier').'posttype', $this->getStore());
	}
	public function getTimeOut()
	{
		$v = Mage::getStoreConfig($this->getConfigPath('notifier').'timeout', $this->getStore());
		return (($v > 0) ? $v : 30);
	}
	public function getRetry()
	{
		$v = Mage::getStoreConfig($this->getConfigPath('notifier').'retry', $this->getStore());
		return (($v > 0) ? $v : 1);
	}
	public function getRetryInterval()
	{
		$v = Mage::getStoreConfig($this->getConfigPath('notifier').'retryinterval', $this->getStore());
		return (($v > 0) ? $v : 5);
	}
	public function getPostEncoding()
	{
		return Mage::getStoreConfig($this->getConfigPath('notifier').'postencoding', $this->getStore());
	}
	public function getKey()
	{
		return Mage::getStoreConfig($this->getConfigPath('notifier').'key', $this->getStore());
	}
	public function getPostCall()
	{
		return Mage::getStoreConfig($this->getConfigPath().'postcall', $this->getStore());
	}
	public function getParams()
	{
		$p = null;
		parse_str(Mage::getStoreConfig($this->getConfigPath().'params', $this->getStore()), $p);
		if (!is_array($p)) $p = array();
		$p['signature'] = Mage::helper('ppfopim/notifier')->getSignature($this->getKey(), $this->getPostEncoding(), time().'|', $this->getStore());
		return $p;
	}

	public function notify($params)
	{
		return false;
	}

	protected function callWebservice($request, $expected = null)
	{
		return false;
	}

	protected function getClient()
	{
		return false;
	}
}

?>
