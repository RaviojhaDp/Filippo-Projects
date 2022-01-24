<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2017 Amasty (https://www.amasty.com)
 * @package Amasty_Storelocator
 */

namespace Dolphin\Core\Controller\Index;

//use Magento\Framework\App\Action;

class Details extends \Magento\Framework\App\Action\Action {

	protected $_pageFactory;

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\View\Result\PageFactory $pageFactory) {
		$this->_pageFactory = $pageFactory;
		return parent::__construct($context);
	}

	public function execute() {
		$result = $this->_pageFactory->create();
		//$result->getConfig()->getTitle()->set("Storelocator"); //setting the page
		//$result->getConfig()->setDescription("Storelocator Details"); // set meta description
		return $result;
	}
}
