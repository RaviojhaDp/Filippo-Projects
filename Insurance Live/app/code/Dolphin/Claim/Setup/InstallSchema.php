<?php

namespace Dolphin\Claim\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\DB\Adapter\AdapterInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.0') < 0){

		$installer->run('create table claim(claim_id int not null auto_increment,
		certificato_id int(11),
		customer_group_id int(11),
		 name varchar(100),
		surname varchar(100),
		address varchar(100),
		zipcode varchar(100),
		city varchar(100),
		province varchar(100),
		counntry varchar(100),
		phone varchar(100),
		mobile_phone varchar(100),
		fiscal_code varchar(100),
		dob date,
		sex varchar(100),
		email varchar(100),
		equipment  varchar(100),
		model varchar(100),
		serial_number varchar(100),
		name_boutique_retailer varchar(100),
		add_boutique_retailer varchar(100),
		purchase_date date,
		date_of_termination date,
		authority varchar(100),
		location_authority varchar(100),
		doc_number varchar(100),
		describe_events varchar(100),
		complaint varchar(100),
		daimiani_spa varchar(100), 
		primary key(claim_id))');


		//demo 
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$scopeConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
//$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/updaterates.log');
//$logger = new \Zend\Log\Logger();
//$logger->addWriter($writer);
//$logger->info('updaterates');
//demo 

		}

        $installer->endSetup();

    }
}