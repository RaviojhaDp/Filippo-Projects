<?php
/**
 * Created by wilson.sun330@gmail.com
 * Date: 13/05/2015
 * Time: 5:02 PM
 */
namespace Dolphin\Insurance\Model;


use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Framework\App\RequestInterface;


class ExtendModel extends \Magento\Customer\Model\CustomerExtractor
{
	 /**
     * @var \Magento\Customer\Model\Metadata\FormFactory
     */
    protected $formFactory;

    /**
     * @var \Magento\Customer\Api\Data\CustomerInterfaceFactory
     */
    protected $customerFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var GroupManagementInterface
     */
    protected $customerGroupManagement;

    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @param Metadata\FormFactory $formFactory
     * @param \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param GroupManagementInterface $customerGroupManagement
     * @param \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
     */
    public function __construct(
        \Magento\Customer\Model\Metadata\FormFactory $formFactory,
        \Magento\Customer\Api\Data\CustomerInterfaceFactory $customerFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        GroupManagementInterface $customerGroupManagement,
		 \Magento\Framework\App\Request\Http $requests,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper
    ) {
        $this->formFactory = $formFactory;
        $this->customerFactory = $customerFactory;
        $this->storeManager = $storeManager;
        $this->customerGroupManagement = $customerGroupManagement;
        $this->dataObjectHelper = $dataObjectHelper;
		$this->requests = $requests;
    }

    /**
     * @param string $formCode
     * @param RequestInterface $request
     * @param array $attributeValues
     * @return CustomerInterface
     */
	 public function extract(
        $formCode,
        RequestInterface $request,
        array $attributeValues = []
    ) {

        $customerForm = $this->formFactory->create(
            CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
            $formCode,
            $attributeValues
        );
		$group_id = $this->requests->getPost('group_id');		
        $customerData = $customerForm->extractData($request);
		//$customerData = array("firstname"=> "test2","lastname"=> "tesr2","email"=> "rv224391@mailinator.com","brand"=> "3");
        $customerData = $customerForm->compactData($customerData);
		
        $allowedAttributes = $customerForm->getAllowedAttributes();
        //$isGroupIdEmpty = isset($allowedAttributes['group_id']);
		$isGroupIdEmpty = isset($group_id);

        $customerDataObject = $this->customerFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $customerDataObject,
            $customerData,
            \Magento\Customer\Api\Data\CustomerInterface::class
        );
        $store = $this->storeManager->getStore();
		$customerDataObject->setGroupId($isGroupIdEmpty);
        /*if ($isGroupIdEmpty) {
            $customerDataObject->setGroupId(
                $this->customerGroupManagement->getDefaultGroup($store->getId())->getId()
            );
        }*/

        $customerDataObject->setWebsiteId($store->getWebsiteId());
        $customerDataObject->setStoreId($store->getId());

        return $customerDataObject;
    }
} 