<?php
/**
 * Verifone e-commerce Epayment module for Magento
 *
 * Feel free to contact Verifone e-commerce at support@paybox.com for any
 * question.
 *
 * LICENSE: This source file is subject to the version 3.0 of the Open
 * Software License (OSL-3.0) that is available through the world-wide-web
 * at the following URI: http://opensource.org/licenses/OSL-3.0. If
 * you did not receive a copy of the OSL-3.0 license and are unable
 * to obtain it through the web, please send a note to
 * support@paybox.com so we can mail you a copy immediately.
 *
 * @version   1.0.7-psr
 * @author    BM Services <contact@bm-services.com>
 * @copyright 2012-2017 Verifone e-commerce
 * @license   http://opensource.org/licenses/OSL-3.0
 * @link      http://www.paybox.com/
 */

namespace Paybox\Epayment\Block\Admin\Field;

class Select extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getOptionHtmlAttributes()
    {
        return ['type', 'name', 'class', 'style', 'checked', 'onclick', 'onchange', 'disabled'];
    }

    protected function _optionToHtml($option, $selected)
    {
        if (is_array($option['value'])) {
            $html ='<optgroup label="'.$option['label'].'">'."\n";
            foreach ($option['value'] as $groupItem) {
                $html .= $this->_optionToHtml($groupItem, $selected);
            }
            $html .='</optgroup>'."\n";
        } else {
            $html = '<option value="'.$this->escapeHtml($option['value']).'"';
            $html.= isset($option['title']) ? 'title="'.$this->escapeHtml($option['title']).'"' : '';
            $html.= isset($option['style']) ? 'style="'.$option['style'].'"' : '';
            $html.= isset($option['disabled']) ? 'disabled="disabled"' : '';
            if (in_array($option['value'], $selected)) {
                $html.= ' selected="selected"';
            }
            $html.= '>'.$this->escapeHtml($option['label']). '</option>'."\n";
        }
        return $html;
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $element->addClass('select');
        $html = '<select id="'.$element->getHtmlId().'" name="'.
            $element->getName().'" '.
            $this->serialize($element->getHtmlAttributes()).'>'."\n";

        $value = $element->getValue();
        if (!is_array($value)) {
            $value = [$value];
        }

        if ($values = $element->getValues()) {
            foreach ($values as $key => $option) {
                if (!is_array($option)) {
                    $html.= $this->_optionToHtml(
                        [
                        'value' => $key,
                        'label' => $option],
                        $value
                    );
                } elseif (is_array($option['value'])) {
                    $html.='<optgroup label="'.$option['label'].'">'."\n";
                    foreach ($option['value'] as $groupItem) {
                        $html.= $this->_optionToHtml($groupItem, $value);
                    }
                    $html.='</optgroup>'."\n";
                } else {
                    $html.= $this->_optionToHtml($option, $value);
                }
            }
        }

        $html.= '</select>'."\n";
        $html.= $this->getAfterElementHtml();
        return $html;
    }
}
