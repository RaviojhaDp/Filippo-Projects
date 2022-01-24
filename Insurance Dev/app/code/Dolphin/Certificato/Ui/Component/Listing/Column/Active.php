<?php
namespace Dolphin\Certificato\Ui\Component\Listing\Column;

class Active implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 1, 'label' => __('Activate')], ['value' => 0, 'label' => __('Expire')], ['value' => 2, 'label' => __('Renewal')], ['value' => 3, 'label' => __('Pending')], ['value' => 4, 'label' => __('Deactivate')]];
    }
}