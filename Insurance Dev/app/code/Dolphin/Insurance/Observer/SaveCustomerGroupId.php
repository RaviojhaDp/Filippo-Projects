<?php

namespace Dolphin\Insurance\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Registry;

Class SaveCustomerGroupId implements ObserverInterface {
    public $_customerRepositoryInterface;
    protected $coreRegistry;
    public $_messageManager;
    public function __construct(
            CustomerRepositoryInterface $customerRepositoryInterface,
            Registry $registry,
            ManagerInterface $messageManager
    ) {
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_messageManager = $messageManager;
        $this->coreRegistry = $registry;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
       $accountController = $observer->getAccountController();
       $request = $accountController->getRequest();
       $group_id = $request->getParam('group_id');

       try {
           $customerId = $observer->getCustomer()->getId();
           $customer = $this->_customerRepositoryInterface->getById($customerId);
           $customer->setGroupId($group_id);
           $this->_customerRepositoryInterface->save($customer);
           $this->coreRegistry->register('is_new_account', true);

       } catch (Exception $e){
           $this->_messageManager->addErrorMessage(__('Something went wrong! Please try again.'));
       }
    }
}