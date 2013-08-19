<?php

class PPF_OPIM_Model_Orders_Api extends PPF_OPIM_Model_Api
{
	public function items($already_exported = false, $minId = null, $minDate = null, $status = null, $stores = null)
	{
		$helper = Mage::helper('ppfopim');
		if ($helper->checkStatus($this) !== true) return false;
		$already_exported = ((intval($already_exported) == '1' || (string) $already_exported == 'yes') ? true : false);
		$minDate = (((int) $minDate == 0 || (string) $minDate == 'no') ? null : $minDate);
		$status = (((string) $status == 'no') ? null : $status);
		$stores = ((!empty($stores)) ? explode(',', $stores) : array());
		if (count($stores) <= 0) $this->_fault('filters_invalid', 'Specify at least a store id.');

		$list = Mage::getModel('sales/order');
		$oids = $list->getExportIds($already_exported, $minId, $minDate, $status, $stores);
		if (!is_array($oids) || count($oids) <= 0) $this->_fault('filters_invalid', 'No orders to export found.');

		$orders = array();
		$order = Mage::getModel('sales/order');
		foreach ($oids as $oID)
		{
			$order = $order->load($oID);
			$orders[] = array('MgId' => $oID, 'OrderId' => $order->getIncrementId(), 'Status' => $order->getStatus(), 'StoreId' => $helper->getOrderStoreId($order->getStoreId()));
		}
		return $orders;
	}

	protected function getOrderOptions($item)
	{
		if (!is_object($item)) return false;
		$result = $attrs = array();
		if ($options = $item->getProductOptions())
		{
			if (isset($options['options']))
				$attrs = array_merge($attrs, $options['options']);
			if (isset($options['additional_options']))
				$attrs = array_merge($attrs, $options['additional_options']);
			if (!empty($options['attributes_info']))
				$attrs = array_merge($options['attributes_info'], $attrs);
			$result = array('attrs' => $attrs, 'simple_name' => (array_key_exists('simple_name', $options) ? $options['simple_name'] : null),
			                'simple_sku' => (array_key_exists('simple_sku', $options) ? $options['simple_sku'] : null));
		}
		return ((count($result) > 0) ? $result : false);
	}

