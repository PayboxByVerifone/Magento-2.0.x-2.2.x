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
 * @version   1.0.0
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Verifone e-commerce
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'pbxep_cb',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_multi-method'
            },
            {
                type: 'pbxep_threetime',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_multi-method'
            },
            {
                type: 'pbxep_paypal',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_simple-method'
            },
            {
                type: 'pbxep_private',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_multi-method'
            },
            {
                type: 'pbxep_prepaid',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_multi-method'
            },
            {
                type: 'pbxep_financial',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_multi-method'
            },
            {
                type: 'pbxep_bcmc',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_simple-method'
            },
            {
                type: 'pbxep_paybuttons',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_multi-method'
            },
            {
                type: 'pbxep_maestro',
                component: 'Paybox_Epayment/js/view/payment/method-renderer/pbxep_simple-method'
            }
        );

        // Add view logic here if needed

        return Component.extend({});
    }
);
