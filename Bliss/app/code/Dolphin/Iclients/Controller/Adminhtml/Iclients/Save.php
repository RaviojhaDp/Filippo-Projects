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

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $_helper;

    protected $dataPersistor;

    protected $_storeManager;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Dolphin\Iclients\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $storeId = $data['store_id'];
        if ($storeId == 0) {
            $storeId = 1;
        }
        $data['store_id'] = $storeId;
        $urlParam = $data['url_param'];
        $identifyCode = $data['identify_code'];
        //echo $storeId;exit;
        /* save image*/
        if (isset($data['logo'][0]['name'])) {
            $data['logo'] = $data['logo'][0]['name'];
        } else {
            $data['logo'] = '';
        }
        /* end save image*/

        if ($data) {
            $id = $this->getRequest()->getParam('iclients_id');

            $model = $this->_objectManager->create(\Dolphin\Iclients\Model\Iclients::class)->load($id);

            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Iclients no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            /* check unique Parameter Field */
            $collectionAll = $this->_objectManager->create(\Dolphin\Iclients\Model\Iclients::class)->getCollection();
            if (!empty($collectionAll)) {
                foreach ($collectionAll as $key => $checkItem) {
                    if ($checkItem->getUrlParam() == $data['url_param'] && $model->getId() != $checkItem->getId()) {
                        $this->messageManager->addErrorMessage(__('Parameter Field Value Must be unique.'));
                        return $resultRedirect->setPath('*/*/edit', ['iclients_id' => $model->getId()]);
                    }
                }
            }
            /* End check unique Parameter Field */
            //if ($model->getId()) {
            $linkvalue = $this->_storeManager->getStore($storeId)->getBaseUrl() . '?client=' . $urlParam . '&acc=' . $identifyCode;
            $iframevalue = '<iframe src="' . $this->_storeManager->getStore($storeId)->getBaseUrl() . '?client=' . $urlParam . '&acc=' . $identifyCode . '" width="100%" scrolling="no"></iframe><script type="text/javascript" src="' . $this->_storeManager->getStore($storeId)->getBaseUrl() . 'pub/media/' . 'js/iframeResizer.min.js"></script>';

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $escaper = $objectManager->create('Magento\Framework\Escaper');
            $data['link'] = $linkvalue;
            $data['ifraem'] = $iframevalue;
            //}
            $model->setData('link', $data['link']);
            $model->setData('ifraem', $data['ifraem']);
            //exit;
            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Iclients.'));
                $this->dataPersistor->clear('dolphin_iclients_iclients');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['iclients_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Iclients.'));
            }

            $this->dataPersistor->set('dolphin_iclients_iclients', $data);
            return $resultRedirect->setPath('*/*/edit', ['iclients_id' => $this->getRequest()->getParam('iclients_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
