<?php

namespace Dolphin\Insurance\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

class Ajax extends \Magento\Framework\App\Action\Action
{
	protected $_storeManager;

	/**
     * @var PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;
 
 
    /**
     * View constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     */
	 public function __construct(Context $context,  \Magento\Store\Model\StoreManagerInterface $storeManager, PageFactory $resultPageFactory, JsonFactory $resultJsonFactory)
		{
	 
			$this->_resultPageFactory = $resultPageFactory;
			$this->_resultJsonFactory = $resultJsonFactory;
			$this->_storeManager = $storeManager;
	 
			parent::__construct($context);
		}
	
    public function execute()
    {
		
		$post = $this->getRequest()->getPostValue('param');
		$poststore = $this->getRequest()->getPostValue('storeId');
		$result = $this->_resultJsonFactory->create();
        $resultPage = $this->_resultPageFactory->create();
		if($post == "client"){
		$block = $resultPage->getLayout()
                ->createBlock('Magento\Customer\Block\Form\Register')->setKey($poststore)
                ->setTemplate('Dolphin_Insurance::client_append.phtml')
                ->toHtml();
				//echo $block;
		        return $this->getResponse()->setBody($block);

		}
		if ($post == "bot"){
		$block = $resultPage->getLayout()
                ->createBlock('Magento\Customer\Block\Form\Register')->setKey($poststore)
                ->setTemplate('Dolphin_Insurance::boutique_append.phtml')
                ->toHtml();
				//echo $block;
				return $this->getResponse()->setBody($block);
		}
		if ($post == "retail"){
		$block = $resultPage->getLayout()
                ->createBlock('Magento\Customer\Block\Form\Register')->setKey($poststore)
                ->setTemplate('Dolphin_Insurance::retailer_append.phtml')
                ->toHtml();
				return $this->getResponse()->setBody($block);
		}
		
    }
}