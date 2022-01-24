<?php
namespace Dolphin\Claim\Model\ResourceModel;

class Claim extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('claim', 'claim_id');
    }
}
?>