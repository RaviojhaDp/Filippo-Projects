<?php
namespace Calderoni\Faber\Model\Config\Source;

class DocReleasedBy implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
	 
    public function toOptionArray()
    {
		return [
			['value' => 'embassy', 'label' => __('Embassy')],
			['value' => 'common', 'label' => __('Common')],
			['value' => 'consulate', 'label' => __('Consulate')],
			['value' => 'foreign_body', 'label' => __('Foreign Body')],
			['value' => 'ministry_of_foreign_affairs', 'label' => __('Ministry of Foreign Affairs')],
			['value' => 'motor_vehicles', 'label' => __('Motor Vehicles')],
			['value' => 'prefecture', 'label' => __('Prefecture')],
			['value' => 'police_headquarters', 'label' => __('Police Headquarters')]
		];
	}
	
	public function getLabel($value){
		$options = $this->toOptionArray();
		$label = "";
		foreach($options as $k=>$option){
			if($option["value"] == $value) {
				$label=$option["label"];
				break;
			}
		}
		return $label;
	}
}