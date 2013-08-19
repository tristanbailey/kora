<?php

class PPF_OPIM_Model_Observer
{
	public function onSalesOrderInvoiceRegister(Varien_Event_Observer $observer)
	{
		if (!($order = $observer->getEvent()->getOrder())) return false;

		$script_path = realpath(dirname(__FILE__).'/../').'/scripts/order_notify.php';
		$php = (file_exists('/usr/local/bin/php') ? '/usr/local/bin/php' : '/usr/bin/php');
		$cmd = 'nohup '.$php.' '.$script_path.' '.escapeshellarg($order->getId()).' '.escapeshellarg($order->getStore()->getId()).' > /dev/null 2>&1 & echo $!';
		exec($cmd, $op);
		//die('Running in the background (id: '.$order->getId().' / pid: '.((int) $op[0]).')');

		//Mage::helper('ppfopim/notifier')->notify('new_order', array('order' => $order));
	}
}

?>
