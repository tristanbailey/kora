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
 * Currency model
 *
 * @category   Innoexts
 * @package    Innoexts_CurrencyGeoFilter
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CurrencyGeoFilter_Model_Core_Store extends Mage_Core_Model_Store
{
    /**
     * Get currency geo filter helper
     * 
     * @return Innoexts_CurrencyGeoFilter_Helper_Data
     */
    protected function getCurrencyGeoFilterHelper()
    {
        return Mage::helper('currencygeofilter');
    }
    /**
     * Get country allowed store currency codes
     *
     * @param string $countryId
     * @return array
     */
    public function getCountryAvailableCurrencyCodes($countryId = null)
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        if ($helper->getConfig()->isEnabled($this)) {
            $codes = array();
            if (!$countryId) {
                $address = $helper->getCustomerLocatorHelper()->getCustomerAddress();
                if ($address) {
                    $countryId = $address->getCountryId();
                }
            }
            if ($countryId) {
                $codes = Mage::getModel('currencygeofilter/currency')->getCountryCurrencies($countryId);
            }
            if (!count($codes)) {
                $codes = array($this->getDefaultCurrencyCode(), );
            }
            return $codes;
        } else {
            return $this->getAvailableCurrencyCodes(true);
        }
    }
    /**
     * Set current country
     * 
     * @param string $countryId
     * @return Innoexts_CurrencyGeoFilter_Model_Core_Store
     */
    public function setCurrentCountry($countryId)
    {
        $countryId = strtoupper($countryId);
        $this->_getSession()->setCurrentCountry($countryId);
        return $this;
    }
    /**
     * Unset current country
     * 
     * @return Innoexts_CurrencyGeoFilter_Model_Core_Store
     */
    public function unsetCurrentCountry()
    {
        $this->_getSession()->unsCurrentCountry();
        return $this;
    }
    /**
     * Retrieve current country
     * 
     * @return string
     */
    public function getCurrentCountry()
    {
        $this->hasDataChanges();
        return $this->_getSession()->getCurrentCountry();
    }
    /**
     * Set current store currency code
     *
     * @param   string $code
     * @return  Innoexts_CurrencyGeoFilter_Model_Core_Store
     */
    public function setCurrentCurrencyCode($code)
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        if ($helper->getConfig()->isEnabled($this) && !$helper->isAdmin()) {
            $code = strtoupper($code);
            $countryId = $this->getCurrentCountry();
            $codes = $this->getCountryAvailableCurrencyCodes($countryId);
            if (in_array($code, $codes)) {
                $this->_getSession()->setCurrencyCode($code);
                if ($code == $this->getDefaultCurrency()) {
                    Mage::app()->getCookie()->delete('currency', $code);
                } else {
                    Mage::app()->getCookie()->set('currency', $code);
                }
            }
            return $this;
        } else {
            return parent::setCurrentCurrencyCode($code);
        }
    }
    /**
     * Get current store currency code
     *
     * @param $countryId
     * @return string
     */
    public function getCurrentCurrencyCode()
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        if ($helper->getConfig()->isEnabled($this) && !$helper->isAdmin()) {
            $countryId = $this->getCurrentCountry();
            $codes = $this->getCountryAvailableCurrencyCodes($countryId);
            if (count($codes)) {
                $codes = array_values($codes);
            }
            $code = $this->_getSession()->getCurrencyCode();
            $defaultCode = $this->getDefaultCurrencyCode();
            if ($code && in_array($code, $codes)) {
                return $code;
            }
            if (!empty($codes)) {
                if (in_array($defaultCode, $codes)) {
                    return $defaultCode;
                }
            } else {
                return $defaultCode;
            }
            return array_shift($codes);
        } else {
            return parent::getCurrentCurrencyCode();
        }
    }
}