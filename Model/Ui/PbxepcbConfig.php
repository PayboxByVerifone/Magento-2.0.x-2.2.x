<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Paybox\Epayment\Model\Ui;

/**
 * Class Pbxepcb
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 */
class PbxepcbConfig {

    const PAYMENT_METHOD_PBXEPCB_CODE = 'pbxep_cb';
    const PAYMENT_METHOD_PBXEPCB_XML_PATH = 'payment/pbxep_cb/cctypes';

    /**
     * Payment method code
     *
     * @var string
     */
    protected $CODE = self::PAYMENT_METHOD_PBXEPCB_CODE;
    protected $_code = self::PAYMENT_METHOD_PBXEPCB_CODE;

    /**
     * @var string
     */
    // protected $_formBlockType = 'Paybox\Epayment\Block\Form\Pbxepcb';

    /**
     * @var string
     */
    // protected $_infoBlockType = 'Paybox\Epayment\Block\Info\Pbxepcb';

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = false;
    protected $scopeConfig;
    protected $_3dsAllowed = true;
    protected $_hasCctypes = true;
    protected $_allowManualDebit = true;
    protected $_allowDeferredDebit = true;
    protected $_allowRefund = true;

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getReceipentEmail() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::PAYMENT_METHOD_PBXEPCB_XML_PATH, $storeScope);
    }

    /**
     * @return string
     */
    public function getCards() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::PAYMENT_METHOD_PBXEPCB_XML_PATH, $storeScope);
    }

}
