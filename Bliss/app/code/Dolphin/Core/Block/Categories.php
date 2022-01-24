<?php
namespace Dolphin\Core\Block;

class Categories extends \Magento\Framework\View\Element\Template {
	protected $_storeManager;
	protected $categoryRepository;
	/**
	 * @var Category
	 */
	private $category;
	protected $_filesystem;
	protected $_imageFactory;
	public function __construct(\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Model\CategoryRepository $categoryRepository,
		\Magento\Framework\Filesystem $filesystem,
		\Magento\Framework\Image\AdapterFactory $imageFactory,
		\Magento\Catalog\Model\Category $category

	) {
		$this->_storeManager = $storeManager;
		$this->categoryRepository = $categoryRepository;
		$this->_filesystem = $filesystem;
		$this->_imageFactory = $imageFactory;
		$this->category = $category;
		parent::__construct($context);
	}

	public function getCategories($id) {

		$items = [];
		$parent_category_id = $id;
		$categoryObj = $this->categoryRepository->get($parent_category_id);
		$subcategories = $categoryObj->getChildrenCategories();
		$subcategories->setPageSize(6);

		foreach ($subcategories as $key => $subcategorie) {
			$category = $this->category->load($subcategorie->getId());
			// echo "<pRE>";
			// print_r($category->debug());
			// exit;
			$items[$key]['imagename'] = $category->getImage();
			$items[$key]['image'] = $category->getData('thumbnail');
			$items[$key]['name'] = $category->getName();
			$items[$key]['url'] = $subcategorie->getUrl();
			$items[$key]['mobileboximage'] = $category->getData('mobileboximage');
		}
		// echo "<pre>";
		// print_r($items);die;
		// exit;
		return $items;
	}
	public function getWebBaseUrl() {
		return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
	}
	public function getImageResizeUrl($image, $width, $height) {
		echo $image;

		$finalImage = explode("/", $image);
		// echo $finalImage[4];
		// exit;
		echo $absolutePath = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('category/resizeimages/') . $finalImage[4];

		echo $imageResized = $this->_filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('resized/' . $width . '/') . $finalImage[4];
		//exit;
		//create image factory...
		$imageResize = $this->_imageFactory->create();
		$imageResize->open($absolutePath);
		$imageResize->constrainOnly(TRUE);
		$imageResize->keepTransparency(TRUE);
		$imageResize->keepFrame(FALSE);
		$imageResize->keepAspectRatio(TRUE);
		$imageResize->resize(520, 520);
		//destination folder
		$destination = $imageResized;
		//save image
		$imageResize->save($destination);

		echo $resizedURL = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'resized/' . $width . '/' . $image;
		exit;
		return $resizedURL;
	}
}