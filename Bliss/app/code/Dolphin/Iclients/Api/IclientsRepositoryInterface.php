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

namespace Dolphin\Iclients\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface IclientsRepositoryInterface
{

    /**
     * Save iclients
     * @param \Dolphin\Iclients\Api\Data\IclientsInterface $iclients
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Dolphin\Iclients\Api\Data\IclientsInterface $iclients
    );

    /**
     * Retrieve iclients
     * @param string $iclientsId
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($iclientsId);

    /**
     * Retrieve iclients matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Dolphin\Iclients\Api\Data\IclientsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete iclients
     * @param \Dolphin\Iclients\Api\Data\IclientsInterface $iclients
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Dolphin\Iclients\Api\Data\IclientsInterface $iclients
    );

    /**
     * Delete iclients by ID
     * @param string $iclientsId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($iclientsId);
}
