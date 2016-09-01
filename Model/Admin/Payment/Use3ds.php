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

class Use3ds {
	public function toOptionArray() {
        $options = array(
			array('value' => 'always', 'label' => __('Yes')),
    		array('value' => 'never', 'label' => __('No')),
			array('value' => 'condition', 'label' => __('Conditionnal')),
        );
    	return $options;
    }
}