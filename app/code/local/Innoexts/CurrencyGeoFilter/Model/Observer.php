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
 * Currency Geo Filter Data Helper
 *
 * @category   Innoexts
 * @package    Innoexts_CurrencyGeoFilter
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CurrencyGeoFilter_Model_Observer {
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
     * Reset quote currency
     * 
     * @param Mage_Sales_Model_Quote $quote
     * @param string $countryId
     * @return Innoexts_CurrencyGeoFilter_Model_Observer
     */
    protected function resetQuoteCurrency(Mage_Sales_Model_Quote $quote, $countryId)
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        $store = $quote->getStore();
        if ($store && !$helper->isAdmin()) {
            $currentCountryId = $store->getCurrentCountry();
            if (!$currentCountryId) {
                $address = $helper->getCustomerLocatorHelper()->getCustomerAddress();
                if ($address) {
                    $currentCountryId = $address->getCountryId();
                }
            }
            $oldQuoteCurrencyCode = $quote->getQuoteCurrencyCode();
            $store->setCurrentCountry($countryId);
            $store->unsCurrentCurrency();
            $baseCurrency = $store->getBaseCurrency();
            $quoteCurrency = $store->getCurrentCurrency();
            if ($oldQuoteCurrencyCode != $quoteCurrency->getCode()) {
                $quote->setQuoteCurrencyCode($quoteCurrency->getCode());
                $quote->setStoreToQuoteRate($baseCurrency->getRate($quoteCurrency));
                $quote->setBaseToQuoteRate($baseCurrency->getRate($quoteCurrency));
                $quote->collectTotals();
            }
        }
        return $this;
    }
    /**
     * Sales quote address before save
     * 
     * @param Varien_Event_Observer $observer
     * @return Innoexts_CurrencyGeoFilter_Model_Observer
     */
    public function quoteAddressBeforeSave(Varien_Event_Observer $observer)
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        if (!$helper->isAdmin()) {
            $quoteAddress = $observer->getEvent()->getQuoteAddress();
            if ($quoteAddress) {
                $quote = $quoteAddress->getQuote();
                if ($quote) {
                    $store = $quote->getStore();
                    if ($store && $helper->getConfig()->isOrderReadjust($store)) {
                        if ($helper->getConfig()->isShippingOrderReadjustMethod($store)) {
                            if (
                                ($quoteAddress->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_SHIPPING) && 
                                $quoteAddress->dataHasChangedFor('country_id')
                            ) {
                                $countryId = $quoteAddress->getCountryId();
                                $this->resetQuoteCurrency($quote, $countryId);
                            }
                        } else if ($helper->getConfig()->isBillingOrderReadjustMethod($store)) {
                            if (
                                ($quoteAddress->getAddressType() == Mage_Sales_Model_Quote_Address::TYPE_BILLING) && 
                                $quoteAddress->dataHasChangedFor('country_id')
                            ) {
                                $countryId = $quoteAddress->getCountryId();
                                $this->resetQuoteCurrency($quote, $countryId);
                            }
                        }
                    }
                }
            }
        }
        return $this;
    }
    /**
     * Sales order after save
     * 
     * @param Varien_Event_Observer $observer
     * @return Innoexts_CurrencyGeoFilter_Model_Observer
     */
    public function orderAfterSave(Varien_Event_Observer $observer)
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        if (!$helper->isAdmin()) {
            $order = $observer->getEvent()->getOrder();
            if ($order) {
                $store = $order->getStore();
                if ($store && $helper->getConfig()->isOrderReadjust($store)) {
                    $store->unsetCurrentCountry();
                }
            }
        }
        return $this;
    }
    /**
     * Send response before
     * 
     * @param Varien_Event_Observer $observer
     * @return Innoexts_CurrencyGeoFilter_Model_Observer
     */
    public function sendResponseBefore(Varien_Event_Observer $observer)
    {
        $helper = $this->getCurrencyGeoFilterHelper();
        if (!$helper->isAdmin() && $helper->getConfig()->isRedirectByCurrency()) {
            $front = $observer->getEvent()->getFront();
            if ($front) {
                if (in_array($front->getRequest()->getRequestedRouteName(), $helper->getWebsiteRedirectRouteNames())) {
                    $helper->redirectWebsiteByBaseCurrencyCode($front->getResponse());
                }
            }
        }
        return $this;
    }
}