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

namespace Paybox\Epayment\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable('sales_order_payment');
        $columns = array(
            'pbxep_action' => array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                 'comment' => 'generic_suffix action',
            ),
            'pbxep_delay' => array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'generic_suffix delay',
            ),
            'pbxep_authorization' => array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'generic_suffix _authorization',
            ),
            'pbxep_capture' => array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'generic_suffix capture',
            ),
            'pbxep_first_payment' => array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'generic_suffix first payment',
            ),
            'pbxep_second_payment' => array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'generic_suffix second _payment',
            ),
            'pbxep_third_payment' => array(
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'comment' => 'generic_suffix third _payment',
            ),
        );

        $connection = $setup->getConnection();
        foreach ($columns as $name => $definition) {
            $connection->addColumn($tableName, $name, $definition);
        }

        $setup->endSetup();
    }
}
