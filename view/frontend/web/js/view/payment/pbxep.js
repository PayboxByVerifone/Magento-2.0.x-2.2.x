/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
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
        /** Add view logic here if needed */
        return Component.extend({});
    }
);
