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
 * @version   1.0.7-psr
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Paybox
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */

namespace Paybox\Epayment\Model\Config\Source;

class CurrencyYesNo
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $om->get('Magento\Store\Model\StoreManagerInterface');
        $currencies = $storeManager->getStore()->getAvailableCurrencyCodes();
        if (count($currencies) > 1) {
            return [['value' => 1, 'label' => __('Default store currency')], ['value' => 0, 'label' => __('Order currency')]];
        } else {
            return [['value' => 1, 'label' => __('Default store currency')]];
        }
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [0 => __('Order currency'), 1 => __('Default store currency')];
    }
}
