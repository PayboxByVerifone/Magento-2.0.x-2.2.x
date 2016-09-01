<?php

namespace Paybox\Epayment\Model\Admin\Payment;

class Actionpaypal implements \Magento\Framework\Option\ArrayInterface
{
	public function toOptionArray() {
		$immediate = array(
			'value' => 'immediate',
			'label' => __('Paid Immediatly')
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
		$out[] = $manual;
		return $out;
	}
}