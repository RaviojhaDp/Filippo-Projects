<?php
/**
 * A Magento 2 module named Dolphin/Iclients
 * Copyright (C) 2019 
 * 
 * This file included in Dolphin/Iclients is licensed under OSL 3.0
 * 
 * http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * Please see LICENSE.txt for the full text of the OSL 3.0 license
 */

namespace Dolphin\Iclients\Controller\Adminhtml\Iclients;

class Delete extends \Dolphin\Iclients\Controller\Adminhtml\Iclients
{

    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('iclients_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Dolphin\Iclients\Model\Iclients::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the Iclients.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['iclients_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a Iclients to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
