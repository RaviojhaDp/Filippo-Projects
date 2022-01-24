<?php
namespace Calderoni\Faber\Model\Config\Source;

class DocType implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * {@inheritdoc}
     */
	 
    public function toOptionArray()
    {
		return [
			['value' => 'identity_card', 'label' => __('Identity card')],
			['value' => 'passport', 'label' => __('Passport')],
			['value' => 'drivers_license', 'label' => __('Driver\'s license')]
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

