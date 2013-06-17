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
class Innoexts_CurrencyGeoFilter_Model_Currency extends Mage_Core_Model_Abstract
{
    /**
     * Countries
     * 
     * @var array
     */
    protected $_countries;
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('currencygeofilter/currency');
    }
    /**
     * Countries getter
     *
     * @return array
     */
    public function getCountries()
    {
        return $this->_countries;
    }
    /**
     * Countries setter
     *
     * @param array Currency Countries
     * @return Innoexts_CurrencyGeoFilter_Model_Currency
     */
    public function setCountries(array $countries)
    {
        $this->_countries = $countries;
        return $this;
    }
    /**
     * Loading data
     *
     * @param   string $id
     * @param   string $field
     * @return  Innoexts_CurrencyGeoFilter_Model_Currency
     */
    public function load($id, $field = null)
    {
        $thius->setCountries(array());
        $this->setData('currency_code', $id);
        return $this;
    }
    /**
     * Retrieve currency countries
     *
     * @param string|array $currency
     * @return array
     */
    public function getCurrencyCountries($currency)
    {
        if ($currency && ($currency instanceof Innoexts_CurrencyGeoFilter_Model_Currency)) {
            $currency = $currency->getCurrencyCode();
        }
        return $this->_getResource()->getCurrencyCountries($currency);
    }
    /**
     * Retrieve country currencies
     *
     * @param string $countryId
     * @return array
     */
    public function getCountryCurrencies($countryId)
    {
        return $this->_getResource()->getCountryCurrencies($countryId);
    }
    /**
     * Save countries
     *
     * @param array $rates
     * @return object
     */
    public function saveCountries($countries)
    {
        $this->_getResource()->saveCountries($countries);
        return $this;
    }
}