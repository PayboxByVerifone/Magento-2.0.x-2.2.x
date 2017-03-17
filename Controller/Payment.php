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

namespace Paybox\Epayment\Controller;

class Payment extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;
    protected $_quoteRepository;
    protected $_logger;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);

        $this->_logger = $this->_objectManager->get('Psr\Log\LoggerInterface');
        $this->_messageManager = $this->_objectManager->get('Magento\Framework\Message\ManagerInterface');
        $this->_quoteRepository = $this->_objectManager->create('Magento\Quote\Api\CartRepositoryInterface');
    }

    public function execute()
    {
    }

    protected function _redirectResponse($order, $success, $checkUrlWarn = false)
    {
        // clear all messages in session
        $this->messageManager->getMessages(true);

        $storeId = $order->getStore()->getId();
        
        if ($success) {
            $this->_getCheckout()->setLastQuoteId($order->getQuoteId())
                                ->setLastSuccessQuoteId($order->getQuoteId())
                                ->setLastOrderId($order->getId())
                                ->setLastRealOrderId($order->getIncrementId())
                                ->setLastOrderStatus($order->getStatus());

            $this->logDebug('Redirecting to success page.');
            $this->_redirect('checkout/onepage/success');
        } else {
            $this->logDebug('Unsetting order data in session.');
            $this->messageManager->addWarning(__('Checkout and order have been canceled.'));

            $this->logDebug("Restore cart for order #{$order->getId()} to allow re-order quicker.");
            $quote = $this->_quoteRepository->get($order->getQuoteId());
            if ($quote->getId()) {
                $quote->setIsActive(true)->setReservedOrderId(null);
                $this->_quoteRepository->save($quote);

                $this->_getCheckout()->replaceQuote($quote);
            }

            $this->logDebug('Redirecting to cart page.');
            $this->_redirect('checkout/cart', array('_store' => $storeId));
        }
    }

    protected function _404()
    {
        $this->_forward('defaultNoRoute');
    }

    protected function _loadQuoteFromOrder(\Magento\Sales\Model\Order $order)
    {
        $quoteId = $order->getQuoteId();

        // Retrieves quote
        $quote = $this->_objectManager->get('Magento\Quote\Model\Quote')->load($quoteId);
        if (empty($quote) || is_null($quote->getId())) {
            $message = 'Not existing quote id associated with the order %d';
            throw new \LogicException(__($message, $order->getId()));
        }

        return $quote;
    }

    /**
     * Get checkout session namespace.
     *
     * @return \Magento\Checkout\Model\Session
     */
    protected function _getCheckout()
    {
        return $this->_objectManager->get('Magento\Checkout\Model\Session');
    }

    protected function _getOrderFromParams(array $params)
    {
        // Retrieves order
        $paybox = $this->getPaybox();
        $order = $paybox->untokenizeOrder($params['reference']);
        if (is_null($order) || is_null($order->getId())) {
            return null;
        }
        return $order;
    }

    public function getConfig()
    {
        return $this->_objectManager->get('Paybox\Epayment\Model\Config');
    }

    public function getPaybox()
    {
        return $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
    }

    public function getSession()
    {
        return $this->_objectManager->get('Magento\Checkout\Model\Session');
    }

    public function logDebug($message)
    {
        $this->_logger->debug($message);
    }

    public function logWarning($message)
    {
        $this->_logger->warning($message);
    }

    public function logError($message)
    {
        $this->_logger->error($message);
    }

    public function logFatal($message)
    {
        $this->_logger->critical($message);
    }
}
