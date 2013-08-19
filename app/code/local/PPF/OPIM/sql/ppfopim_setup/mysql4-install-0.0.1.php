<?php

$installer = $this;

$installer->startSetup();

$installer->run("DELETE FROM `{$this->getTable('core_config_data')}` WHERE `path` LIKE 'ppfopim/%'");

$configValues = array('ppfopim/general/active'         => '1',
                      'ppfopim/general/store_id'       => '',
                      'ppfopim/general/extra_fields'   => '',
                      'ppfopim/notifier/active'        => '0',
                      'ppfopim/notifier/posturl'       => 'https://phytosciencecrm.co.uk/api/new_order.php',
                      'ppfopim/notifier/posttype'      => 'post',
                      'ppfopim/notifier/timeout'       => '30',
                      'ppfopim/notifier/retry'         => '1',
                      'ppfopim/notifier/retryinterval' => '5',
                      'ppfopim/notifier/postencoding'  => 'hexadecimal',
                      'ppfopim/notifier/key'           => '',
                      'ppfopim/notifier/signature'     => 'MagentoAPI',
                      'ppfopim/neworder/postcall'      => 'newOrder',
                      'ppfopim/neworder/params'        => 'store_id=');

foreach ($configValues as $configPath => $configValue)
	$installer->setConfigData($configPath, $configValue);

$installer->run("
CREATE TABLE IF NOT EXISTS `{$installer->getTable('ppfopim/notifier_log')}` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`action` VARCHAR(32) NOT NULL,
	`posturl` VARCHAR(255) NOT NULL,
	`posttype` VARCHAR(16) NOT NULL,
	`postcall` VARCHAR(64) NOT NULL,
	`params` TINYTEXT DEFAULT NULL,
	`response` TEXT DEFAULT NULL,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB CHARACTER SET utf8;
");

$installer->run("
ALTER TABLE `{$installer->getTable('sales_flat_order')}` ADD `ppfopim_export_date` DATETIME DEFAULT NULL;
ALTER TABLE `{$installer->getTable('sales_flat_order')}` ADD KEY `IDX_PPFOEXPORTDATE` (`ppfopim_export_date`);
");

$installer->endSetup();

?>
