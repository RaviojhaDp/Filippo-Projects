<?php
namespace Dolphin\Claim\Model;

class Claim extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dolphin\Claim\Model\ResourceModel\Claim');
    }
}
?>