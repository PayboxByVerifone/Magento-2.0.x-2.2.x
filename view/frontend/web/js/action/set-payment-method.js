/**
 * Paybox Epayment module for Magento
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
 * @version   1.0.8-meqp
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Verifone e-commerce
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */
define(
    [
        'Magento_Checkout/js/model/quote',
        'Magento_Checkout/js/action/set-payment-information',
    ],
    function (quote, setPaymentInformation) {
        'use strict';

        return function (messageContainer) {
            return setPaymentInformation(messageContainer, quote.paymentMethod());
        };
    }
);
