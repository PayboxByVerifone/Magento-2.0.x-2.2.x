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
 * @version   1.0.13-exception
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Verifone e-commerce
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */

namespace Paybox\Epayment\Observer;

use Magento\Framework\DataObject;
use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;
use Magento\Quote\Api\Data\PaymentInterface;

class DataAssignObserver extends AbstractDataAssignObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument($observer);
        if ($method->getCode() !== 'pbxep_cb' || $method->getHasCctypes() === false) {
            return;
        }
        $data = $this->readDataArgument($observer);
        $additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_array($additionalData)) {
            return;
        }

        $additionalData = new DataObject($additionalData);
        $payment = $observer->getPaymentModel();
        $payment->setCcType($additionalData->getData('cc_type'));

        $cctype = $payment->getCcType();
        if (empty($cctype)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Please select a valid credit card type')
            );
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $selected = explode(',', $objectManager->get('Paybox\Epayment\Model\Ui\PbxepcbConfig')->getCards());
        if (!in_array($cctype, $selected)) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Please select a valid credit card type')
            );
        }
    }
}
