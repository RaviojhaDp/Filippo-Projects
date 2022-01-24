<?php
namespace Dolphin\Claim\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
	public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
		$installer = $setup;

		$installer->startSetup();

		if(version_compare($context->getVersion(), '1.2.0', '<')) {
		$installer->getConnection()->addColumn(
				$installer->getTable( 'claim' ),
				'country',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'comment'=> 'country'
				]
			);
			$installer->getConnection()->addColumn(
				$installer->getTable( 'claim' ),
				'expire_date',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
					'comment'=> 'expire_date'
				]
			);
		}
		if(version_compare($context->getVersion(), '1.2.1', '<')) {
		$installer->getConnection()->addColumn(
				$installer->getTable( 'claim' ),
				'customer_id',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'comment'=> 'country'
				]
			);
			
		}
		if(version_compare($context->getVersion(), '1.2.2', '<')) {
		$installer->getConnection()->addColumn(
				$installer->getTable( 'claim' ),
				'authenticity',
				[
					'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					'nullable' => true,
					'comment'=> 'authenticity'
				]
			);
			
		}
	}		
}