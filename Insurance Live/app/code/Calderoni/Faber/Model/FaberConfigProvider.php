<?php
namespace Calderoni\Faber\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\UrlInterface;
use Calderoni\Faber\Helper\Data as FaberHelper;
use Calderoni\Faber\Model\Config\Source\DocType as DocType;
use Calderoni\Faber\Model\Config\Source\DocReleasedBy as DocReleasedBy;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class FaberConfigProvider implements ConfigProviderInterface
{

	/**
	 * @var CheckoutSession
	 */
	private $checkoutSession;
	
	protected $urlBuilder;

	protected $faberHelper;
	protected $docType;
	protected $docReleasedBy;

	public function __construct(
		CheckoutSession $checkoutSession,
		UrlInterface $urlBuilder,
		FaberHelper $faberHelper,
		DocType $docType,
		DocReleasedBy $docReleasedBy
	)
	{
		$this->checkoutSession           = $checkoutSession;
		$this->urlBuilder                 = $urlBuilder;
		$this->faberHelper                 = $faberHelper;
		$this->docType                 = $docType;
		$this->docReleasedBy                 = $docReleasedBy;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getConfig()
	{
		$output = [
			'faberConfig'             => $this->getFaberConfig(),
			'documentsConfig'             => $this->getDocumentsConfig(),
		];

		return $output;
	}

	/**
	 * @return array
	 */
	private function getFaberConfig()
	{
		return [
			"urls"=> array(
				"createDocument" => $this->urlBuilder->getUrl("faber/document/create"),
				"saveDocument" => $this->urlBuilder->getUrl("faber/document/save")
				//"getDocument" => $this->getUrl("faber/document/get")
			),
			"faberDocInfo"=>$this->faberHelper->getConfig("faber/settings/sign_doc_info"),
			"saveDocTooltip"=>$this->faberHelper->getConfig("faber/settings/sign_doc_tooltip"),
			"placeOrderToc"=>$this->faberHelper->getCmsBlockContent("checkout-agreement"),
			"additionalInfoTooltip"=>$this->faberHelper->getConfig("faber/settings/additional_info_tooltip")
		];
	}
	
	/**
	 * @return array
	 */
	private function getDocumentsConfig()
	{
		return [
			"docType"=> $this->docType->toOptionArray(),
			"docReleasedBy"=> $this->docReleasedBy->toOptionArray(),
			"docReleaseDateFormat" => "dd/mm/yy"
		];
	}
}
