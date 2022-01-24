<?php
namespace Dolphin\Certificato\Ui\Component\Listing\Column;

class Activegroup implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 3, 'label' => __('Retailer')], ['value' => 4, 'label' => __('Botique')],['value' => 5, 'label' => __('Client')]];
    }
}