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
 * Customer locator config
 * 
 * @category   Innoexts
 * @package    Innoexts_CustomerLocator
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CustomerLocator_Model_Config extends Varien_Object
{
    /**
     * Config path constants
     */
    const XML_PATH_CUSTOMER_LOCATOR_OPTION_ALLOW_LOCATION_MODIFICATION     = 'customerlocator/options/allow_location_modification';
    const XML_PATH_CUSTOMER_LOCATOR_OPTION_USE_DEFAULT_SHIPPING_ADDRESS    = 'customerlocator/options/use_default_shipping_address';
    const XML_PATH_CUSTOMER_LOCATOR_OPTION_REGION_ID_REQUIRED              = 'customerlocator/options/region_id_required';
    const XML_PATH_CUSTOMER_LOCATOR_OPTION_POSTCODE_REQUIRED               = 'customerlocator/options/postcode_required';
    const XML_PATH_CUSTOMER_LOCATOR_OPTION_CITY_REQUIRED                   = 'customerlocator/options/city_required';
    const XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_COUNTRY_ID            = 'customerlocator/default_location/country_id';
    const XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_REGION_ID             = 'customerlocator/default_location/region_id';
    const XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_POSTCODE              = 'customerlocator/default_location/postcode';
    const XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_CITY                  = 'customerlocator/default_location/city';
    /**
     * Check if location is allowed to be changed by customer
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isAllowLocationModification($store = null)
    {
        return Mage::getStoreConfigFlag(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_OPTION_ALLOW_LOCATION_MODIFICATION, $store);
    }
    /**
     * Check if default shipping address should be applied
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isUseDefaultShippingAddress($store = null)
    {
        return Mage::getStoreConfigFlag(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_OPTION_USE_DEFAULT_SHIPPING_ADDRESS, $store);
    }
    /**
     * Check if region id is required
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isRegionIdRequired($store = null)
    {
        return Mage::getStoreConfigFlag(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_OPTION_REGION_ID_REQUIRED, $store);
    }
    /**
     * Check if postal code is required
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isPostcodeRequired($store = null)
    {
        return Mage::getStoreConfigFlag(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_OPTION_POSTCODE_REQUIRED, $store);
    }
    /**
     * Check if city is required
     * 
     * @param mixed $store
     * @return boolean
     */
    public function isCityRequired($store = null)
    {
        return Mage::getStoreConfigFlag(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_OPTION_CITY_REQUIRED, $store);
    }
    /**
     * Get default location country identifier
     * 
     * @param mixed $store
     * @return boolean
     */
    public function getDefaultLocationCountryId($store = null)
    {
        return Mage::getStoreConfig(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_COUNTRY_ID, $store);
    }
    /**
     * Get default location region identifier
     * 
     * @param mixed $store
     * @return boolean
     */
    public function getDefaultLocationRegionId($store = null)
    {
        return Mage::getStoreConfig(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_REGION_ID, $store);
    }
    /**
     * Get default location postcode
     * 
     * @param mixed $store
     * @return boolean
     */
    public function getDefaultLocationPostcode($store = null)
    {
        return Mage::getStoreConfig(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_POSTCODE, $store);
    }
    /**
     * Get default location city
     * 
     * @param mixed $store
     * @return boolean
     */
    public function getDefaultLocationCity($store = null)
    {
        return Mage::getStoreConfig(Innoexts_CustomerLocator_Model_Config::XML_PATH_CUSTOMER_LOCATOR_DEFAULT_LOCATION_CITY, $store);
    }
    /**
     * Get default location
     * 
     * @param mixed $store
     * @return Varien_Object
     */
    public function getDefaultLocation($store = null)
    {
        $location = new Varien_Object();
        $location->setCountryId($this->getDefaultLocationCountryId($store))
            ->setRegionId($this->getDefaultLocationRegionId($store))
            ->setPostcode($this->getDefaultLocationPostcode($store))
            ->setCity($this->getDefaultLocationCity($store));
        return $location;
    }
}
?>