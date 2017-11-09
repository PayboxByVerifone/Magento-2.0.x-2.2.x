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
 *
 * @version   1.0.5
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Verifone e-commerce
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
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
                template: 'Paybox_Epayment/payment/pbxep_simple',
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
                /** Redirect to Genericclass */
                afterPlaceOrder: function (lastOrderId) {
                    $.mage.cookies.set('lastOrderId', lastOrderId);
                    $.mage.redirect(url.build('pbxep/payment/redirect/'));
                }
            });
     }
 );