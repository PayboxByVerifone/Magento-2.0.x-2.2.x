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

namespace Paybox\Epayment\Gateway\Request;

use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class VoidRequest implements BuilderInterface
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
     * @param  array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment'])
            || !$buildSubject['payment'] instanceof PaymentDataObjectInterface
        ) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        /**
         * @var PaymentDataObjectInterface $paymentDO
         */
        $paymentDO = $buildSubject['payment'];

        $order = $paymentDO->getOrder();
        $payment = $paymentDO->getPayment();

        if (!$payment instanceof OrderPaymentInterface) {
            throw new \LogicException('Order payment should be provided.');
        }

        return [
            'TXN_TYPE' => 'V',
            'TXN_ID' => $payment->getLastTransId(),
            'MERCHANT_KEY' => $this->config->getValue(
                'merchant_gateway_key',
                $order->getStoreId()
            )
        ];
    }
}
