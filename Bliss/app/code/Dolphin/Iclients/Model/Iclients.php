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

use Magento\Framework\Api\DataObjectHelper;
use Dolphin\Iclients\Api\Data\IclientsInterface;
use Dolphin\Iclients\Api\Data\IclientsInterfaceFactory;

class Iclients extends \Magento\Framework\Model\AbstractModel
{

    protected $iclientsDataFactory;

    protected $dataObjectHelper;

    protected $_eventPrefix = 'dolphin_iclients_iclients';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param IclientsInterfaceFactory $iclientsDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \Dolphin\Iclients\Model\ResourceModel\Iclients $resource
     * @param \Dolphin\Iclients\Model\ResourceModel\Iclients\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        IclientsInterfaceFactory $iclientsDataFactory,
        DataObjectHelper $dataObjectHelper,
        \Dolphin\Iclients\Model\ResourceModel\Iclients $resource,
        \Dolphin\Iclients\Model\ResourceModel\Iclients\Collection $resourceCollection,
        array $data = []
    ) {
        $this->iclientsDataFactory = $iclientsDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve iclients model with iclients data
     * @return IclientsInterface
     */
    public function getDataModel()
    {
        $iclientsData = $this->getData();
        
        $iclientsDataObject = $this->iclientsDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $iclientsDataObject,
            $iclientsData,
            IclientsInterface::class
        );
        
        return $iclientsDataObject;
    }
}
