<?php

namespace Dolphin\Certificato\Setup;

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

		$installer->run('create table certificato(certificato_id int not null auto_increment,
customer_group_id int(11),
 name varchar(100),
surname varchar(100),
address varchar(100),
zipcode varchar(100),
city varchar(100),
province varchar(100),
phone varchar(100),
mobile_phone varchar(100),
fiscal_code varchar(100),
dob date,
sex varchar(100),
civil_status varchar(100),
degree_education varchar(100),
profession varchar(100),
buying_opportunity varchar(100),
reason_purchase varchar(100),
reason_choice varchar(100),
came_to_know varchar(100),
equpiment  varchar(100),
model varchar(100),
serial_number varchar(100),
name_boutique_retailer varchar(100),
add_boutique_retailer varchar(100),
seller_name varchar(100),
purchase_date date,
receipt_number varchar(100),
general_conditions TINYINT(1),
privacy TINYINT(1),
marketing TINYINT(1),
profiling TINYINT(1),
cession TINYINT(1),
 primary key(certificato_id))');


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