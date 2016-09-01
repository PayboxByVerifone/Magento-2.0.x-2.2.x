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

namespace Paybox\Epayment\Controller;

class Payment extends \Magento\Framework\App\Action\Action {

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $_logger;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);

        $this->_logger = $this->_objectManager->get('Psr\Log\LoggerInterface');
        $this->_messageManager = $this->_objectManager->get('Magento\Framework\Message\ManagerInterface');
    }

    public function execute() {
        
    }

    protected function _404() {
        $this->_forward('defaultNoRoute');
    }

    protected function _loadQuoteFromOrder(\Magento\Sales\Model\Order $order) {
        $quoteId = $order->getQuoteId();

        // Retrieves quote
        $quote = $this->_objectManager->get('Magento\Quote\Model\Quote')->load($quoteId);
        if (empty($quote) || is_null($quote->getId())) {
            $message = 'Not existing quote id associated with the order %d';
            throw new \LogicException(__($message, $order->getId()));
        }

        return $quote;
    }

    protected function _getOrderFromParams(array $params) {
        // Retrieves order
        $paybox = $this->getPaybox();
        $order = $paybox->untokenizeOrder($params['reference']);
        if (is_null($order) || is_null($order->getId())) {
            return null;
        }
        return $order;
    }

    public function getConfig() {
        return $this->_objectManager->get('Paybox\Epayment\Model\Config');
    }

    public function getPaybox() {
        return $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
    }

    public function getSession() {
        return $this->_objectManager->get('Magento\Checkout\Model\Session');
    }

    public function logDebug($message) {
        $this->_logger->debug($message);
    }

    public function logWarning($message) {
        $this->_logger->warning($message);
    }

    public function logError($message) {
        $this->_logger->error($message);
    }

    public function logFatal($message) {
        $this->_logger->critical($message);
    }

}
