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

namespace Dolphin\Iclients\Api\Data;

interface IclientsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get iclients list.
     * @return \Dolphin\Iclients\Api\Data\IclientsInterface[]
     */
    public function getItems();

    /**
     * Set name list.
     * @param \Dolphin\Iclients\Api\Data\IclientsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
