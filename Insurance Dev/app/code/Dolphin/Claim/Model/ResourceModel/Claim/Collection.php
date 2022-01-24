<?php

namespace Dolphin\Claim\Model\ResourceModel\Claim;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dolphin\Claim\Model\Claim', 'Dolphin\Claim\Model\ResourceModel\Claim');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }

}
?>