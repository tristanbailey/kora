<?php

class PPF_OPIM_Model_Config_Source_Encoding extends Varien_Object
{
	public function toOptionArray()
	{
		return array('hexadecimal'    => Mage::helper('ppfopim')->__('Hexadecimal'),
		             'urlencode'      => Mage::helper('ppfopim')->__('Url Encode')
		);
	}
}

?>
