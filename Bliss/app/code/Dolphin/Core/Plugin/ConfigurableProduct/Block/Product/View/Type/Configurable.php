<?php
namespace Dolphin\Core\Plugin\ConfigurableProduct\Block\Product\View\Type;

class Configurable {

	public function afterGetJsonConfig(
		\Magento\ConfigurableProduct\Block\Product\View\Type\Configurable $subject,
		$result
	) {

		$jsonResult = json_decode($result, true);
		$jsonResult['size'] = [];
		$jsonResult['other_material_grams'] = [];
		$jsonResult['d_collection'] = [];
		$jsonResult['categorys'] = [];
		$jsonResult['material'] = [];
		$jsonResult['chain_length'] = [];
		$jsonResult['carat'] = [];
		$jsonResult['description'] = [];
		$jsonResult['purity'] = [];
		$jsonResult['stone'] = [];
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		foreach ($subject->getAllowProducts() as $simpleProduct) {
			$product = $objectManager->create('Magento\Catalog\Model\Product')->load($simpleProduct->getId());
			$jsonResult['size'][$simpleProduct->getId()] = $product->getResource()->getAttribute('size')->getFrontend()->getValue($product);
			$jsonResult['other_material_grams'][$simpleProduct->getId()] = $product->getResource()->getAttribute('other_material_grams')->getFrontend()->getValue($product);
			$jsonResult['d_collection'][$simpleProduct->getId()] = $product->getResource()->getAttribute('d_collection')->getFrontend()->getValue($product);
			$jsonResult['categorys'][$simpleProduct->getId()] = $product->getResource()->getAttribute('categorys')->getFrontend()->getValue($product);
			$jsonResult['material'][$simpleProduct->getId()] = $product->getResource()->getAttribute('material')->getFrontend()->getValue($product);
			$jsonResult['chain_length'][$simpleProduct->getId()] = $product->getResource()->getAttribute('chain_length')->getFrontend()->getValue($product);
			$jsonResult['carat'][$simpleProduct->getId()] = $product->getResource()->getAttribute('carat')->getFrontend()->getValue($product);
			$jsonResult['description'][$simpleProduct->getId()] = $product->getResource()->getAttribute('description')->getFrontend()->getValue($product);
			$jsonResult['purity'][$simpleProduct->getId()] = $product->getResource()->getAttribute('purity')->getFrontend()->getValue($product);
			$jsonResult['stone'][$simpleProduct->getId()] = $product->getResource()->getAttribute('stone')->getFrontend()->getValue($product);
		}
		$result = json_encode($jsonResult);
		return $result;
	}
}