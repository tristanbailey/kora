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
 * Customer address block
 * 
 * @category   Innoexts
 * @package    Innoexts_CustomerLocator
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CustomerLocator_Block_Customer_Address extends Mage_Core_Block_Template
{
    /**
     * Address
     * 
     * @var Varien_Object
     */
    protected $_address;
    /**
     * Retrieve customer locator helper
     *
     * @return Innoexts_CustomerLocator_Helper_Data
     */
    protected function getCustomerLocatorHelper()
    {
        return Mage::helper('customerlocator');
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
     * Check if location is allowed to be changed by a customer
     * 
     * @return bool
     */
    public function isAllowLocationModification()
    {
        return $this->getCustomerLocatorConfig()->isAllowLocationModification($this->getStore());
    }
    /**
     * Get address
     *
     * @return Varien_Object
     */
    public function getAddress()
    {
        if (is_null($this->_address)) {
            $this->_address = $this->getCustomerLocatorHelper()->getCustomerAddress();
        }
        return $this->_address;
    }
    /**
     * Get country identifier
     * 
     * @return string
     */
    public function getCountryId()
    {
        return $this->getAddress()->getCountryId();
    }
    /**
     * Get region identifier
     * 
     * @return string
     */
    public function getRegionId()
    {
        return $this->getAddress()->getRegionId();
    }
    /**
     * Get region
     * 
     * @return string
     */
    public function getRegion()
    {
        return $this->getAddress()->getRegion();
    }
    /**
     * Get city
     * 
     * @return string
     */
    public function getCity()
    {
        return $this->getAddress()->getCity();
    }
    /**
     * Get postal code
     * 
     * @return string
     */
    public function getPostcode()
    {
        return $this->getAddress()->getPostcode();
    }
    /**
     * Check if region is required
     *
     * @return bool
     */
    public function isRegionRequired()
    {
        return $this->getCustomerLocatorConfig()->isRegionIdRequired($this->getStore());
    }
    /**
     * Check if city is required
     * 
     * @return bool
     */
    public function isCityRequired()
    {
        return $this->getCustomerLocatorConfig()->isCityRequired($this->getStore());
    }
    /**
     * Check if postal code is required
     *
     * @return bool
     */
    public function isPostcodeRequired()
    {
        return $this->getCustomerLocatorConfig()->isPostcodeRequired($this->getStore());
    }
}