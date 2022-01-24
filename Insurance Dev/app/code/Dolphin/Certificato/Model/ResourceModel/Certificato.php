<?php
namespace Dolphin\Certificato\Model\ResourceModel;

class Certificato extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('certificato', 'certificato_id');
    }
}
?>