<?php

namespace Dolphin\CustomerAttributes\Setup;

use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {
	/**
	 * EAV setup factory
	 *
	 * @var \Magento\Eav\Setup\EavSetupFactory
	 */
	private $eavSetupFactory;

	/**
	 * Customer setup factory
	 *
	 * @var CustomerSetupFactory
	 */
	private $customerSetupFactory;

	/**
	 * Constructor
	 *
	 * @param EavSetupFactory $eavSetupFactory
	 * @param CustomerSetupFactory $customerSetupFactory
	 */
	public function __construct(
		EavSetupFactory $eavSetupFactory,
		CustomerSetupFactory $customerSetupFactory
	) {
		$this->eavSetupFactory = $eavSetupFactory;
		$this->customerSetupFactory = $customerSetupFactory;
	}
	public function install(
		ModuleDataSetupInterface $setup,
		ModuleContextInterface $context
	) {
		$setup->startSetup();

		$eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
		$customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

		/**
		 * Create a select box attribute
		 */
		$attributeCode = 'wedding_date';
		$customerSetup->addAttribute(
			\Magento\Customer\Model\Customer::ENTITY,
			$attributeCode,
			[
				'type' => 'static', // backend type
				'label' => 'Wedding Date',
				'input' => 'date', // frontend input
				'source' => '', // source model
				'backend' => \Magento\Eav\Model\Entity\Attribute\Backend\Datetime::class,
				'required' => false,
				'visible' => true,
				'sort_order' => 200,
				'position' => 300,
				'system' => false,
			]
		);
		$attribute = $customerSetup
			->getEavConfig()
			->getAttribute(
				\Magento\Customer\Model\Customer::ENTITY,
				$attributeCode
			)
			->addData(
				['used_in_forms' => [
					'adminhtml_customer',
					'adminhtml_checkout',
					'customer_account_create',
					'customer_account_edit',
				],
				]);

		$attribute->save();
		$setup->endSetup();

	}
}
