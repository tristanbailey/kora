<?php

class PPF_OPIM_Model_Config_Source_Posttype extends Varien_Object
{
	public function toOptionArray()
	{
		return array('wsdl'    => Mage::helper('ppfopim')->__('WSDL'),
		             'post'    => Mage::helper('ppfopim')->__('Post')
		);
	}
}

?>
