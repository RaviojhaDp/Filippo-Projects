<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Dolphin\Iclients\Model\Config\Source;

/**
 * @api
 * @since 100.0.2
 */
class Logoalign implements \Magento\Framework\Option\ArrayInterface {
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray() {
		return [
			['value' => 'center', 'label' => __('Center')],
			['value' => 'left', 'label' => __('Left')],
			['value' => 'right', 'label' => __('Right')],
		];
	}

	/**
	 * Get options in "key-value" format
	 *
	 * @return array
	 */
	public function toArray() {
		return [
			'center' => __('Center'),
			'left' => __('Left'),
			'right' => __('Right'),
		];
	}
}
