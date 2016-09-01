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

namespace Paybox\Epayment\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface {

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context) {
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
