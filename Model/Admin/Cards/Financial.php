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

class Financial extends AbstractCards
{
	public function getConfigNodeName() {
		return 'financial';
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
				'label' => __('Carte Cofinoga'),
				'value' => 'COFINOGA',
			);
			$result[] = array(
				'label' => __('Carte Aurore'),
				'value' => 'AURORE',
			);
			$result[] = array(
				'label' => __('1euro.com'),
				'value' => 'UNEURO',
			);
		}
		return $result;
	}
}