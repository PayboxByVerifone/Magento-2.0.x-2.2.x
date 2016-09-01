<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Paybox\Epayment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Payment\Observer\AbstractDataAssignObserver;

class DataAssignObserver extends AbstractDataAssignObserver
{
    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument();
        $data = $this->readDataArgument();

        $paymentInfo = $method->getInfoInstance();

        $cctype = $paymentInfo->getCcType();
        if (empty($cctype)) {
            $errorMsg = 'Please select a valid credit card type';
            throw new \LogicException(__($errorMsg));
        }

        $selected = explode(',', $this->_objectManager->get('Paybox\Epayment\Model\Ui\PbxepcbConfig')->getCards());
        if (!in_array($cctype, $selected)) {
            $errorMsg = 'Please select a valid credit card type';
            throw new \LogicException(__($errorMsg));
        }
    }
}
