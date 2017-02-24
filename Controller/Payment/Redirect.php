<?php
/**
 * Paybox Epayment module for Magento
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * available at : http://opensource.org/licenses/osl-3.0.php
 *
 * @package    Paybox_Epayment
 * @copyright  Copyright (c) 2013-2014 Paybox
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Paybox\Epayment\Controller\Payment;

class Redirect extends \Paybox\Epayment\Controller\Payment
{
	public function execute() {
		$cookieName = 'lastOrderId';
		$cookieManager = $this->_objectManager->get('Magento\Framework\Stdlib\CookieManagerInterface');
		$encryptor = $this->_objectManager->get('Magento\Framework\Encryption\Encryptor');
		$registry = $this->_objectManager->get('Magento\Framework\Registry');

		// Retrieves order id
		$session = $this->getSession();
		$orderId = $session->getLastRealOrderId();

		// If none, try previously saved
		$this->logDebug('Paybox - LastRealOrderId from $session: '.$orderId);
		if (is_null($orderId)) {
			$orderId = $session->getCurrentPbxepOrderId();
			$this->logDebug('Paybox - CurrentPbxepOrderId from $session: '.$orderId);
		}

		//Try with cookies
		$cookieOrderId = $cookieManager->getCookie($cookieName);

		// If none, 404
		if (is_null($orderId)) {
			$this->logDebug('Paybox - $orderId is null => 404');

			$this->logDebug('Paybox - Try to get id from cookies');
			if(!is_null($cookieOrderId)){
				$this->logDebug('Paybox - Retrieve id from cookies : ' . $cookieOrderId);
				$order = $this->_objectManager->get('Magento\Sales\Model\Order')->load($cookieOrderId);
				if(isset($_COOKIE[$cookieName])){
					unset($_COOKIE[$cookieName]);
				}
			}else{
				return $this->_404();
			}
		}else{
			$order = $this->_objectManager->get('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
		}

		// Load order
		$order = $this->_objectManager->get('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
		if (is_null($order) || is_null($order->getId())) {
			$session->unsCurrentPbxepOrderId();
			return $this->_404();
		}

		// Check order status
		$state = $order->getState();
		if ($state != \Magento\Sales\Model\Order::STATE_NEW) {
			$session->unsCurrentPbxepOrderId();
			return $this->_404();
		}

		// Save id
		$session->setCurrentPbxepOrderId($orderId);

		// Keep track of order for security check
		$orders = $session->getPbxepOrders();
		if (is_null($orders)) {
			$orders = array();
		}

		$orders[$encryptor->encrypt($orderId)] = true;
		$session->setPbxepOrders($orders);

		// Payment method
		$order->getPayment()->getMethodInstance()->onPaymentRedirect($order);

		// Render form
		$registry->register('pbxep/order_' . $orderId, $order);

		$page = $this->resultPageFactory->create();
		
		return $page;
	}
}
