<?php
namespace Calderoni\Faber\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Setup\SalesSetupFactory;
use Magento\Quote\Setup\QuoteSetupFactory;

class InstallData implements InstallDataInterface
{
	/**
     * @var Magento\Sales\Setup\SalesSetupFactory
     */
    protected $_salesSetupFactory;
 
    /**
     * @var Magento\Quote\Setup\QuoteSetupFactory
     */
    protected $_quoteSetupFactory;
 
    /**
     * @param SalesSetupFactory $salesSetupFactory
     * @param QuoteSetupFactory $quoteSetupFactory
     */
    public function __construct(
        SalesSetupFactory $salesSetupFactory,
        QuoteSetupFactory $quoteSetupFactory
    ) {
        $this->_salesSetupFactory = $salesSetupFactory;
        $this->_quoteSetupFactory = $quoteSetupFactory;
    }
 
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {
 
        /** @var \Magento\Sales\Setup\SalesSetup $salesInstaller */
        $salesInstaller = $this->_salesSetupFactory
                        ->create(
                            [
                                'resourceName' => 'sales_setup',
                                'setup' => $setup
                            ]
                        );
        /** @var \Magento\Quote\Setup\QuoteSetup $quoteInstaller */
        $quoteInstaller = $this->_quoteSetupFactory
                        ->create(
                            [
                                'resourceName' => 'quote_setup',
                                'setup' => $setup
                            ]
                        );
 
        $this->addQuoteAttributes($quoteInstaller);
        $this->addOrderAttributes($salesInstaller);
    }
 
    /**
     * add attribute in quote address
     * @param object $installer
     */
    public function addQuoteAttributes($installer) {
        /*
		$installer->addAttribute('quote', 'doc_signedid', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 255,
            'nullable' => true
        ]);
		*/
    }
 
    /**
     * add attribute in sales_order
     * @param object $installer
     */
    public function addOrderAttributes($installer) {
        $installer->addAttribute('order', 'doc_signedid', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 255,
            'nullable' => true
        ]);
        $installer->addAttribute('order', 'doc_type', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 255,
            'nullable' => true
        ]);
        $installer->addAttribute('order', 'doc_number', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 255,
            'nullable' => true
        ]);
        $installer->addAttribute('order', 'doc_released_by', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 255,
            'nullable' => true
        ]);
        $installer->addAttribute('order', 'doc_release_date', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 255,
            'nullable' => true
        ]);
        $installer->addAttribute('order', 'insurance', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 10,
            'nullable' => true
        ]);
        $installer->addAttribute('order', 'custody', [
            'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            'length'=> 10,
            'nullable' => true
        ]);
    }
}
