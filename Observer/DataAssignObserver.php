<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
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
            $errorMsg = 'Please select a valid credit card type';
            throw new \LogicException(__($errorMsg));
        }

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $selected = explode(',', $objectManager->get('Paybox\Epayment\Model\Ui\PbxepcbConfig')->getCards());
        if (!in_array($cctype, $selected)) {
            $errorMsg = 'Please select a valid credit card type';
            throw new \LogicException(__($errorMsg));
        }
    }
}
