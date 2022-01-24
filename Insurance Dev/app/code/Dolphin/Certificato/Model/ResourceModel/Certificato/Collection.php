<?php

namespace Dolphin\Certificato\Model\ResourceModel\Certificato;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dolphin\Certificato\Model\Certificato', 'Dolphin\Certificato\Model\ResourceModel\Certificato');
        $this->_map['fields']['page_id'] = 'main_table.page_id';
    }
    public function filterOrder()
    {
        exit("ayasaaa");
    }

}
?>