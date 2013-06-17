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
 * Currency resource model
 *
 * @category   Innoexts
 * @package    Innoexts_CurrencyGeoFilter
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CurrencyGeoFilter_Model_Mysql4_Currency extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Currency country table
     *
     * @var string
     */
    protected $_currencyCountryTable;
    /**
     * Countries
     *
     * @var array
     */
    protected $_countries;
    /**
     * Currency country cache array
     *
     * @var array
     */
    protected static $_countryCache;
    /**
     * Constructor
     */
    protected function _construct()
    {
        $this->_init('currencygeofilter/currency', 'currency_code');
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $resource = Mage::getSingleton('core/resource');
        $this->_currencyCountryTable = $resource->getTableName('currencygeofilter/currency_country');
        parent::__construct();
    }
    /**
     * Saving countries
     *
     * @param   array $countries
     * @return Innoexts_CurrencyGeoFilter_Model_Mysql4_Currency
     */
    public function saveCountries($countries)
    {
        if (is_array($countries) && sizeof($countries) > 0) {
            $write = $this->_getWriteAdapter();
            $table  = $write->quoteIdentifier($this->_currencyCountryTable);
            $colCurrency= $write->quoteIdentifier('currency');
            $colCountry  = $write->quoteIdentifier('country_id');
            $sql = 'REPLACE INTO '.$table.' ('.$colCurrency.', '.$colCountry.') VALUES ';
            $values = array();
            $deleteCondition = '';
            $deleteConditionPieces = array();
            foreach ($countries as $currencyCode => $_countries) {
                foreach ($_countries as $countryCode) {
                    array_push($deleteConditionPieces, '(
                        ('.$colCurrency.' <> '.$write->quoteInto('?', $currencyCode).') AND 
                        ('.$colCountry.' <> '.$write->quoteInto('?', $countryCode).')
                    )');
                    $values[] = $write->quoteInto('(?)', array($currencyCode, $countryCode));
                }
            }
            $deleteCondition = implode(' OR ', $deleteConditionPieces);
            $sql.= implode(',', $values);
            $write->delete($this->_currencyCountryTable, $deleteCondition);
            $write->query($sql);
        } else {
            Mage::throwException(Mage::helper('currencygeofilter')->__('Invalid countries received.'));
        }
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
        $countries= array();
        if (is_array($currency)) {
            foreach($currency as $code) {
                $countries[$code] = $this->_getCountriesByCode($code);
            }
        } else {
            $countries = $this->_getCountriesByCode($currency);
        }
        return $countries;
    }
    /**
     * Retrieve country currencies
     *
     * @param string $countryId
     * @return array
     */
    public function getCountryCurrencies($countryId)
    {
        $currencies = array();
        $read = $this->_getReadAdapter();
        $select = $read->select()
            ->from($this->getTable('currencygeofilter/currency_country'), array('currency'))
            ->where($read->quoteInto('country_id = ?', $countryId));
        $data = $read->fetchAll($select);
        foreach($data as $currencyCountry) {
            $currencies[] = $currencyCountry['currency'];
        }
        unset($data);
        return $currencies;
    }
    /**
     * Retrieve countries by currency code
     *
     * @param string $code
     * @return array
     */
    protected function _getCountriesByCode($code)
    {
        $read = $this->_getReadAdapter();
        $select = $read->select()
            ->from($this->getTable('currencygeofilter/currency_country'), array('country_id'))
            ->where($read->quoteInto('currency = ?', $code));
        $data = $read->fetchAll($select);
        $result = array();
        foreach($data as $currencyCountry) {
            $result[] = $currencyCountry['country_id'];
        }
        unset($data);
        return $result;
    }
}