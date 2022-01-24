<?php

namespace Dolphin\Insurance\Block\Index;

use Magento\Customer\Block\Account\SortLinkInterface;
class Link extends \Magento\Framework\View\Element\Html\Link implements SortLinkInterface
{
    /**
     * @var \Magento\Customer\Model\Url
     */
    

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Customer\Model\Url $customerUrl
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        
        array $data = []
    ) {
        
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    

    /**
     * {@inheritdoc}
     * @since 101.0.0
     */
    public function getSortOrder()
    {
        return $this->getData(self::SORT_ORDER);
    }

}