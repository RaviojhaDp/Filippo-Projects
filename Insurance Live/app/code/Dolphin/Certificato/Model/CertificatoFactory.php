<?php

namespace Dolphin\Certificato\Model;

class CertificatoFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager)
    {
        $this->_objectManager = $objectManager;
    }

    /**
     * Create new country model
     *
     * @param array $arguments
     * @return \Magento\Directory\Model\Country
     */
    public function create(array $arguments = [])
    {
        return $this->_objectManager->create('Dolphin\Certificato\Model\Certificato', $arguments, false);
    }
    
    public function loadByCertificatoCode($certificato_code) {
        $collection = $this->_objectManager->create('Dolphin\Certificato\Model\Certificato')->getCollection();
        $collection->addFieldToFilter('certificato_code', $certificato_code);
        return $collection->getFirstItem();
    }
}