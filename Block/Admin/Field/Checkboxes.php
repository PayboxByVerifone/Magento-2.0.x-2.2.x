<?php
/**
 * Paybox Epayment module for Magento
 *
 * Feel free to contact Paybox by Verifone at support@paybox.com for any
 * question.
 *
 * LICENSE: This source file is subject to the version 3.0 of the Open
 * Software License (OSL-3.0) that is available through the world-wide-web
 * at the following URI: http://opensource.org/licenses/OSL-3.0. If
 * you did not receive a copy of the OSL-3.0 license and are unable
 * to obtain it through the web, please send a note to
 * support@paybox.com so we can mail you a copy immediately.
 *
 *
 * @version   1.0.0
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Paybox
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
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

    protected function _getOptionHtmlAttributes()
    {
        return array('type', 'name', 'class', 'style', 'checked', 'onclick', 'onchange', 'disabled');
    }

    protected function _optionToHtml($option, \Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
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

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
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
