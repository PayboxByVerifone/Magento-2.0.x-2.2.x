<?php
/**
 * Paybox Epayment module for Magento
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * available at : http://opensource.org/licenses/osl-3.0.php
 *
 * @package    Paybox_Epayment
 * @copyright  Copyright (c) 2013-2014 Paybox
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Paybox\Epayment\Model\Admin\Payment;

class Same {
	public function toOptionArray() {
        $options = array(
        		array('value' => '', 'label' => __('')),
				array('value' => 'same', 'label' => __('Same')),
				array('value' => 'different', 'label' => __('Different')),
            );
    	return $options;
    }
}