	public function export($id = null)
	{
		$helper = Mage::helper('ppfopim');
		if ($helper->checkStatus($this) !== true) return false;

		$order_info = array('Order' => null, 'Details' => array(), 'Billing' => null, 'Shipping' => null, 'Customer' => null, 'Addresses' => array());
		$order = Mage::getModel('sales/order');
		if (!$order->eIdExists($id)) $this->_fault('filters_invalid', 'Order not found.');
		$order = $order->load($id);

		$baseBug = $helper->compMagentoVer('1.4.1.1');
		$use_base = (($baseBug && $order->getBaseGrandTotal() != $order->getGrandTotal() && $order->getBaseCurrencyCode() == $order->getOrderCurrencyCode()) ? false : true);

		$shippingAddress = (($order->getIsVirtual()) ? new Varien_Object() : $order->getShippingAddress());
		$data = array('OrderID' => $order->getEntityId(),
		              'InvoiceID' => $order->getIncrementId(),
		              'CustomerID' => $order->getCustomerId(),
		              'OrderDate' => $order->getCreatedAt(),
		              'Subtotal' => ($use_base ? $order->getBaseSubtotal() : $order->getSubtotal()),
		              'ShippingCost' => ($use_base ? $order->getBaseShippingAmount() : $order->getShippingAmount()),
		              'ShippingVAT' => ($use_base ? $order->getBaseShippingTaxAmount() : $order->getShippingTaxAmount()),
		              'Discount' => ($use_base ? $order->getBaseDiscountAmount() : $order->getDiscountAmount()),
		              'VAT' => ($use_base ? $order->getBaseTaxAmount() : $order->getTaxAmount()),
		              'TotalCost' => ($use_base ? $order->getBaseGrandTotal() : $order->getGrandTotal()),
		              'Paid' => ($use_base ? $order->getBaseTotalPaid() : $order->getTotalPaid()),
		              'Status' => $order->getStatus(),
		              'Currency' => $order->getBaseCurrencyCode(),
		              'OrderCurrency' => $order->getOrderCurrencyCode(),
		              'OrderCurrencyRate' => $order->getBaseToOrderRate(),
		              'OrderSubtotal' => $order->getSubtotal(),
		              'OrderShippingCost' => $order->getShippingAmount(),
		              'OrderShippingVAT' => $order->getShippingTaxAmount(),
		              'OrderDiscount' => $order->getDiscountAmount(),
		              'OrderVAT' => $order->getTaxAmount(),
		              'OrderTotalCost' => $order->getGrandTotal(),
		              'OrderPaid' => $order->getTotalPaid(),
		              'CustomerTaxvat' => $order->getCustomerTaxvat(),
		              'DiscountCode' => $order->getCouponCode(),
		              'Weight' => $order->getWeight(),
		              'Email' => $order->getCustomerEmail(),
		              'ShippingMethod' => $order->getShippingMethod(),
		              'ShippingDescription' => $order->getShippingDescription());
		if ($payment = $order->getPayment())
		{
			$data['PaymentMethod'] = $payment->getMethod();
			$data['IsPaid'] = (($payment->getAmountPaid() == $order->getGrandTotal()) ? true : false);
			$paydata = $payment->getAdditionalData();
			if (!empty($paydata) && ($paydata = @unserialize($paydata)) && is_array($paydata))
			{
				if (array_key_exists('VendorTxCode', $paydata))
					$data['TransactionReference'] = $paydata['VendorTxCode'];
				if (array_key_exists('Last4Digits', $paydata))
					$data['CreditCardID'] = $paydata['Last4Digits'];
			}
		}
		$notes = null;
		foreach ($order->getAllStatusHistory() as $history)
		{
			$comment = trim($history->getComment());
			if (!empty($comment)) $notes .= $comment."\n--NOTE--\n";
		}
		$data['Message'] = substr($notes, 0, -10);
		if (($tax = $order->getFullTaxInfo()) && is_array($tax))
		{
			$taxrate = null;
			foreach ($tax as $t)
			{
				if (!empty($t['percent'])) $taxrate .= $t['percent'].';';
			}
			$data['VATrate'] = substr($taxrate, 0, -1);
		}

		if ($extra = $helper->getExtraFields($order->getStoreId()))
		{
			foreach ($extra as $field)
				$data[$field] = $order->getData($field);
		}

		$order_info['Order'] = $this->parseKeyValues($data);

		foreach ($order->getItemsCollection() as $item)
		{
			if (!is_object($item)) continue;
			if ($item->getParentItemId()) continue;
			$product = Mage::getModel('catalog/product')->load($item->getProductId());
			$p_options = $option_info = null;
			if (($p_options = $this->getOrderOptions($item)) && array_key_exists('attrs', $p_options) && is_array($p_options['attrs']))
			{
				foreach ($p_options['attrs'] as $option)
					$option_info .= $option['label'].': '.$option['value'].', ';
			}
			$product_name = ((is_array($p_options) && array_key_exists('simple_name', $p_options) && !empty($p_options['simple_name'])) ? $p_options['simple_name'] : $product->getName());
			$product_sku = ((is_array($p_options) && array_key_exists('simple_sku', $p_options) && !empty($p_options['simple_sku'])) ? $p_options['simple_sku'] : $product->getSku());
			$data = array('ProductID' => $product->getId(),
			              'SKU' => $product_sku,
			              'Name' => $product->getName(),
			              'ExtraName' => $product_name,
			              'Options' => substr($option_info, 0, -2),
			              'Type' => $product->getTypeId(),
			              'Cost' => $product->getCost(),
			              'Weight' => $item->getWeight(),
			              'Qty' => $item->getQtyOrdered(),
			              'Price' => ($use_base ? $item->getBasePrice() : $item->getPrice()),
			              'Discount' => ($use_base ? $item->getBaseDiscountAmount() : $item->getDiscountAmount()),
			              'VATpaid' => ($use_base ? $item->getBaseTaxAmount() : $item->getTaxAmount()),
			              'VATrate' => $item->getTaxPercent(),
			              'TotalPrice' => ($use_base ? $item->getBaseRowTotal() : $item->getRowTotal()),
			              'TotalPriceTax' => ($use_base ? $item->getBaseRowTotalInclTax() : $item->getRowTotalInclTax()),
			              'OrderPrice' => $item->getPrice(),
			              'OrderDiscount' => $item->getDiscountAmount(),
			              'OrderVATpaid' => $item->getTaxAmount(),
			              'OrderTotalPrice' => $item->getRowTotal(),
			              'OrderTotalPriceTax' => $item->getRowTotalInclTax(),
			              'OriginalPrice' => $product->getPrice());
			$order_info['Details'][]['Product'] = $this->parseKeyValues($data);
		}

		$address = $order->getBillingAddress()->getStreet();
		if (is_array($address)) $address = trim(implode("\n", $address));
		$data = array('CustomerID' => $order->getBillingAddress()->getCustomerId(),
		              'Title' => $order->getBillingAddress()->getPrefix(),
		              'Firstname' => $order->getBillingAddress()->getFirstname(),
		              'Lastname' => $order->getBillingAddress()->getLastname(),
		              'Gender' => $order->getBillingAddress()->getGender(),
		              'Company' => $order->getBillingAddress()->getCompany(),
		              'Address' => $address,
		              'City' => $order->getBillingAddress()->getCity(),
		              'State' => $order->getBillingAddress()->getRegion(),
		              'Postcode' => $order->getBillingAddress()->getPostcode(),
		              'Country' => $order->getBillingAddress()->getCountryId(),
		              'Phone' => $order->getBillingAddress()->getTelephone(),
		              'Fax' => $order->getBillingAddress()->getFax(),
		              'Email' => $order->getBillingAddress()->getEmail());
		$order_info['Billing'] = $this->parseKeyValues($data);

		$address = $shippingAddress->getStreet();
		if (is_array($address)) $address = trim(implode("\n", $address));
		$data = array('CustomerID' => $shippingAddress->getCustomerId(),
		              'Title' => $shippingAddress->getPrefix(),
		              'Firstname' => $shippingAddress->getFirstname(),
		              'Lastname' => $shippingAddress->getLastname(),
		              'Gender' => $shippingAddress->getGender(),
		              'Company' => $shippingAddress->getCompany(),
		              'Address' => $address,
		              'City' => $shippingAddress->getCity(),
		              'State' => $shippingAddress->getRegion(),
		              'Postcode' => $shippingAddress->getPostcode(),
		              'Country' => $shippingAddress->getCountryId(),
		              'Phone' => $shippingAddress->getTelephone(),
		              'Fax' => $shippingAddress->getFax(),
		              'Email' => $shippingAddress->getEmail());
		$order_info['Shipping'] = $this->parseKeyValues($data);

		if ($customer_id = $order->getCustomerId())
		{
			$customer = Mage::getModel('customer/customer')->load($customer_id);
			$group = Mage::getModel('customer/group')->load($customer->getGroupId());
			$jobtitles = $this->getAttributeOptions($customer, 'job_title');
			if (!($caddress = $customer->getPrimaryBillingAddress()))
				$caddress = new Varien_Object();
			$address = $caddress->getStreet();
			if (is_array($address)) $address = trim(implode("\n", $address));
			$data = array('CustomerID' => $customer->getId(),
			              'Title' => $customer->getPrefix(),
			              'Firstname' => $customer->getFirstname(),
			              'Lastname' => $customer->getLastname(),
			              'CustomerTypeID' => $customer->getGroupId(),
			              'CustomerType' => $group->getCode(),
			              'Company' => $caddress->getCompany(),
			              'Address' => $address,
			              'City' => $caddress->getCity(),
			              'State' => $caddress->getRegion(),
			              'Postcode' => $caddress->getPostcode(),
			              'Country' => $caddress->getCountryId(),
			              'Phone' => $customer->getTelephone(),
			              'Fax' => $customer->getFax(),
			              'Email' => $customer->getEmail(),
			              'TaxNo' => $customer->getTaxvat());
			$order_info['Customer'] = $this->parseKeyValues($data);

			foreach ($customer->getAddresses() as $item)
			{
				if (!is_object($item)) continue;
				if ($caddress->getId() == $item->getId()) continue;
				$address = $item->getStreet();
				if (is_array($address)) $address = trim(implode("\n", $address));
				$data = array('AddressID' => $item->getId(),
				              'Title' => $item->getPrefix(),
				              'Firstname' => $item->getFirstname(),
				              'Lastname' => $item->getLastname(),
				              'Gender' => $item->getGender(),
				              'Company' => $item->getCompany(),
				              'Address' => $address,
				              'City' => $item->getCity(),
				              'State' => $item->getRegion(),
				              'Postcode' => $item->getPostcode(),
				              'Country' => $item->getCountryId(),
				              'Phone' => $item->getTelephone(),
				              'Fax' => $item->getFax(),
				              'Email' => $item->getCustomerEmail());
				$order_info['Addresses'][]['Address'] = $this->parseKeyValues($data);
			}
		}

		$order->setPpfopimExportDate()->save();

		return $order_info;
	}

