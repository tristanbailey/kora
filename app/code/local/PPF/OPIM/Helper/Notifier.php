<?php

class PPF_OPIM_Helper_Notifier extends Mage_Core_Helper_Data
{
	const MODULE_PATH_BASE          = 'ppfopim';

	public function isEnabled($store = null)
	{
		$enabled = ((Mage::getStoreConfig(self::MODULE_PATH_BASE.'/notifier/active', $store) == '1') ? true : false);
		return $enabled;
	}

	public function getSignature($key, $method, $prefix = null, $store = null)
	{
		$signature = Mage::getStoreConfig(self::MODULE_PATH_BASE.'/notifier/signature', $store);
		return $this->encodeString($key, $method, $prefix.$signature);
	}

	public function encodeString($key, $method, $string)
	{
		$rc4 = Mage::getModel('ppfopim/crypt_rc4');
		$rc4->setKey($key);
		$rc4->setMethod($method);
		$enc = $rc4->encrypt($string);

		$string = null;
		if ($method == 'hexadecimal')
		{
			for ($i = 0; $i < strlen($enc); $i++)
				$string .= dechex(ord($enc[$i])).' ';
		}
		else $string = urlencode($enc);
		return trim($string);
	}

	public function notify($type, $params = null)
	{
		$store = ((!is_array($params) || !array_key_exists('store', $params) || !is_object($params['store'])) ? Mage::app()->getStore() : $params['store']);
		if ($this->isEnabled($store) !== true) return false;
		switch ($type)
		{
			case 'new_order':
				return $this->new_order($params, $store); break;
			default: break;
		}
		return false;
	}

	public function new_order($params, $store)
	{
		if (!is_array($params) || !array_key_exists('order', $params) || !is_object($params['order']) || $params['order']->getId() < 0) return false;

		$p = array('order_id' => $params['order']->getId());
		$type = Mage::getStoreConfig(self::MODULE_PATH_BASE.'/notifier/posttype', $store);
		if (empty($type)) return false;
		$notifier = Mage::getModel('ppfopim/notifier_posttype_'.strtolower($type))->setConfigKey('neworder')->setStore($store);
		$response = $notifier->notify($p);

		$p = array_merge($p, $notifier->getParams());
		$log = Mage::getModel('ppfopim/notifier_log');
		$log->setAction('neworder')
		    ->setPosturl($notifier->getPostUrl())
		    ->setPosttype($notifier->getPostType())
		    ->setPostcall($notifier->getPostCall())
		    ->setParams($p)
		    ->setResponse($response)
		    ->save();
		return true;
	}
}

?>
