<?php
namespace Dolphin\Insurance\Observer;

use Magento\Framework\View\Page\Config;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Registry;
use Magento\Catalog\Api\CategoryRepositoryInterface;

class Addclass implements \Magento\Framework\Event\ObserverInterface
{
	private $_registry;
	protected $categoryRepository;
	
	 public function __construct(
        Registry $registry,
		Config $config,
		\Magento\Catalog\Model\CategoryRepository $categoryRepository
    ){
		$this->_registry = $registry;
        $this->config = $config;
		 $this->categoryRepository = $categoryRepository;
		
    }
	
  public function execute(\Magento\Framework\Event\Observer $observer)
  {
	    $name = $observer->getFullActionName();
        $layout = $observer->getLayout();
		if($name == "catalog_category_view"){
		$category = $this->_registry->registry('current_category');
		if($category->getData('level') == '3'){
			$parent_id = $category->getParentId();
			$cat = $this->categoryRepository->get($parent_id);
            $this->config->addBodyClass($cat->getName());
		}
		if($category->getData('level') == '2'){
			$this->config->addBodyClass($category->getName());
		 }
		}

     return $this;
  }
}