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

class ProcessOrder implements ObserverInterface {

    private static $_oldOrder = null;
    
    protected $logger;
    
    public function __construct(\Psr\Log\LoggerInterface $loggerInterface) {
		$this->logger = $loggerInterface;
	}

    public function onBeforeCreate($observer) {
        $event = $observer->getEvent();
        $session = $event->getSession();

        if ($session->getOrder()->getId()) {
            self::$_oldOrder = $session->getOrder();
        }
    }

    public function execute(EventObserver $observer) {
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
                && $config->getSubscription() != \Paybox\Epayment\Model\Config::SUBSCRIPTION_OFFER3) {
        	return $this;
        }

//         Action must be "Manual"
        if ($payment->getPbxepAction() != \Paybox\Epayment\Model\Payment\AbstractPayment::PBXACTION_MANUAL) {
        	return $this;
        }
        
        if($method->getConfigAutoCaptureMode() != \Paybox\Epayment\Model\Payment\AbstractPayment::PBXACTION_MODE_SHIPMENT){
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
            }
            catch (Exception $e) {
                $error = $e->getMessage();
            }
                
        var_dump($result, $error);


        die();
    }

}
