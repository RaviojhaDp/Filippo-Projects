<?php

namespace Calderoni\Faber\Block\Order\View;

/**
 * Class InsuranceCustody
 * @package Calderoni\Faber\Block\Order\View
 */
class InsuranceCustody extends \Magento\Framework\View\Element\Template
{
    /**
     * @type \Magento\Framework\Registry|null
     */
    protected $_coreRegistry = null;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    )
    {
        $this->_coreRegistry = $registry;

        parent::__construct($context, $data);
    }

    public function getInsurance()
    {
        if ($order = $this->getOrder()) {
            return $order->getInsurance();
        }

        return '';
    }
    public function getCustody()
    {
        if ($order = $this->getOrder()) {
            return $order->getCustody();
        }

        return '';
    }

    /**
     * Get current order
     *
     * @return mixed
     */
    public function getOrder()
    {
        return $this->_coreRegistry->registry('current_order');
    }
}
