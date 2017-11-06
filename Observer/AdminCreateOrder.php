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

namespace Paybox\Epayment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class AdminCreateOrder implements ObserverInterface
{
    private static $_oldOrder = null;

    public function onBeforeCreate($observer)
    {
        $event = $observer->getEvent();
        $session = $event->getSession();

        if ($session->getOrder()->getId()) {
            self::$_oldOrder = $session->getOrder();
        }
    }

    public function execute(EventObserver $observer)
    {
        $oldOrder = self::$_oldOrder;
        if (!is_null($oldOrder)) {
            $order = $observer->getEvent()->getOrder();
            if (!is_null($order)) {
                $payment = $order->getPayment();
                $oldPayment = $oldOrder->getPayment();

                // Payment information
                $payment->setPbxepAction($oldPayment->getPbxepAction());
                $payment->setPbxepAuthorization($oldPayment->getPbxepAuthorization());
                $payment->setPbxepCapture($oldPayment->getPbxepCapture());
                $payment->setPbxepFirstPayment($oldPayment->getPbxepFirstPayment());
                $payment->setPbxepSecondPayment($oldPayment->getPbxepSecondPayment());
                $payment->setPbxepSecondThird($oldPayment->getPbxepSecondPThird());
                $payment->setPbxepDelay($oldPayment->getPbxepDelay());
                $payment->setPbxepSecondPayment($oldPayment->getPbxepSecondPayment());

                // Transactions
                $oldTxns = $this->getObjectManager()->get('Magento\Framework\Model\ResourceModel\Db\TransactionManager')->getCollection();
                $oldTxns->addFilter('payment_id', $oldPayment->getId());
                foreach ($oldTxns as $oldTxn) {
                    $payment->setTransactionId($oldTxn->getTxnId());
                    $payment->setParentTransactionId($oldTxn->getParentTxnId());
                    $txn = $payment->addTransaction($oldTxn->getTxnType());
                    $txn->setParentTxnId($oldTxn->getParentTxnId());
                    $txn->setIsClosed($oldTxn->getIsClosed());
                    $infos = $oldTxn->getAdditionalInformation();
                    foreach ($infos as $key => $value) {
                        $txn->setAdditionalInformation($key, $value);
                    }

                    $txn->setOrderPaymentObject($payment);
                    $txn->setPaymentId($payment->getId());
                    $txn->setOrderId($order->getId());
                    $txn->save();
                }

                $payment->save();
            }
        }
    }
}
