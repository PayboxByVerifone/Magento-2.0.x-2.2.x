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

namespace Paybox\Epayment\Block\Checkout;

class Payment
{
	protected function _construct() {
		parent::_construct();
		$this->setTemplate('pbxep/checkout-payment.phtml');
	}

	protected function _prepareLayout() {
		$head = $this->getLayout()->getBlock('head');
		if (!empty($head)) {
			$head->addCss('css/pbxep/styles.css');
		}
		return parent::_prepareLayout();
	}

	public function getCreditCards() {
		$result = array();
		$cards = $this->getMethod()->getCards();
		$selected = explode(',', $this->getMethod()->getConfigData('cctypes'));
		foreach ($cards as $code => $card) {
			if (in_array($code, $selected)) {
				$result[$code] = $card;
			}
		}
		return $result;
	}
	
	public function getCards() {
		$result = array();
		$cards = $this->getMethod()->getCards();
		$selected = explode(',', $this->getMethod()->getConfigData('cctypes'));
		foreach ($cards as $code => $card) {
			if (in_array($code, $selected)) {
				$result[$code] = $card;
			}
		}
		return $result;
	}

	public function getMethodLabelAfterHtml() {
		$cards = $this->getCreditCards();
		$html = array();
		foreach ($cards as $card) {
			$url = $this->htmlEscape($this->getSkinUrl($card['image']));
			$alt = $this->htmlEscape($card['label']);
			$html[] = '<img class="pbxep-payment-logo" src="'.$url.'" alt="'.$alt.'"/>';
		}
		$html = '<span class="pbxep-payment-label">'.implode('&nbsp;', $html).'</span>';
		return $html;
	}
}
