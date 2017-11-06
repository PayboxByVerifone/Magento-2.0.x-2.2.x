<?php
/**
 * Verifone e-commerce Epayment module for Magento
 *
 * Feel free to contact Verifone e-commerce at support@paybox.com for any
 * question.
 *
 * LICENSE: This source file is subject to the version 3.0 of the Open
 * Software License (OSL-3.0) that is available through the world-wide-web
 * at the following URI: http://opensource.org/licenses/OSL-3.0. If
 * you did not receive a copy of the OSL-3.0 license and are unable
 * to obtain it through the web, please send a note to
 * support@paybox.com so we can mail you a copy immediately.
 *
 * @version   1.0.7-psr
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Verifone e-commerce
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */

namespace Paybox\Epayment\Gateway\Response;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;
use Magento\Sales\Model\Order\Payment;

class FraudHandler implements HandlerInterface
{
    const FRAUD_MSG_LIST = 'FRAUD_MSG_LIST';

    /**
     * Handles fraud messages
     *
     * @param  array $handlingSubject
     * @param  array $response
     * @return void
     */
    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($response[self::FRAUD_MSG_LIST]) || !is_array($response[self::FRAUD_MSG_LIST])) {
            return;
        }

        if (!isset($handlingSubject['payment'])
            || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /**
         * @var PaymentDataObjectInterface $paymentDO
         */
        $paymentDO = $handlingSubject['payment'];
        $payment = $paymentDO->getPayment();

        $payment->setAdditionalInformation(
            self::FRAUD_MSG_LIST,
            (array)$response[self::FRAUD_MSG_LIST]
        );

        /**
         * @var $payment Payment
         */
        $payment->setIsTransactionPending(true);
        $payment->setIsFraudDetected(true);
    }
}
