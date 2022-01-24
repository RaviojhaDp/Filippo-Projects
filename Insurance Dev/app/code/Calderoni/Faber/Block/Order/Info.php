<?php

namespace Calderoni\Faber\Block\Order;

use Magento\Sales\Model\Order\Address;
use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\Registry;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order\Address\Renderer as AddressRenderer;

use Calderoni\Faber\Model\Config\Source\DocType as DocType;
use Calderoni\Faber\Model\Config\Source\DocReleasedBy as DocReleasedBy;

class Info extends \Magento\Sales\Block\Order\Info
{
    protected $docType;
    protected $docReleasedBy;
	
    public function __construct(
        TemplateContext $context,
        Registry $registry,
        PaymentHelper $paymentHelper,
        AddressRenderer $addressRenderer,
		DocType $docType,
		DocReleasedBy $docReleasedBy,
        array $data = []
    ) {
        $this->docType = $docType;
        $this->docReleasedBy = $docReleasedBy;
        parent::__construct($context, $registry, $paymentHelper, $addressRenderer, $data);
    }

    /**
     * @return string
     */
    public function getDocType()
    {
		return $this->docType->getLabel($this->getOrder()->getData("doc_type"));
    }
    public function getDocNumber()
    {
		return $this->getOrder()->getData("doc_number");
    }
    public function getDocReleasedBy()
    {
		return $this->docReleasedBy->getLabel($this->getOrder()->getData("doc_released_by"));
    }
    public function getDocReleaseDate()
    {
		return $this->getOrder()->getData("doc_release_date");
    }
	
	
}
