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
use \Magento\Framework\Validator\Exception;

class ProcessOrder implements ObserverInterface
{
    private static $_oldOrder = null;

    protected $logger;

    public function __construct(\Psr\Log\LoggerInterface $loggerInterface)
    {
        $this->logger = $loggerInterface;
    }

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
        $mode = '';
        $event = $observer->getEvent();

        //Event on shipment action
        if (!is_null($event->getShipment())) {
            $mode = 'shipment';
            $order = $event->getShipment()->getOrder();
            if (!is_null($order)) {
                $payment = $order->getPayment();
            }
        }

        if (!is_null($event->getOrder())) {
            $mode = 'save';
            $order = $event->getOrder();
        }

        if (empty($order)) {
            return $this;
        }

        // This order must be paid by Paybox
        $payment = $order->getPayment();
        if (empty($payment)) {
            return $this;
        }

        $method = $payment->getMethodInstance();
        if (!(get_class($method) == 'Paybox\Epayment\Model\Payment\Cb')) {
            return $this;
        }

        // Paybox Direct must be activated
        $config = $method->getPayboxConfig();
        if ($config->getSubscription() != \Paybox\Epayment\Model\Config::SUBSCRIPTION_OFFER2
            && $config->getSubscription() != \Paybox\Epayment\Model\Config::SUBSCRIPTION_OFFER3
        ) {
            return $this;
        }

        //         Action must be "Manual"
        if ($payment->getPbxepAction() != \Paybox\Epayment\Model\Payment\AbstractPayment::PBXACTION_MANUAL) {
            return $this;
        }

        if ($method->getConfigAutoCaptureMode() != \Paybox\Epayment\Model\Payment\AbstractPayment::PBXACTION_MODE_SHIPMENT) {
            //            var_dump($method->getConfigAutoCaptureMode());
            //            die();
            return $this;
        }

        // No capture must be prevously done
        $capture = $payment->getPbxepCapture();
        if (!empty($capture)) {
            return $this;
        }

        if (!$order->canInvoice()) {
            return $this;
        }

        $this->logger->debug(sprintf('Order %s: Automatic capture', $order->getIncrementId()));
        $result = false;
        $error = 'Unknown error';

        try {
            $result = $method->makeCapture($order);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }
    }
}
