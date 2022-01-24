<?php
namespace Dolphin\Core\Block;

class General extends \Magento\Framework\View\Element\Template {
	protected $_storeManager;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager

	) {
		$this->_storeManager = $storeManager;
		parent::__construct($context);
	}

	public function getWebBaseUrl() {
		return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
	}

	public function getWebsiteStoreCode() {
		return $this->_storeManager->getStore()->getCode();
	}

}