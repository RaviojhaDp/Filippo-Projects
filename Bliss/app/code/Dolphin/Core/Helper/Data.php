<?php
namespace Dolphin\Core\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {

	/*
		    @var \Magento\Store\Model\StoreManagerInterface
	*/
	protected $_storeManager;

	public function __construct(
		\Magento\Framework\App\Helper\Context $context,
		\Magento\Customer\Model\Session $customerSession,
		\Magento\Store\Model\StoreManagerInterface $storeManager

	) {
		$this->customerSession = $customerSession;
		parent::__construct($context);
		$this->_storeManager = $storeManager;
	}

	public function isLoggedIn() {
		return $this->customerSession->isLoggedIn();
	}
	public function getPubMediaUrl() {
		$storeManager = $this->_storeManager;
		return $storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
	}
	public function getStoreId() {
		return $this->_storeManager->getStore()->getId();
	}
}
