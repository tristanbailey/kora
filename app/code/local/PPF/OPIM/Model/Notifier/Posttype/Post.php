<?php

class PPF_OPIM_Model_Notifier_Posttype_Post extends PPF_OPIM_Model_Notifier_Posttype_Abstract
{
	public function notify($params)
	{
		$params = array_merge($params, $this->getParams());
		return $this->callWebservice($params);
	}

	protected function callWebservice($request)
	{
		$client = $this->getClient();
		if (!$client) return false;

		curl_setopt($client, CURLOPT_POST, 1);
		curl_setopt($client, CURLOPT_POSTFIELDS, $request);

		$tries = 0;
		$result = false;
		$max_tries = $this->getRetry();
		while ($tries <= $max_tries)
		{
			$tries++;
			try
			{
				@$result = curl_exec($client);
				if ($result) break;
			}
			catch (SoapFault $e)
			{
				$this->_exception = $e;
				$result = null;
				if ($max_tries >= 1) sleep($this->getRetryInterval());
			}
		}
		if (!$result && is_object($this->_exception)) $result = $this->_exception->getMessage();
		return $result;
	}

	protected function getClient()
	{
		if ($this->_client) return $this->_client;
		try
		{
			$c = curl_init();
			curl_setopt($c, CURLOPT_URL, $this->getPostUrl());
			curl_setopt($c, CURLOPT_HEADER, 0);
			curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($c, CURLOPT_TIMEOUT, $this->getTimeOut());
			curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($c, CURLOPT_SSL_VERIFYHOST, false);
			@$this->_client = $c;
		}
		catch (Exception $e)
		{
			$this->_exception = $e;
			$this->_client = null;
		}
		return $this->_client;
	}
}

?>
