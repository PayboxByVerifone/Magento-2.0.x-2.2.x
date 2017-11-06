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

namespace Paybox\Epayment\Block;

class Redirect extends \Magento\Framework\View\Element\Template
{
    protected $_objectManager;
    protected $_helper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = [],
        \Paybox\Epayment\Helper\Data $helper
    ) {
        parent::__construct($context, $data);

        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_helper = $helper;
    }

    public function getFormFields()
    {
        $registry = $this->_objectManager->get('Magento\Framework\Registry');
        $current_order_id = $this->_objectManager->get('Magento\Checkout\Model\Session')->getCurrentPbxepOrderId();
        $order = $registry->registry('pbxep/order_'.$current_order_id);
        $payment = $order->getPayment()->getMethodInstance();
        $cntr = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        return $cntr->buildSystemParams($order, $payment);
    }

    public function getInputType()
    {
        $config = $this->_objectManager->get('Paybox\Epayment\Model\Config');
        if ($config->isDebug()) {
            return 'text';
        }
        return 'hidden';
    }

    public function getKwixoUrl()
    {
        $paybox = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        $urls = $paybox->getConfig()->getKwixoUrls();
        return $paybox->checkUrls($urls);
    }

    public function getMobileUrl()
    {
        $paybox = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        $urls = $paybox->getConfig()->getMobileUrls();
        return $paybox->checkUrls($urls);
    }

    public function getSystemUrl()
    {
        $paybox = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        $urls = $paybox->getConfig()->getSystemUrls();
        return $paybox->checkUrls($urls);
    }

    public function getResponsiveUrl()
    {
        $paybox = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        $urls = $paybox->getConfig()->getResponsiveUrls();
        return $paybox->checkUrls($urls);
    }
}
