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

namespace Paybox\Epayment\Model\Admin\Order\Status;

class Mode extends \Paybox\Epayment\Model\Admin\Order\Status
{
    //	protected $_stateStatuses = array(
    //		\Magento\Sales\Model\Order::STATE_NEW,
    //		\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT,
    //	);

    public function toOptionArray()
    {
        $options = parent::toOptionArray();
        $options[0] = [
            'value' => 'manual',
            'label' => __('Manual capture only'),
        ];
        //    	$options[1] = array(
        //    		'value' => 'state',
        //    		'label' => __('On order state change'),
        //    	);
        $options[2] = [
            'value' => 'shipment',
            'label' => __('On order shipment'),
        ];
        return $options;
    }
}
