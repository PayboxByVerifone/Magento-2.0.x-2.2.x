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

namespace Paybox\Epayment\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;

class AdminCreateOrder implements ObserverInterface
{
	private static $_oldOrder = null;

	public function onBeforeCreate($observer) {
		$event = $observer->getEvent();
		$session = $event->getSession();

        if ($session->getOrder()->getId()) {
			self::$_oldOrder = $session->getOrder();
		}
	}

	public function execute(EventObserver $observer) {
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