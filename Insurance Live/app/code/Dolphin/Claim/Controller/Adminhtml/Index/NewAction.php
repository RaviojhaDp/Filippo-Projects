<?php
namespace Dolphin\Claim\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Dolphin\Claim\Model\Claim as Claim;

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

     
    }
}