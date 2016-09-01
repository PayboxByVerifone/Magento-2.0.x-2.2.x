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

class Info extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * Render element html
     *
     * @param Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element) {
        $html = '<tr id="row_'.$element->getHtmlId().
        '"><td class="label" colspan="5" style="text-align: left;"><span id="'.$element->getHtmlId().'">';
        $html .= $element->getLabel();
        $html .= '</span></td></tr>';
        return $html;
    }
}