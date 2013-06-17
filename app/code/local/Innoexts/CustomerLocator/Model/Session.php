<?php
/**
 * Innoexts
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@innoexts.com so we can send you a copy immediately.
 * 
 * @category    Innoexts
 * @package     Innoexts_CustomerLocator
 * @copyright   Copyright (c) 2012 Innoexts (http://www.innoexts.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Customer locator session
 * 
 * @category   Innoexts
 * @package    Innoexts_CustomerLocator
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CustomerLocator_Model_Session extends Mage_Core_Model_Session_Abstract
{
    /**
     * Address
     * 
     * @var Varien_Object
     */
    protected $_address;
    /**
     * Constructor
     */
    public function __construct()
    {
        $namespace = 'customerlocator';
        if ($this->getCustomerConfigShare()->isWebsiteScope()) {
            $namespace .= '_' . (Mage::app()->getStore()->getWebsite()->getCode());
        }
        $this->init($namespace);
        Mage::dispatchEvent('customerlocator_session_init', array('customerlocator_session' => $this));
    }
    /**
     * Retrieve customer sharing configuration model
     *
     * @return Mage_Customer_Model_Config_Share
     */
    public function getCustomerConfigShare()
    {
        return Mage::getSingleton('customer/config_share');
    }
    /**
     * Get core helper
     * 
     * @return Innoexts_InnoCore_Helper_Data
     */
    protected function getCoreHelper()
    {
        return Mage::helper('innocore');
    }
    /**
     * Get address helper
     * 
     * @return Innoexts_InnoCore_Helper_Address
     */
    protected function getAddressHelper()
    {
        return $this->getCoreHelper()->getAddressHelper();
    }
    /**
     * Get geo ip helper
     * 
     * @return Innoexts_GeoIp_Helper_Data
     */
    protected function getGeoIpHelper()
    {
        return Mage::helper('geoip');
    }
    /**
     * Get customer locator config
     * 
     * @return Innoexts_CustomerLocator_Model_Config
     */
    protected function getCustomerLocatorConfig()
    {
        return Mage::getSingleton('customerlocator/config');
    }
    /**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
    protected function getStore()
    {
        return Mage::app()->getStore();
    }
    /**
     * Get current address
     * 
     * @return Varien_Object
     */
    protected function _getAddress()
    {
        if (is_null($this->_address)) {
            $address = new Varien_Object();
            $address->setCountryId($this->getCountryId());
            $address->setRegionId($this->getRegionId());
            $address->setRegion($this->getRegion());
            $address->setCity($this->getCity());
            $address->setPostcode($this->getPostcode());
            $this->_address = $address;
        }
        return $this->_address;
    }
    /**
     * Check if address is empty
     * 
     * @return bool
     */
    public function isAddressEmpty()
    {
        $this->_getAddress();
        return $this->getAddressHelper()->isEmpty($this->_address);
    }
    /**
     * Get ip address
     * 
     * @return string
     */
    protected function getIp()
    {
        $ip = Mage::helper('core/http')->getRemoteAddr();
        return ($ip) ? long2ip(ip2long($ip)) : null;
    }
    /**
     * Get geo ip address
     * 
     * @return Varien_Object
     */
    protected function getGeoIpAddress()
    {
        $address = null;
        $addressHelper = $this->getAddressHelper();
        $ip = $this->getIp();
        if ($ip) {
            $_address = $this->getGeoIpHelper()->getAddressByIp($ip);
            if ($_address) {
                $_address = $addressHelper->cast($_address);
                if (!$addressHelper->isEmpty($_address)) {
                    $address = $_address;
                }
            }
        }
        return $address;
    }
    /**
     * Get customer session
     * 
     * @return Mage_Customer_Model_Session
     */
    protected function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }
    /**
     * Check if customer is logged in
     * 
     * @return bool
     */
    protected function isCustomerLoggedIn()
    {
        return $this->getCustomerSession()->isLoggedIn();
    }
    /**
     * Get customer
     * 
     * @return Mage_Customer_Model_Customer
     */
    protected function getCustomer()
    {
        return $this->getCustomerSession()->getCustomer();
    }
    /**
     * Get customer default address
     * 
     * @return Varien_Object
     */
    protected function getCustomerDefaultAddress()
    {
        $address = null;
        $addressHelper = $this->getAddressHelper();
        if ($this->isCustomerLoggedIn()) {
            $_address = $this->getCustomer()->getDefaultShippingAddress();
            if ($_address) {
                $_address = $addressHelper->cast($_address);
                if (!$addressHelper->isEmpty($_address)) {
                    $address = $_address;
                }
            }
        }
        return $address;
    }
    /**
     * Get default address
     * 
     * @return Varien_Object
     */
    protected function getDefaultAddress()
    {
        $addressHelper = $this->getAddressHelper();
        $address = $this->getCustomerLocatorConfig()->getDefaultLocation($this->getStore());
        $address = $addressHelper->cast($address);
        return $address;
    }
    /**
     * Locate address
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
    protected function locateAddress()
    {
        $address = null;
        if ($this->getCustomerLocatorConfig()->isUseDefaultShippingAddress($this->getStore())) {
            $address = $this->getCustomerDefaultAddress();
        }
        if (!$address) {
            $address = $this->getGeoIpAddress();
        }
        if (!$address) {
            $address = $this->getDefaultAddress();
        }
        $this->setAddress($address);
        return $this;
    }
    /**
     * Set shipping address
     * 
     * @param Varien_Object $shippingAddress
     * @return Innoexts_CustomerLocator_Model_Session
     */
    public function setAddress($address)
    {
        $address = $this->getAddressHelper()->cast($address);
        $this->setCountryId($address->getCountryId());
        $this->setRegionId($address->getRegionId());
        $this->setRegion($address->getRegion());
        $this->setCity($address->getCity());
        $this->setPostcode($address->getPostcode());
        $this->_address = $address;
        return $this;
    }
    /**
     * Retrieve address
     * 
     * @return Varien_Object
     */
    public function getAddress()
    {
        $this->_getAddress();
        if ($this->isAddressEmpty()) {
            $this->locateAddress();
        }
        return $this->_address;
    }
    /**
     * Unset address
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
    public function unsetAddress()
    {
        $this->setCountryId(null);
        $this->setRegionId(null);
        $this->setRegion(null);
        $this->setCity(null);
        $this->setPostcode(null);
        $this->_address = null;
        return $this;
    }
}