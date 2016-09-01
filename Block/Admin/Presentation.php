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

namespace Paybox\Epayment\Block\Admin;
use Magento\Framework\View\Element\Template;

class Presentation extends Template
{
	protected function _construct()
    {
    	parent::_construct();
        $this->setTemplate($this->getHtmlTemplate());
    }

    public function getHtmlTemplate() {
    	$manager = \Magento\Framework\App\ObjectManager::getInstance();
		$config  = $manager->get('Paybox\Epayment\Model\Config');
        $lang = $manager->get('Magento\Framework\Locale\Resolver');
        if (!empty($lang)) {
            $lang = preg_replace('#_.*$#', '', $lang->getLocale());
        }
        if (!in_array($lang, array('fr', 'en'))) {
            $lang = 'en';
        }
        return 'pbxep/presentation/'.$lang.'.phtml';
    }
}