<?php
namespace Dolphin\CustomImageAttr\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;

class CategoryBannerMobile implements DataPatchInterface, PatchRevertableInterface {

	/**
	 * @var ModuleDataSetupInterface
	 */
	private $moduleDataSetup;
	/**
	 * @var EavSetupFactory
	 */
	private $eavSetupFactory;

	/**
	 * Constructor
	 *
	 * @param ModuleDataSetupInterface $moduleDataSetup
	 * @param EavSetupFactory $eavSetupFactory
	 */
	public function __construct(
		ModuleDataSetupInterface $moduleDataSetup,
		EavSetupFactory $eavSetupFactory
	) {
		$this->moduleDataSetup = $moduleDataSetup;
		$this->eavSetupFactory = $eavSetupFactory;
	}

	/**
	 * {@inheritdoc}
	 */
	public function apply() {
		$this->moduleDataSetup->getConnection()->startSetup();
		/** @var EavSetup $eavSetup */
		$eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
		$eavSetup->addAttribute(
			\Magento\Catalog\Model\Category::ENTITY,
			'categorymobilebanner',
			[
				'type' => 'varchar',
				'label' => 'Category Mobile Banner',
				'input' => 'image',
				'sort_order' => 335,
				'source' => '',
				'global' => ScopedAttributeInterface::SCOPE_STORE,
				'visible' => true,
				'required' => false,
				'user_defined' => false,
				'default' => null,
				'group' => 'General Information',
				'backend' => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
			]
		);

		$this->moduleDataSetup->getConnection()->endSetup();
	}

	public function revert() {
		$this->moduleDataSetup->getConnection()->startSetup();
		/** @var EavSetup $eavSetup */
		$eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
		$eavSetup->removeAttribute(\Magento\Catalog\Model\Category::ENTITY, 'categorymobilebanner');

		$this->moduleDataSetup->getConnection()->endSetup();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAliases() {
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getDependencies() {
		return [];
	}
}