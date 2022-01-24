<?php 
namespace Dolphin\Sap\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        if ($connection->tableColumnExists('sales_order', 'trackflag') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'trackflag',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
                        'length' => 0,
                        'nullable' => false,
                        'comment' => 'Track mail send flag SAP'
                    ]
                );
        }

        if ($connection->tableColumnExists('sales_order', 'tracknumber') === false) {
            $connection
                ->addColumn(
                    $setup->getTable('sales_order'),
                    'tracknumber',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        'length' => 0,
                        'comment' => 'Track number sync with SAP'
                    ]
                );
        }
        $installer->endSetup();
    }
}