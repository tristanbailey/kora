<?php

$orderId = ((isset($argc) && isset($argv) && $argc == 3) ? $argv[1] : null);
$storeId = ((isset($argc) && isset($argv) && $argc == 3) ? $argv[2] : null);
if (!is_numeric($orderId) || !is_numeric($storeId)) die();

if (!defined('MAGE_PATH')) define('MAGE_PATH', realpath(dirname(__FILE__).'/../../../../../../'));
chdir(MAGE_PATH);
sleep(10);

require 'app/Mage.php';

if (!Mage::isInstalled()) {
    echo "Application is not installed yet, please complete install wizard first.";
    exit;
}

// Only for urls
// Don't remove this
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = MAGE_PATH.'/index.php';

Mage::app('admin')->setUseSessionInUrl(false);

$store = Mage::getModel('core/store')->load($storeId);
$order = Mage::getModel('sales/order')->setStore($store)->load($orderId);
try {
	$helper = Mage::helper('ppfopim/notifier');
	$helper->notify('new_order', array('order' => $order, 'store' => $store));
} catch (Exception $e) { Mage::printException($e); }

?>
