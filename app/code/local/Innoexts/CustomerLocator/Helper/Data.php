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
 * Customer locator helper
 * 
 * @category   Innoexts
 * @package    Innoexts_CustomerLocator
 * @author     Innoexts Team <developers@innoexts.com>
 */
class Innoexts_CustomerLocator_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Get customer locator session
     * 
     * @return Innoexts_CustomerLocator_Model_Session
     */
    protected function getCustomerLocatorSession()
    {
        return Mage::getSingleton('customerlocator/session');
    }
    /**
     * Get customer address
     * 
     * @return Varien_Object
     */
    public function getCustomerAddress()
    {
        return $this->getCustomerLocatorSession()->getAddress();
    }
    /**
     * Set customer address
     * 
     * @param Varien_Object $address
     * @return Innoexts_CustomerLocator_Helper_Data
     */
    public function setCustomerAddress($address)
    {
        $this->getCustomerLocatorSession()->setAddress($address);
        return $this;
    }
    /**
     * Unset customer address
     * 
     * @param Varien_Object $address
     * @return Innoexts_CustomerLocator_Helper_Data
     */
    public function unsetCustomerAddress()
    {
        $this->getCustomerLocatorSession()->unsetAddress();
        return $this;
    }
}