<?php
namespace Dolphin\Certificato\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Dolphin\Certificato\Model\Certificato as Certificato;

class NewAction extends \Magento\Backend\App\Action
{
    /**
     * Edit A Contact Page
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    { 
        $this->_view->loadLayout();
        $this->_view->renderLayout();

      /*  $contactDatas = $this->getRequest()->getParam('');
		echo "<pre>";
			print($contactDatas);
			die;
        if(is_array($contactDatas)) {
            $Certificato = $this->_objectManager->create(Certificato::class);
            
			$Certificato->setData($contactDatas)->save();
            $resultRedirect = $this->resultRedirectFactory->create();
            
        }*/
    }
}