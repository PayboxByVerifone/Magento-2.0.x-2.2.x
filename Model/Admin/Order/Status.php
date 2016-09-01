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

/**
 * Order Statuses source model
 */

namespace Paybox\Epayment\Model\Admin\Order;

class Status extends \Magento\Sales\Model\ResourceModel\Order\Status
{
	protected $_stateStatuses = null;

	public function toOptionArray()
	{
		$result = [];
		if (is_array($this->_stateStatuses)) {
			foreach ($this->_stateStatuses as $status) {
				$result[] = array('value' => $status, 'label' => __($status));
			}
		}
		else {
			$result[] = array('value' => $this->_stateStatuses, 'label' => __($this->_stateStatuses));
		}
		return $result;
	}
}