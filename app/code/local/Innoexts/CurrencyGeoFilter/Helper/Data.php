<?php
/**
 * Innoexts
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the InnoExts Commercial License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://innoexts.com/commercial-license-agreement
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@innoexts.com so we can send you a copy immediately.
 * 
 * @category    Innoexts
 * @package     Innoexts_CurrencyGeoFilter
 * @copyright   Copyright (c) 2012 Innoexts (http://www.innoexts.com)
 * @license     http://innoexts.com/commercial-license-agreement  InnoExts Commercial License
 */

/**
 * Currency geo gilter helper
 *
 * @category   Innoexts
 * @package    Innoexts_CurrencyGeoFilter
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CurrencyGeoFilter_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get version helper
     * 
     * @return Innoexts_InnoCore_Helper_Version
     */
    public function getVersionHelper()
    {
        return Mage::helper('innocore')->getVersionHelper();
    }
    /**
     * Get config
     * 
     * @return Innoexts_CurrencyGeoFilter_Model_Config
     */
    public function getConfig()
    {
        return Mage::getSingleton('currencygeofilter/config');
    }
    /**
     * Get customer locator helper
     * 
     * @return Innoexts_CustomerLocator_Helper_Data
     */
    public function getCustomerLocatorHelper()
    {
        return Mage::helper('customerlocator');
    }
    /**
     * Get store
     * 
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore();
    }
    /**
     * Check if admin store is active
     * 
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->getStore()->isAdmin();
    }
    /**
     * Get websites
     * 
     * @return array of Mage_Core_Model_Website
     */
    public function getWebsites()
    {
        return Mage::app()->getWebsites();
    }
    /**
     * Get website by base currency code
     * 
     * @param string $currencyCode
     * @return Mage_Core_Model_Website
     */
    public function getWebsiteByBaseCurrencyCode($currencyCode)
    {
        $website = null;
        foreach ($this->getWebsites() as $_website) {
            if ($currencyCode == $_website->getBaseCurrencyCode()) {
                $website = $_website;
                break;
            }
        }
        return $website;
    }
    /**
     * Get website redirect route names
     * 
     * @return array of string
     */
    public function getWebsiteRedirectRouteNames()
    {
        return array('catalog', 'catalogsearch');
    }
    /**
     * Redirect to website by base currency code
     * 
     * @param Zend_Controller_Response_Http $responce
     * @param string $currencyCode
     * @return Innoexts_CurrencyGeoFilter_Helper_Data
     */
    public function redirectWebsiteByBaseCurrencyCode($responce = null, $currencyCode = null)
    {
        if (is_null($responce)) {
            $responce = Mage::app()->getResponse();
        }
        if (is_null($currencyCode)) {
            $currencyCode = $this->getStore()->getCurrentCurrencyCode();
        }
        $website = $this->getWebsiteByBaseCurrencyCode($currencyCode);
        if ($website && ($website->getId() != $this->getStore()->getWebsiteId())) {
            $store = $website->getDefaultStore();
            if ($store) {
                $store->setCurrentCurrencyCode($currencyCode);
                $url = $store->getUrl();
                $responce->setRedirect($url);
            }
        }
        return $this;
    }
}