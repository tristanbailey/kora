<?php

class PPF_OPIM_Model_Notifier_Posttype_Wsdl extends PPF_OPIM_Model_Notifier_Posttype_Abstract
{
	public function notify($params)
	{
		$params = array_merge($params, $this->getParams());
		$request = array($params);
		return $this->callWebservice($request);
	}

	protected function callWebservice($request)
	{
		$client = $this->getClient();
		if (!$client) return false;

		$tries = 0;
		$result = false;
		$max_tries = $this->getRetry();
		while ($tries <= $max_tries)
		{
			$tries++;
			try
			{
				@$result = get_object_vars(call_user_func(array($client, $this->getPostCall()), $request));
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
		return ((is_array($result)) ? $result : null);
	}

	protected function getClient()
	{
		if ($this->_client) return $this->_client;
		$options = array('connection_timeout' => $this->getTimeOut());
		try
		{
			@$this->_client = new SoapClient($this->getPostUrl(), $options);
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
