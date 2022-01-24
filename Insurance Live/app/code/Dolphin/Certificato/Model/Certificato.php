<?php
namespace Dolphin\Certificato\Model;

class Certificato extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Dolphin\Certificato\Model\ResourceModel\Certificato');
    }
}
?>