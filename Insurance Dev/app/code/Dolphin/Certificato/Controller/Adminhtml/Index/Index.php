<?php

namespace Dolphin\Certificato\Controller\Adminhtml\Index;


class Index extends \Magento\Backend\App\Action
{
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
	}
	protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Dolphin_Certificato::index');
    }
}

?>