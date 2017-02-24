<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Paybox\Epayment\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;

class AuthorizationRequest implements BuilderInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @param ConfigInterface $config
     */
    public function __construct(
        ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * Builds ENV request
     *
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /** @var PaymentDataObjectInterface $payment */
        $payment = $buildSubject['payment'];
        $order = $payment->getOrder();
        $address = $order->getShippingAddress();
        
        // return [
        //     'TXN_TYPE' => 'A',
        //     'INVOICE' => $order->getOrderIncrementId(),
        //     'AMOUNT' => $order->getGrandTotalAmount(),
        //     'CURRENCY' => $order->getCurrencyCode(),
        //     'EMAIL' => $address->getEmail(),
        //     'MERCHANT_KEY' => $this->config->getValue(
        //         'merchant_gateway_key',
        //         $order->getStoreId()
        //     )
        // ];


        $config = $this->getConfig();

        // URLs
        $baseUrl = 'pbxep/payment';
        $values = array(
            'PBX_ANNULE' => $this->_buildUrl($baseUrl . '/cancel'),
            'PBX_EFFECTUE' => $this->_buildUrl($baseUrl . '/success'),
            'PBX_REFUSE' => $this->_buildUrl($baseUrl . '/failed'),
            'PBX_REPONDRE_A' => $this->_buildUrl($baseUrl . '/ipn'),
        );

        // Merchant information
        $values['PBX_SITE'] = $config->getSite();
        $values['PBX_RANG'] = substr(sprintf('%02d', $config->getRank()), -2);
        $values['PBX_IDENTIFIANT'] = $config->getIdentifier();

        // Card information
        $cards = $payment->getCards();
        if ($payment->getHasCctypes()) {
            $code = $order->getPayment()->getData('cc_type');
        } else {
            $code = array_keys($cards);
            $code = $code[0];
        }
        if (!isset($cards[$code])) {
            $message = 'No card with code %s.';
            Mage::throwException(Mage::helper('pbxep')->__($message), $code);
        }
        $card = $cards[$code];
        $values['PBX_TYPEPAIEMENT'] = $card['payment'];
        $values['PBX_TYPECARTE'] = $card['card'];

        // Order information
        $values['PBX_PORTEUR'] = $this->getBillingEmail($order);
        $values['PBX_DEVISE'] = $this->getCurrency($order);
        $values['PBX_CMD'] = $this->tokenizeOrder($order);

        // Amount
        $orderAmount = $order->getBaseGrandTotal();
        $amountScale = $this->_currencyDecimals[$values['PBX_DEVISE']];
        $amountScale = pow(10, $amountScale);
        if ($payment->getCode() == 'pbxep_threetime') {
            $amounts = $this->computeThreetimePayments($orderAmount, $amountScale);
            foreach ($amounts as $k => $v) {
                $values[$k] = $v;
            }
        }
        else {
            $values['PBX_TOTAL'] = sprintf('%03d', round($orderAmount * $amountScale));
            switch ($payment->getPayboxAction()) {
                case Paybox_Epayment_Model_Payment_Abstract::PBXACTION_MANUAL:
                    $values['PBX_AUTOSEULE'] = 'O';
                    break;

                case Paybox_Epayment_Model_Payment_Abstract::PBXACTION_DEFERRED:
                    $delay = (int) $payment->getConfigData('delay');
                    if ($delay < 1) {
                        $delay = 1;
                    } else if ($delay > 7) {
                        $delay = 7;
                    }
                    $values['PBX_DIFF'] = sprintf('%02d', $delay);
                    break;
            }
        }

        // 3-D Secure
        if (!$payment->is3DSEnabled($order)) {
            $values['PBX_3DS'] = 'N';
        }

        // Paybox => Magento
        $values['PBX_RETOUR'] = 'M:M;R:R;T:T;A:A;B:B;C:C;D:D;E:E;F:F;G:G;H:H;I:I;J:J;N:N;O:O;P:P;Q:Q;S:S;W:W;Y:Y;K:K';
        $values['PBX_RUF1'] = 'POST';

        // Choose correct language
        $lang = Mage::app()->getLocale();
        if (!empty($lang)) {
            $lang = preg_replace('#_.*$#', '', $lang->getLocaleCode());
        }
        $languages = $config->getLanguages();
        if (!array_key_exists($lang, $languages)) {
            $lang = 'default';
        }
        $lang = $languages[$lang];
        $values['PBX_LANGUE'] = $lang;

        // Choose page format depending on browser/devise
        if (Mage::helper('pbxep/mobile')->isMobile()) {
            $values['PBX_SOURCE'] = 'XHTML';
        }

        $values['PBX_SOURCE'] = 'RWD';

        // Misc.
        $values['PBX_TIME'] = date('c');
        $values['PBX_HASH'] = strtoupper($config->getHmacAlgo());

        // Card specific workaround
        if (($card['payment'] == 'LEETCHI') && ($card['card'] == 'LEETCHI')) {
            $values['PBX_EFFECTUE'] .= '?R='.urlencode($values['PBX_CMD']);
            $values['PBX_REFUSE'] .= '?R='.urlencode($values['PBX_CMD']);
        }
        else if (($card['payment'] == 'PREPAYEE') && ($card['card'] == 'IDEAL')) {
            $s =  '?C=IDEAL&P=PREPAYEE';
            $values['PBX_ANNULE'] .= $s;
            $values['PBX_EFFECTUE'] .= $s;
            $values['PBX_REFUSE'] .= $s;
            $values['PBX_REPONDRE_A'] .= $s;
        }

        // Sort parameters for simpler debug
        ksort($values);

        // Sign values
        $sign = $this->signValues($values);

        // Hash HMAC
        $values['PBX_HMAC'] = $sign;

        return $values;
    }
}
