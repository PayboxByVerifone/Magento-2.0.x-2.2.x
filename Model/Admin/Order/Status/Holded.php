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

namespace Paybox\Epayment\Model\Admin\Order\Status;

class Holded extends \Paybox\Epayment\Model\Admin\Order\Status
{
	protected $_stateStatuses = \Magento\Sales\Model\Order::STATE_HOLDED;
}
