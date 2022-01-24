<?php
namespace Calderoni\Faber\Model\Api;

class Authenticate extends \Calderoni\Faber\Model\Api
{
	const ENTITY_NAME = "Authenticate";

    public function __construct(
        \Magento\Framework\Model\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Config\Model\Config\Factory $configFactory,
		\Calderoni\Faber\Helper\Data $faberHelper
    ) {
        parent::__construct($context,$registry,$objectManager,$configFactory,$faberHelper);
    }
	
	public function authenticate($params=array())
    {
		$this->generateAccessToken();
    }
	
}