	public function reconcile($id, $notify = false, $data = null)
	{
		$helper = Mage::helper('ppfopim');
		if ($helper->checkStatus($this) !== true) return false;
		if (is_object($data)) $data = $helper->objToArray($data);
		if (!is_array($data)) $this->_fault('filters_invalid', 'Unable to parse array.');
		if (!is_numeric($id)) $this->_fault('filters_invalid', sprintf('%s => OrderID is not numeric.', $id));

		$order = Mage::getModel('sales/order');
		if (!$order->eIdExists($id)) $this->_fault('filters_invalid', 'Order not found.');
		$order = $order->load($id);

		try
		{
			if ($order->canShip())
			{
				$notify = (($notify == '1') ? true : false);

				$qtys = array();
				foreach ($order->getAllItems() as $orderItem)
					$qtys[$orderItem->getId()] = $orderItem->getQtyToShip();

				$shipment = Mage::getModel('sales/service_order', $order)->prepareShipment($qtys);

				$tracking_url_info = null;
				if (array_key_exists('TrackingNo', $data) && !empty($data['TrackingNo']))
				{
					$tracking_url_info = 'Tracking #: '.$data['TrackingNo'];
					$order->addStatusToHistory($order->getStatus(), $tracking_url_info, false)
					      ->save();

					$track = Mage::getModel('sales/order_shipment_track')->addData(array('number' => $data['TrackingNo']));
					$shipment->addTrack($track);
				}

				$shipment->register();
				$shipment->setEmailSent($notify);
				$shipment->getOrder()->setCustomerNoteNotify($notify);

				$shipment->getOrder()->setIsInProcess(true);
				$transactionSave = Mage::getModel('core/resource_transaction')->addObject($shipment)->addObject($shipment->getOrder())->save();

				if ($notify) $shipment->sendEmail(true, $tracking_url_info);
				if ($order->getState() != Mage_Sales_Model_Order::STATE_COMPLETE)
					$order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE)
					      ->setData('status', Mage_Sales_Model_Order::STATE_COMPLETE)
					      ->addStatusToHistory(Mage_Sales_Model_Order::STATE_COMPLETE, '', $notify)
					      ->save();
				return $shipment->getId();
			}
			else
			{
				switch ($order->getState())
				{
					case Mage_Sales_Model_Order::STATE_COMPLETE:
						Mage::throwException('Order #'.$order->getIncrementId().' already marked as completed');
						break;
					case Mage_Sales_Model_Order::STATE_PROCESSING:
						$order->setData('state', Mage_Sales_Model_Order::STATE_COMPLETE)
						      ->setData('status', Mage_Sales_Model_Order::STATE_COMPLETE)
						      ->addStatusToHistory(Mage_Sales_Model_Order::STATE_COMPLETE, '', $notify)
						      ->save();
						return true;
						break;
					default:
						Mage::throwException('Order #'.$order->getIncrementId().' can\'t be marked as shipped');
						break;
				}
			}
		}
		catch (Mage_Core_Exception $e) { $this->_fault('data_invalid', sprintf('%s: Error occured when trying to save the order: %s', $id, $e->getMessage())); }
		return false;
	}
}

?>
