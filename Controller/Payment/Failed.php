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

class Failed extends \Paybox\Epayment\Controller\Payment
{
	public function execute() {
		try {
            $session = $this->getSession();
            $paybox = $this->getPaybox();

            // Retrieves params
            $params = $paybox->getParams(false, false);
            if ($params === false) {
                return $this->_404();
            }

            // Load order
            $order = $this->_getOrderFromParams($params);
            if (is_null($order) || is_null($order->getId())) {
                return $this->_404();
            }

            // Payment method
            $order->getPayment()->getMethodInstance()->onPaymentFailed($order);

            // Set quote to active
            $this->_loadQuoteFromOrder($order)->setIsActive(true)->save();

            // Cleanup
            $session->unsCurrentPbxepOrderId();

            $message = sprintf('Order %d: Customer is back from Paybox payment page. Payment refused by Paybox (%d).', $order->getIncrementId(), $params['error']);
            $this->logDebug($message);

            $message = __('Payment refused by Paybox.');
            $this->_messageManager->addError($message);
        }
        catch (Exception $e) {
            $this->logDebug(sprintf('failureAction: %s', $e->getMessage()));
        }

        // Redirect to cart
        $this->_redirect('checkout/cart');
	}
}
