<?php
namespace Dolphin\Core\Block;
use Mageplaza\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class Blog extends \Magento\Framework\View\Element\Template {

	/**
	 * Post Collection Factory
	 *
	 * @var PostCollectionFactory
	 */
	public $postCollectionFactory;
	protected $_storeManager;

	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		PostCollectionFactory $postCollectionFactory,
		\Magento\Store\Model\StoreManagerInterface $storeManager

	) {
		$this->postCollectionFactory = $postCollectionFactory;
		$this->_storeManager = $storeManager;
		parent::__construct($context);
	}

	public function getBlog() {
		$collection = $this->postCollectionFactory->create();
		$items = [];
		foreach ($collection as $key => $blog) {
			if ($blog->getEnabled() == 1) {
				$items[$key]['image'] = $blog->getImage();
				$items[$key]['name'] = $blog->getName();
				$items[$key]['url'] = $blog->getUrlKey();
				$items[$key]['short_description'] = $blog->getShortDescription();
			}
		}

		return $items;
	}

	public function getWebBaseUrl() {
		return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
	}
}