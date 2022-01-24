<?php 
namespace Dolphin\Certificato\Controller\Customer;  
class Index extends \Magento\Framework\App\Action\Action { 

protected $messageManager;

public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\View\Result\PageFactory $pageFactory,
    \Dolphin\Certificato\Model\CertificatoFactory $certificatoModelFactory,
	\Magento\Framework\Message\ManagerInterface $messageManager
) {
    $this->pageFactory = $pageFactory;
    $this->certificatoModelFactory = $certificatoModelFactory;
	  $this->messageManager = $messageManager;
    return parent::__construct($context);
}
 public function execute() { 
	 if(@$_GET['customer_id']){
	   $collection = $this->certificatoModelFactory->create();
	   $collection->load($_GET['customer_id'])->delete();
	   if($collection){
	   $this->messageManager->addSuccess(__("Customer Certificato record has been deleted succesfully."));
	   }
	 }
	$this->_view->loadLayout(); 
    $this->_view->renderLayout(); 	

  } 
  
} 
?>   