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

namespace Dolphin\Iclients\Model;

use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Dolphin\Iclients\Api\Data\IclientsInterfaceFactory;
use Dolphin\Iclients\Api\IclientsRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Dolphin\Iclients\Api\Data\IclientsSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Dolphin\Iclients\Model\ResourceModel\Iclients\CollectionFactory as IclientsCollectionFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Dolphin\Iclients\Model\ResourceModel\Iclients as ResourceIclients;

class IclientsRepository implements IclientsRepositoryInterface
{

    protected $dataObjectProcessor;

    protected $extensibleDataObjectConverter;
    private $collectionProcessor;

    protected $iclientsCollectionFactory;

    protected $iclientsFactory;

    protected $searchResultsFactory;

    private $storeManager;

    protected $resource;

    protected $dataObjectHelper;

    protected $extensionAttributesJoinProcessor;

    protected $dataIclientsFactory;


    /**
     * @param ResourceIclients $resource
     * @param IclientsFactory $iclientsFactory
     * @param IclientsInterfaceFactory $dataIclientsFactory
     * @param IclientsCollectionFactory $iclientsCollectionFactory
     * @param IclientsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceIclients $resource,
        IclientsFactory $iclientsFactory,
        IclientsInterfaceFactory $dataIclientsFactory,
        IclientsCollectionFactory $iclientsCollectionFactory,
        IclientsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->iclientsFactory = $iclientsFactory;
        $this->iclientsCollectionFactory = $iclientsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataIclientsFactory = $dataIclientsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Dolphin\Iclients\Api\Data\IclientsInterface $iclients
    ) {
        /* if (empty($iclients->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $iclients->setStoreId($storeId);
        } */
        
        $iclientsData = $this->extensibleDataObjectConverter->toNestedArray(
            $iclients,
            [],
            \Dolphin\Iclients\Api\Data\IclientsInterface::class
        );
        
        $iclientsModel = $this->iclientsFactory->create()->setData($iclientsData);
        
        try {
            $this->resource->save($iclientsModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the iclients: %1',
                $exception->getMessage()
            ));
        }
        return $iclientsModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getById($iclientsId)
    {
        $iclients = $this->iclientsFactory->create();
        $this->resource->load($iclients, $iclientsId);
        if (!$iclients->getId()) {
            throw new NoSuchEntityException(__('iclients with id "%1" does not exist.', $iclientsId));
        }
        return $iclients->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->iclientsCollectionFactory->create();
        
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \Dolphin\Iclients\Api\Data\IclientsInterface::class
        );
        
        $this->collectionProcessor->process($criteria, $collection);
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }
        
        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Dolphin\Iclients\Api\Data\IclientsInterface $iclients
    ) {
        try {
            $iclientsModel = $this->iclientsFactory->create();
            $this->resource->load($iclientsModel, $iclients->getIclientsId());
            $this->resource->delete($iclientsModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the iclients: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($iclientsId)
    {
        return $this->delete($this->getById($iclientsId));
    }
}
