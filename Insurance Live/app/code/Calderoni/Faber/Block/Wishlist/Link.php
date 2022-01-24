<?php
namespace Calderoni\Elegant\Block\Wishlist;

class Link extends \Magento\Wishlist\Block\Link
{
    public function getLabel()
    {
        return __('Wishlist');
    }
}
