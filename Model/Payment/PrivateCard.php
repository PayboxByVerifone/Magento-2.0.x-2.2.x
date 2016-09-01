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

namespace Paybox\Epayment\Model\Payment;

class PrivateCard extends AbstractPayment {

    const CODE = 'pbxep_private';
    const XML_PATH = 'payment/pbxep_private/cctypes';

    protected $_code = self::CODE;
    protected $_3dsAllowed = true;
    protected $_hasCctypes = true;
    protected $_allowManualDebit = true;
    protected $_allowDeferredDebit = true;
    protected $_allowRefund = true;

    public function getReceipentEmail() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue(self::XML_PATH, $storeScope);
    }

    public function toOptionArray() {
        $result = array();
        $configPath = $this->getConfigPath();
        // $cards = Mage::getConfig()->getNode($configPath)->asArray();
        $cards = $this->_getConfigValue($configPath);
        if (!empty($cards)) {
            foreach ($cards as $code => $card) {
                $result[] = array(
                    'label' => __($card['label']),
                    'value' => $code,
                );
            }
        } else {
//            $result[] = array(
//                'label' => __('CB'),
//                'value' => 'CB',
//            );
//            $result[] = array(
//                'label' => __('Visa'),
//                'value' => 'VISA',
//            );
//            $result[] = array(
//                'label' => __('Mastercard'),
//                'value' => 'EUROCARD_MASTERCARD',
//            );
//            $result[] = array(
//                'label' => __('E-Carte Bleue'),
//                'value' => 'E_CARD',
//            );
        }
        return $result;
    }

}
