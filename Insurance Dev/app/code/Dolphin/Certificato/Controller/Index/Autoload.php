<?php

/**
 * Grid Admin Cagegory Map Record Save Controller.
 * @category  Webkul
 * @package   Webkul_Grid
 * @author    Webkul
 * @copyright Copyright (c) 2010-2017 Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Dolphin\Certificato\Controller\Index;

use Magento\Framework\Controller\Result\JsonFactory;

class Autoload extends \Magento\Framework\App\Action\Action {

    protected $_customer;
    protected $_storemanager;
    protected $resultJsonFactory;

    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\ObjectManagerInterface $objectmanager,
    \Magento\Customer\Model\CustomerFactory $customer,
    JsonFactory $resultJsonFactory,
    \Magento\Store\Model\StoreManagerInterface $storemanager
    ) {
        parent::__construct($context);
        $this->_objectManager = $objectmanager;
        $this->_customer = $customer;
        $this->_storemanager = $storemanager;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    public function execute() {
        $resultJson = $this->resultJsonFactory->create();
        $datao = $this->getRequest()->getPostValue();
        $model = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->load($datao['param']);
        $data = $model->getData();
        $email = $data['email'];
        $websiteID = $this->_storemanager->getStore()->getWebsiteId();
        $customer = $this->_customer->create()->setWebsiteId($websiteID)->loadByEmail($email);
        $customerId = $customer->getId();
        $groupId = $customer->getGroupId();
        $data['customer_c_id'] = $customerId;
        $data['customer_c_group_id'] = $groupId;
        $data['city'] = ucwords(strtolower(($model->getData("city"))));
        return $resultJson->setData($data);
    }

}
