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

namespace Paybox\Epayment\Block;

class Redirect extends \Magento\Framework\View\Element\Template {

    protected $_objectManager;
    protected $_helper;

    public function __construct(
    \Magento\Framework\View\Element\Template\Context $context, array $data = [], \Paybox\Epayment\Helper\Data $helper
    ) {
        parent::__construct($context, $data);

        $this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $this->_helper = $helper;
    }

    public function getFormFields() {
        $registry = $this->_objectManager->get('Magento\Framework\Registry');
        $order = $registry->registry('pbxep/order_'.$_SESSION['checkout']['current_pbxep_order_id']);
        $payment = $order->getPayment()->getMethodInstance();
        $cntr = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        return $cntr->buildSystemParams($order, $payment);
    }

    public function getInputType() {
        $config = $this->_objectManager->get('Paybox\Epayment\Model\Config');
        if ($config->isDebug()) {
            return 'text';
        }
        return 'hidden';
    }

    public function getKwixoUrl() {
        $paybox = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        $urls = $paybox->getConfig()->getKwixoUrls();
        return $paybox->checkUrls($urls);
    }

    public function getMobileUrl() {
        $paybox = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        $urls = $paybox->getConfig()->getMobileUrls();
        return $paybox->checkUrls($urls);
    }

    public function getSystemUrl() {
        $paybox = $this->_objectManager->get('Paybox\Epayment\Model\Paybox');
        $urls = $paybox->getConfig()->getSystemUrls();
        return $paybox->checkUrls($urls);
    }

}
