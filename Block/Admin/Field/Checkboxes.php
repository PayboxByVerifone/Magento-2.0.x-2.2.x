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

namespace Paybox\Epayment\Block\Admin\Field;

class Checkboxes extends \Magento\Config\Block\System\Config\Form\Field
{
	protected $_coreHelper;

	public function __construct()
	{
		$manager = \Magento\Framework\App\ObjectManager::getInstance();
		$this->_coreHelper  = $manager->get('\Magento\Framework\Api\DataObjectHelper');
	}

    protected function _getOptionHtmlAttributes() {
        return array('type', 'name', 'class', 'style', 'checked', 'onclick', 'onchange', 'disabled');
    }

	protected function _optionToHtml($option, \Magento\Framework\Data\Form\Element\AbstractElement $element) {
        $id = $element->getHtmlId().'_'.$this->_coreHelper->escapeHtml($option['value']);

        $html = '<li><input id="'.$id.'"';
        foreach ($this->_getOptionHtmlAttributes() as $attribute) {
            if ($value = $element->getDataUsingMethod($attribute, $option['value'])) {
            	if ($attribute == 'name') {
            		$value .= '[]';
            	}
                $html .= ' '.$attribute.'="'.$value.'"';
            }
        }
        $html .= ' value="'.$option['value'].'" />'
            . ' <label for="'.$id.'">' . $option['label'] . '</label></li>'
            . "\n";
        return $html;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
    	$element->setValue(explode(',', $element->getValue()));
    	$values = $element->getValues();

        if (!$values) {
            return '';
        }

        $name = $element->getDataUsingMethod('name', 'NONE');
        $html = '<input type="hidden" name="'.$name.'[]" value="NONE"/>';
        $html  .= '<ul class="checkboxes" id="'.$this->escapeHtml($element->getHtmlId()).'">';
        foreach ($values as $value) {
            $html.= $this->_optionToHtml($value, $element);
        }
        $html .= '</ul>'. $this->getAfterElementHtml();

        return $html;
	}
}