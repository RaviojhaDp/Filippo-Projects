<?php
/**
 * A Magento 2 module named Dolphin/Iclients
 * Copyright (C) 2019 
 * 
 * This file included in Dolphin/Iclients is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Dolphin\Iclients\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $table_dolphin_iclients_iclients = $setup->getConnection()->newTable($setup->getTable('dolphin_iclients_iclients'));

        $table_dolphin_iclients_iclients->addColumn(
            'iclients_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true,'nullable' => false,'primary' => true,'unsigned' => true,],
            'Entity ID'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'name'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'url_param',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'url_param'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'website_link',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'website_link'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'identify_code',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'identify_code'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'display_logo',
            \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
            null,
            [],
            'display_logo'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'header',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'header'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'logo',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'logo'
        );

        $table_dolphin_iclients_iclients->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'store_id'
        );

        $setup->getConnection()->createTable($table_dolphin_iclients_iclients);
    }
}
