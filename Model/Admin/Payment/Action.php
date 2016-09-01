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

class Action implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray() {
		$immediate = array(
			'value' => 'immediate',
			'label' => __('Paid Immediatly')
		);
		$deferred = array(
			'value' => 'deferred',
			'label' => __('Defered payment')
		);
		$manual = array(
			'value' => 'manual',
			'label' => __('Paid shipping')
		);

		$manager = \Magento\Framework\App\ObjectManager::getInstance();
		$config  = $manager->get('Paybox\Epayment\Model\Config');
		if ($config->getSubscription() != \Paybox\Epayment\Model\Config::SUBSCRIPTION_OFFER1) {
			$manual['disabled'] = 'disabled';
		}

		$out = [];
		$out[] = $immediate;
		$out[] = $deferred;
		$out[] = $manual;
		return $out;
	}
}