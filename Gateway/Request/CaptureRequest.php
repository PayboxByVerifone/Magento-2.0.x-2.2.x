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
 * @version   1.0.8-meqp
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Verifone e-commerce
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */

namespace Paybox\Epayment\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class CaptureRequest implements BuilderInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Builds ENV request
     *
     * @param  array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /**
         * @var PaymentDataObjectInterface $paymentDO
         */
        $paymentDO = $buildSubject['payment'];

        $order = $paymentDO->getOrder();

        $payment = $paymentDO->getPayment();

        if (!$payment instanceof OrderPaymentInterface) {
            throw new \LogicException('Order payment should be provided.');
        }

        return [
            'TXN_TYPE' => 'S',
            'TXN_ID' => $payment->getLastTransId(),
            'MERCHANT_KEY' => $this->config->getValue(
                'merchant_gateway_key',
                $order->getStoreId()
            )
        ];


        $order = $payment->getOrder();
        $this->logDebug(sprintf('Order %s: Capture for %f', $order->getIncrementId(), $amount));

        // Currently processing a transaction ? Use it.
        if (null !== $this->_processingTransaction) {
            $txn = $this->_processingTransaction;

            switch ($txn->getTxnType()) {
                // Already captured
                case Transaction::TYPE_CAPTURE:
                    $trxData = $txn->getAdditionalInformation(Transaction::RAW_DETAILS);
                    if (!is_array($trxData)) {
                        throw new \LogicException('No transaction found.');
                    }

                    $payment->setTransactionId($txn->getTransactionId());
                    $payment->setIsTransactionClosed(0);
                    return $this;

                case Transaction::TYPE_AUTH:
                    // Nothing to do
                    break;

                default:
                    throw new \LogicException('Unsupported transaction type '.$txn->getTxnType());
            }
        } else {
            // Otherwise, find the good transaction
            // Find capture transaction
            $txn = $this->getPayboxTransaction($payment, Transaction::TYPE_CAPTURE);
            if (null !== $txn) {
                // Find Verifone e-commerce data
                $trxData = $txn->getAdditionalInformation(Transaction::RAW_DETAILS);
                if (!is_array($trxData)) {
                    throw new \LogicException('No transaction found.');
                }

                // Already captured
                $payment->setTransactionId($txn->getTransactionId());
                $payment->setIsTransactionClosed(0);
                return $this;
            }

            // Find authorization transaction
            $txn = $this->getPayboxTransaction($payment, Transaction::TYPE_AUTH, true);
            if (null === $txn) {
                throw new \LogicException('Payment never authorized.');
            }
        }

        $this->logDebug(sprintf('Order %s: Capture - transaction %d', $order->getIncrementId(), $txn->getTransactionId()));

        // Call Verifone e-commerce Direct
        $paybox = $this->getPaybox();
        $this->logDebug(sprintf('Order %s: Capture - calling directCapture with amount of %f', $order->getIncrementId(), $amount));
        $data = $paybox->directCapture($amount, $order, $txn);
        $this->logDebug(sprintf('Order %s: Capture - response code %s', $order->getIncrementId(), $data['CODEREPONSE']));

        // Message
        if ($data['CODEREPONSE'] == '00000') {
            $message = 'Payment was captured by Verifone e-commerce.';
            $close = true;
        } else {
            $message = 'Verifone e-commerce direct error ('.$data['CODEREPONSE'].': '.$data['COMMENTAIRE'].')';
            $close = false;
        }
        $data['status'] = $message;
        $this->logDebug(sprintf('Order %s: Capture - %s', $order->getIncrementId(), $message));

        // Transaction
        $type = Transaction::TYPE_CAPTURE;
        $captureTxn = $this->_addPayboxDirectTransaction(
            $order,
            $type,
            $data,
            $close,
            [
                self::CALL_NUMBER => $data['NUMTRANS'],
                self::TRANSACTION_NUMBER => $data['NUMAPPEL'],
            ],
            $txn
        );
        $captureTxn->save();
        if ($close) {
            $captureTxn->close();
            $payment->setPbxepCapture(serialize($data));
        }

        // Avoid automatic transaction creation
        $payment->setIsTransactionClosed(0);
        $payment->save();

        // If Verifone e-commerce returned an error, throw an exception
        if ($data['CODEREPONSE'] != '00000') {
            throw new \LogicException($message);
        }

        // Change order state and create history entry
        $status = $this->getConfigPaidStatus();
        $state = Order::STATE_PROCESSING;
        $order->setState($state, $status, __($message));
        $order->setIsInProgress(true);
        $order->save();

        return $this;
    }
}
