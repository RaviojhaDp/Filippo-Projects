<?php
namespace Calderoni\Faber\Controller\Document;

class Create extends \Magento\Framework\App\Action\Action
{
	protected $resultJsonFactory;
	protected $productRepository;
	protected $cart;
	protected $checkoutSession;
	protected $regionFactory;
	protected $faberAuthenticate;
	protected $faberHeader;
	protected $faberRow;
	protected $faberDocument;
	protected $faberHelper;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
		\Magento\Checkout\Model\Cart $cart,
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Directory\Model\RegionFactory $regionFactory,
		\Calderoni\Faber\Model\Api\Authenticate $faberAuthenticate,
		\Calderoni\Faber\Model\Api\Header $faberHeader,
		\Calderoni\Faber\Model\Api\Row $faberRow,
		\Calderoni\Faber\Model\Api\Document $faberDocument,
		\Calderoni\Faber\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->resultJsonFactory = $resultJsonFactory;
		$this->productRepository = $productRepository;
		$this->cart = $cart;
		$this->checkoutSession = $checkoutSession;
		$this->regionFactory = $regionFactory;
		$this->faberAuthenticate = $faberAuthenticate;
		$this->faberHeader = $faberHeader;
		$this->faberRow = $faberRow;
		$this->faberDocument = $faberDocument;
		$this->faberHelper = $faberHelper;
	}

    public function execute()
    {
		$post = $this->getRequest()->getPost();
		//print_r($post);
		//print_r($post["productIds"]);
		//exit;
		
		$this->faberAuthenticate->generateAccessToken();
		
		$quote = $this->getCheckoutSession()->getQuote();
		
/*
[namea]: Ftest
[surna]: Ltest
[addra]: Via F. Turati 31
[capra]: 20831
[citra]: Seregno
[prora]: MB
[nazra]: IT
[telra]: 3483065047
[emara]: fltest@gmail.com
[cfisa]: PZZLSN73B27F205T
[tdida]: Identity card
[ndida]: 234234234
[rilda]: Embassy
[dtrda]: 03/09/2018
*/
		$headerFields = $post["headerData"]["hf"];
		$rowFields = $post["headerData"]["rf"];

		if($rowFields["insurance"] == "Yes" && $rowFields["custody"] == "Yes") $headerFields["Templates_lkp"] = "9";
		elseif($rowFields["insurance"] == "Yes" && $rowFields["custody"] == "No") $headerFields["Templates_lkp"] = "8";
		elseif($rowFields["insurance"] == "No" && $rowFields["custody"] == "Yes") $headerFields["Templates_lkp"] = "7";
		elseif($rowFields["insurance"] == "No" && $rowFields["custody"] == "No") $headerFields["Templates_lkp"] = "6";
		
		//checking if Region is CODE or ID
		if(trim($headerFields["prora"]) != "" && is_numeric($headerFields["prora"])){
			$region = $this->regionFactory->create()->load($headerFields["prora"]);
			$headerFields["prora"] = $region->getCode();
		}
		//echo "<pre>";print_r($headerFields);exit;
		
		$header = $this->faberHeader->addHeader( $headerFields );
		$headerIdRecord = $header["data"]->idRecord;
		
		//echo "headerIdRecord: ".$headerIdRecord;exit;
		
		$items = $quote->getAllItems();
		
		//print_r($items);
		foreach($items as $item) {
			$rf["HeaderId"] = (int)$headerIdRecord;
			$rf["matnr"] = $item->getSku();
			//$rf["Quantita"] = $item->getQty();
			$rf["prezz"] = $item->getPrice();

			$product = $this->loadBySku($item->getSku());
			$rf["certi"] = $product->getData("diamond_cert_number");
			$rf["carat"] = $product->getData("diamond_carat");
			$rf["color"] = $product->getResource()->getAttribute('diamond_color')->getFrontend()->getValue($product);
			$rf["tagli"] = $product->getResource()->getAttribute('diamond_cut')->getFrontend()->getValue($product);
			$rf["fluor"] = $product->getResource()->getAttribute('diamond_fluorescence')->getFrontend()->getValue($product);
			$rf["purit"] = $product->getResource()->getAttribute('diamond_clarity')->getFrontend()->getValue($product);
			
			//adding EmptyFields
			//$rf["fpoli"] = ($rowFields["insurance"]=="Yes")?'X':'""';
			$rf["fpoli"] = ($rowFields["insurance"]=="Yes")?'EN10000001':'""';
			$rf["fdepo"] = ($rowFields["custody"]=="Yes")?'X':'""';
			
			//print_r($rf);exit;
			
			$r = $this->faberRow->addRow( $rf );
			//print_r($r);exit;
			$rowIds[] = $r["data"]->idRecord;
		}

		//print_r($headerFields);
		
		$this->faberDocument->composeDocument($headerIdRecord);
		$docData = $this->faberDocument->createDocument([$headerIdRecord]);
		
		if(strtoupper($docData["data"]->code) == strtoupper(\Calderoni\Faber\Model\Api::API_ERR_CODE)){
			$response = array("status"=>false, "msg"=>__("Error generating document. Please try again"));
		}else{
			//print_r($docData["data"]);
			$response = array(
				"status"=>true,
				"headerId"=>$headerIdRecord,
				"rowIds"=>$rowIds,
				"sessiontoken"=>$docData["data"]->sessiontoken,
				"web2signurl"=>$docData["data"]->web2signurl
			);
		}
		$result = $this->resultJsonFactory->create();
		return $result->setData($response);
	}
	
	public function getCart()
    {        
        return $this->cart;
    }
    
    public function getCheckoutSession()
    {
        return $this->checkoutSession;
    }

	public function loadBySku($sku)
	{
		return $this->productRepository->get($sku);
	}

	
}