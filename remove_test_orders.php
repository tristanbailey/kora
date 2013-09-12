<?php

require 'app/Mage.php';
Mage::app('admin')->setUseSessionInUrl(false);                                                                                                                 
//replace your own orders numbers here:
$test_order_ids=array(
	'600000033',
	'600000032',
	'600000031',
	'600000030',
	'600000029',
	'600000028',
	'600000027',
	'600000026',
	'600000025',
	'600000024',
	'600000023',
	'600000022',
	'600000021',
	'600000020',
	'600000019',
	'600000018',
	'600000017',
	'600000016',
	'600000015',
	'600000014',
	'600000013',
	'600000012',
	'600000011',
	'600000010',
	'600000009',
	'600000008',
	'600000007',
	'600000006',
	'600000005',
	'600000004',
	'600000003',
	'600000002',
	'600000001'
);
foreach($test_order_ids as $id){
    try{
        Mage::getModel('sales/order')->loadByIncrementId($id)->delete();
        echo "order #".$id." is removed".PHP_EOL;
    }catch(Exception $e){
        echo "order #".$id." could not be remvoved: ".$e->getMessage().PHP_EOL;
    }
}
echo "complete."

?>

