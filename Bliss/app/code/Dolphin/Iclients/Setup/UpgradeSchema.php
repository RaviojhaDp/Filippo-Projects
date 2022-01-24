<?php

namespace Dolphin\Iclients\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if (version_compare($context->getVersion(), "1.0.3", "<")) {

            $tableName = $setup->getTable('dolphin_iclients_iclients');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'ifraem',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'ifraem',
                    ]
                );
                $connection->addColumn(
                    $tableName,
                    'link',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        255,
                        [],
                        'comment' => 'link',
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), "1.0.4", "<")) {

            $tableName = $setup->getTable('sales_order_grid');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'url_param',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'Iclients',
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), "1.0.5", "<")) {

            $tableName = $setup->getTable('sales_order');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'url_param',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'Iclients',
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), "1.0.6", "<")) {

            $tableName = $setup->getTable('dolphin_iclients_iclients');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'sap_code',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'SAP code',
                    ]
                );
            }
            $tableName1 = $setup->getTable('sales_order');
            if ($setup->getConnection()->isTableExists($tableName1) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName1,
                    'ecorner_sellout',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        'comment' => 'ecorner_sellout',
                        'default' => '0',
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), "1.0.7", "<")) {

            $tableName = $setup->getTable('dolphin_iclients_iclients');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'logo_align',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'Logo Alignment',
                    ]
                );
                $connection->addColumn(
                    $tableName,
                    'content_align',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'Content Alignment',
                    ]
                );
            }
        }

        if (version_compare($context->getVersion(), "1.0.8", "<")) {

            $tableName = $setup->getTable('dolphin_iclients_iclients');
            if ($setup->getConnection()->isTableExists($tableName) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName,
                    'header_bg_color',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'Header BG Color',
                    ]
                );
                $connection->addColumn(
                    $tableName,
                    'text_color',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        null,
                        [],
                        'comment' => 'Header Text Color',
                    ]
                );
            }
        }
        if (version_compare($context->getVersion(), "1.0.9", "<")) {
            $tableName1 = $setup->getTable('sales_order');
            if ($setup->getConnection()->isTableExists($tableName1) == true) {
                $connection = $setup->getConnection();
                $connection->addColumn(
                    $tableName1,
                    'sap_error',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                        null,
                        'comment' => 'ecorner_sellout',
                        'default' => '0',
                    ]
                );
            }
        }
        $setup->endSetup();
    }
}
