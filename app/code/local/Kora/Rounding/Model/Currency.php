<?php

/*
http://www.tall-paul.co.uk/2012/03/28/magento-round-up-currency-conversions/
*/

class Kora_Rounding_Model_Currency extends Mage_Directory_Model_Currency {
 /**
 * Convert price to currency format
 *
 * @param   double $price
 * @param   string $toCurrency
 * @return  double
 */

 public function convert($price, $toCurrency=null)
 {
    if (is_null($toCurrency)) {
      return $price;
    }
    elseif ($rate = $this->getRate($toCurrency)) {
       return ceil($price*$rate);
    }
    throw new Exception(Mage::helper('directory')->__('Undefined rate from "%s-%s".', $this->getCode(), $toCurrency->getCode()));
 }
}
