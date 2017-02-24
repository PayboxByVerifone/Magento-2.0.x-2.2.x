/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
 /*browser:true*/
 /*global define*/
 define(
    [
    'jquery',
    'Magento_Checkout/js/view/payment/default',
    'Paybox_Epayment/js/action/set-payment-method',
    'Magento_Checkout/js/model/full-screen-loader',
    'mage/url',
    ],
    function ($, Component, setPaymentMethodAction, fullScreenLoader,url) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Paybox_Epayment/payment/pbxep_multi',
                transactionResult: ''
            },
            initObservable: function () {
                this._super()
                .observe([
                    'billingAgreement'
                    ]);
                return this;
            },
            getCode: function () {
                return this.item.method;
            },
            getData: function () {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'cc_type': this.getCreditCardType()
                    }
                };
            },
            getCards: function () {
                return window.checkoutConfig.payment[this.item.method].cards;
            },
            getCreditCardType: function () {
                return jQuery('input[name="payment[cc_type]"]:checked').val();
            },
            continueToPaybox: function () {
                this.redirectAfterPlaceOrder = false;
                    this.selectPaymentMethod(); // save selected payment method in Quote
                    setPaymentMethodAction(this.messageContainer);
                    this.placeOrder();
                    return false;
                },
                /** Redirect to Paybox */
                afterPlaceOrder: function (lastOrderId) {
                    $.mage.cookies.set('lastOrderId', lastOrderId);
                    $.mage.redirect(url.build('pbxep/payment/redirect/'));
                }
            });
    }
    );