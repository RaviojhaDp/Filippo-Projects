<?php
namespace Dolphin\Sap\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action {
	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory) {
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute() {
		// for testing route unlock products
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();

		$resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
		$connection = $resource->getConnection();

		$time = time();
		$to = date('Y-m-d H:i:s', $time);

		$lastTime = $time - 7200; // 60*60*2
		$from = date('Y-m-d H:i:s', $lastTime);

		$quoteCollection = $objectManager->get('\Magento\Quote\Model\Quote\Item')->getCollection();
		//$quoteCollection->addFieldToFilter('created_at',array('from' => $from,'to' => $to));

		$quoteCollection = $quoteCollection->getData();
		// echo "<pre>";
		// print_r($quoteCollection);
		// exit;
		$sku1 = array();
		foreach ($quoteCollection as $quote) {
			$createdTime = strtotime($quote['created_at']);
			$currentTime = strtotime($to);
			$minutes = round(abs($currentTime - $createdTime) / 60, 2);
			if ($minutes >= 60) {
				$sku1[] = $quote['sku'];
				$productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
				try {
					$productidcollection = $productRepository->get($quote['sku']);
					$pid = $productidcollection->getId();
					$product = $objectManager->create('Magento\Catalog\Model\Product')->load($pid);
					if ($product->getData('is_locked') == 1) {
						$product->setData('is_locked', 0);
						$product->save();

						$sqlselect = "select * from `quote_item` where product_id = " . $pid;
						$selectres = $connection->query($sqlselect);
						//echo "<pre>";
						//print_r($selectres);

						foreach ($selectres as $s) {
							$quoteid = $s['quote_id'];
							$itemid = $s['item_id'];

							$quote = $objectManager->create('Magento\Quote\Model\Quote')->load($quoteid);

							$quoteItem = $objectManager->create('Magento\Quote\Model\Quote\Item')->load($itemid);
							$quoteItem->delete();

							$quote->setTotalsCollectedFlag(false);
							$quote->collectTotals()->save();
							$_cacheTypeList = $objectManager->create('Magento\Framework\App\Cache\TypeListInterface');
							$_cacheFrontendPool = $objectManager->create('Magento\Framework\App\Cache\Frontend\Pool');
							$types = array('config', 'layout', 'block_html', 'collections', 'reflection', 'db_ddl', 'eav', 'config_integration', 'config_integration_api', 'full_page', 'translate', 'config_webservice');
							foreach ($types as $type) {
								$_cacheTypeList->cleanType($type);
							}
							foreach ($_cacheFrontendPool as $cacheFrontend) {
								$cacheFrontend->getBackend()->clean();
							}
						}
					}

				} catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
					$productidcollection = false;
				}
			}
		}
		// echo "<pre>";
		// print_r($sku1);
		// exit;
	}
}