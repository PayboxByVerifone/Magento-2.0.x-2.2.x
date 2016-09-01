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

namespace Paybox\Epayment\Model\Admin\Cards;

class PrivateCard extends AbstractCards
{
	public function getConfigNodeName() {
		return 'private';
	}

	public function toOptionArray() {
		$result = array();
		$configPath = $this->getConfigPath();
		// $cards = Mage::getConfig()->getNode($configPath)->asArray();
		$cards = $this->_getConfigValue($configPath);
		if (!empty($cards)) {
			foreach ($cards as $code => $card) {
				$result[] = array(
					'label' => __($card['label']),
					'value' => $code,
				);
			}
		}
		else {
			$result[] = array(
				'label' => __('American Express'),
				'value' => 'AMEX',
			);
			$result[] = array(
				'label' => __('Diners'),
				'value' => 'DINERS',
			);
			$result[] = array(
				'label' => __('JCB'),
				'value' => 'JCB',
			);
		}
		return $result;
	}
}