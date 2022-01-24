<?php
namespace Dolphin\Core\Block;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\View\Element\Template;

class Details extends \Magento\Framework\View\Element\Template {
	//protected $pageConfig;
	public function __construct(
		Template\Context $context,
		ObjectManagerInterface $objectManager,
		//\Magento\Framework\View\Page\Config $pageConfig,

		array $data = []
	) {
		parent::__construct($context, $data);
		$this->objectManager = $objectManager;

	}

	public function getLocationdetails($id) {
		$modelcollection = $this->objectManager->create('Amasty\Storelocator\Model\Location')->load(1)->getCollection()->addFieldToFilter('id', $id)->getData();
		return $modelcollection;
	}
}
