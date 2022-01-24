<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Pwa\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /*
         * Create table 'push_notification'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('push_notification'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'registration_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Registration ID'
            )
            ->addColumn(
                'end_point',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'End Point'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Update Time'
            )
            ->setComment('Push Notification Table');
        $installer->getConnection()->createTable($table);

        /*
         * Create table 'push_notification_message'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('push_notification_message'))
            ->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Entity ID'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Title'
            )
            ->addColumn(
                'body',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Body'
            )
            ->addColumn(
                'url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Target Url'
            )
            ->addColumn(
                'icon',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'Icon'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                11,
                ['unsigned' => true, 'nullable' => false, 'default' => '0'],
                'status'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Creation Time'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [],
                'Update Time'
            )
            ->setComment('Push Notification Messages Table');
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
