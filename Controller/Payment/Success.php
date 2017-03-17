<?php
/**
 * Paybox Epayment module for Magento
 *
 * Feel free to contact Paybox by Verifone at support@paybox.com for any
 * question.
 *
 * LICENSE: This source file is subject to the version 3.0 of the Open
 * Software License (OSL-3.0) that is available through the world-wide-web
 * at the following URI: http://opensource.org/licenses/OSL-3.0. If
 * you did not receive a copy of the OSL-3.0 license and are unable
 * to obtain it through the web, please send a note to
 * support@paybox.com so we can mail you a copy immediately.
 *
 *
 * @version   1.0.6
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Paybox
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */

namespace Paybox\Epayment\Controller\Payment;

use \Magento\Framework\Validator\Exception;

class Success extends \Paybox\Epayment\Controller\Payment
{
    public function execute()
    {
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
            $order->getPayment()->getMethodInstance()->onPaymentSuccess($order, $params);

            // Cleanup
            $session->unsCurrentPbxepOrderId();

            $message = sprintf('Order %s: Customer is back from Paybox payment page. Payment success.', $order->getIncrementId());
            $this->logDebug($message);

            // Redirect to success page
            $this->_redirectResponse($order, true /* is success ? */, true /* notification url warn in TEST mode */);
            return;
        } catch (\Exception $e) {
            $this->logDebug(sprintf('successAction: %s', $e->getMessage()));
        }

        $this->_redirect('checkout/cart');
    }
}
