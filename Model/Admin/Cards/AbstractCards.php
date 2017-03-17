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
 * @version   1.0.0
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Paybox
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */

namespace Paybox\Epayment\Model\Admin\Cards;

use \Magento\Payment\Model\Method\AbstractMethod;
use Magento\Payment\Model\Config as PaymentConfig;

abstract class AbstractCards extends AbstractMethod
{
    protected $_scopeConfig;
    protected $_store;

    abstract public function getConfigNodeName();

    public function __construct(
    \Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Payment\Model\Method\Logger $logger, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = []
    ) {
        parent::__construct(
                $context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $resource, $resourceCollection, $data
        );
        $this->_scopeConfig = $scopeConfig;
        $this->_store = $this->getStore();
    }

    public function getConfigPath()
    {
        return 'default/payment/pbxep_' . $this->getConfigNodeName() . '/cards';
    }

    public function getStore()
    {
        if (is_null($this->_store)) {
            $ObjectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $manager = $ObjectManager->get('Magento\Store\Model\StoreManagerInterface');
            $this->_store = $manager->getStore();
        }
        return $this->_store;
    }

    protected function _getConfigValue($name)
    {
        return $this->_scopeConfig->getValue($name, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getCards()
    {
        return $this->_scopeConfig->getValue('payment/' . $this->getConfigNodeName() . '/cctypes');
    }
}
