<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */

namespace Webkul\Pwa\Model\PushNotificationMessage\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class Status
 */
class Status implements OptionSourceInterface
{
    /**
     * @var \Webkul\Pwa\Model\PushNotificationMessage
     */
    protected $_pushNotificationMessage;

    /**
     * Constructor
     *
     * @param \Magento\Cms\Model\Page $cmsPage
     */
    public function __construct(\Webkul\Pwa\Model\PushNotificationMessage $pushNotificationMessage)
    {
        $this->_pushNotificationMessage = $pushNotificationMessage;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->_pushNotificationMessage->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
