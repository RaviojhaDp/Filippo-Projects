<?php namespace Dolphin\Roccaapi\Model\Config\Source;

class ListMode implements \Magento\Framework\Option\ArrayInterface
{
 public function toOptionArray()
 {
  return [
    ['value' => 'damiani', 'label' => __('Damiani')],
    ['value' => 'bliss', 'label' => __('Bliss')],
    ['value' => 'salvini', 'label' => __('Salvini')],
    ['value' => 'rocca', 'label' => __('Rocca')]
  ];
 }
}