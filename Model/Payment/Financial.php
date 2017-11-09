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

namespace Paybox\Epayment\Model\Payment;

class Financial extends AbstractPayment
{
    const CODE = 'pbxep_financial';
    const XML_PATH = 'payment/pbxep_financial/cctypes';

    protected $_code = self::CODE;
    protected $_3dsAllowed = true;
    protected $_hasCctypes = true;
    protected $_allowManualDebit = true;
    protected $_allowDeferredDebit = true;
    protected $_allowRefund = true;

    public function getReceipentEmail()
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue(self::XML_PATH, $storeScope);
    }

    public function toOptionArray()
    {
        $result = [];
        $configPath = $this->getConfigPath();
        $cards = $this->_getConfigValue($configPath);
        if (!empty($cards)) {
            foreach ($cards as $code => $card) {
                $result[] = [
                    'label' => __($card['label']),
                    'value' => $code,
                ];
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
