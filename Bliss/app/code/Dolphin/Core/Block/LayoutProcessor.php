<?php
namespace Dolphin\Core\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessor as CheckoutLayoutProcessor;

class LayoutProcessor {
	/**
	 * @param CheckoutLayoutProcessor $subject
	 * @param array $jsLayout
	 * @return array
	 */
	public function afterProcess(
		CheckoutLayoutProcessor $subject,
		array $jsLayout
	): array{
		$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
		['children']['shippingAddress']['children']['shipping-address-fieldset']['children']['street']['children']['0']['label'] = __('Street Address');
		return $jsLayout;
	}
}
