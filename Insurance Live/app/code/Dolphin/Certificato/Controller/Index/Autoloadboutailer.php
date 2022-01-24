<?php
 
namespace Dolphin\Certificato\Controller\Index;
 
 use Magento\Framework\Controller\Result\JsonFactory;

class Autoloadboutailer extends \Magento\Framework\App\Action\Action
{
	protected $resultJsonFactory;
   
    public function __construct(
	\Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    JsonFactory $resultJsonFactory,
    \Magento\Customer\Model\AddressFactory $addressFactory
	) {
		 parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->addressFactory = $addressFactory;
		$this->resultJsonFactory = $resultJsonFactory;
	}
	
    public function execute()
    {
    	$resultJson = $this->resultJsonFactory->create();
    	$customer_id = $this->getRequest()->getPostValue();
    	$address = $this->addressFactory->create()
        ->getCollection()
        ->addFieldToFilter('parent_id', $customer_id);
        if(count($address->getData()) > 0){
        	return $resultJson->setData($address->getData());
        }
		
	}
}