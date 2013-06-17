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
 * Currency block
 *
 * @category   Innoexts
 * @package    Innoexts_CurrencyGeoFilter
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CurrencyGeoFilter_Block_Directory_Currency extends Mage_Directory_Block_Currency
{
    /**
     * Get helper
     *
     * @return Innoexts_CurrencyGeoFilter_Helper_Data
     */
    protected function getCurrencyGeoFilterHelper()
    {
        return Mage::helper('currencygeofilter');
    }
    /**
     * Get currencies array
     * Return array: code => currency name
     * Return empty array if only one currency
     * 
     * @return array
     */
    public function getCurrencies()
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        $store = Mage::app()->getStore();
        if ($helper->getConfig()->isEnabled($store) && !$store->isAdmin()) {
            $currencies = $this->getData('currencies');
            if (is_null($currencies)) {
                $currencies = array();
                $currency = Mage::getModel('directory/currency');
                $codes = $store->getCountryAvailableCurrencyCodes($store->getCurrentCountry());
                if (is_array($codes) && count($codes) > 1) {
                    $rates = $currency->getCurrencyRates($store->getBaseCurrency(), $codes);
                    foreach ($codes as $code) {
                        if (isset($rates[$code])) {
                            $currencies[$code] = Mage::app()->getLocale()->getTranslation($code, 'nametocurrency');
                        }
                    }
                }
                $this->setData('currencies', $currencies);
            }
            return $currencies;
        } else {
            return parent::getCurrencies();
        }
    }
}