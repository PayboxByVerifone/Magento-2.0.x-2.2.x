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

namespace Paybox\Epayment\Model\Ui;

/**
 * Class Pbxepcb
 *
 * @method \Magento\Quote\Api\Data\PaymentMethodExtensionInterface getExtensionAttributes()
 */
class PbxepcbConfig
{
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

    public function __construct(\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getReceipentEmail()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::PAYMENT_METHOD_PBXEPCB_XML_PATH, $storeScope);
    }

    /**
     * @return string
     */
    public function getCards()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->scopeConfig->getValue(self::PAYMENT_METHOD_PBXEPCB_XML_PATH, $storeScope);
    }
}
