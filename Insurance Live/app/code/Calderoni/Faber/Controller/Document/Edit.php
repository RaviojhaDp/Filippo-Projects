<?php
namespace Calderoni\Faber\Controller\Document;

class Edit extends \Magento\Framework\App\Action\Action
{
	protected $resultJsonFactory;
	protected $productRepository;
	protected $cart;
	protected $checkoutSession;
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
		$this->faberAuthenticate = $faberAuthenticate;
		$this->faberHeader = $faberHeader;
		$this->faberRow = $faberRow;
		$this->faberDocument = $faberDocument;
		$this->faberHelper = $faberHelper;
	}

    public function execute()
    {
		$this->faberAuthenticate->generateAccessToken();
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
14
[Templates_lkp]: 4
*/
		//Delivery if Store Pickup selected
		$headerFields["negoz"] = "Milano Duomo";
		$headerFields["addrn"] = "Piazza Duomo Milano";
		$headerFields["telen"] = "02788899";
		
		//Beneficiary Address
		$headerFields["surnb"] = "Gregoria";
		$headerFields["nameb"] = "Anna";
		$headerFields["plobb"] = "Alassio";
		$headerFields["probb"] = "SV";
		$headerFields["nazbb"] = "IT";
		$headerFields["addrb"] = "Via della vigna 507a";
		$headerFields["caprb"] = "caprb";
		$headerFields["citrb"] = "Genova";
		$headerFields["prorb"] = "Ge";
		$headerFields["nazrb"] = "IT";
		$headerFields["emabb"] = "prova@prova";
		$headerFields["telbb"] = "39785452";
		
		//Consent to the processing of data
		$headerFields["fmktg"] = "X";
		$headerFields["fprof"] = "X";
		$headerFields["fterz"] = "X";
		$headerFields["dtaac"] = "01/04/2018"; // Order date
		
		//SAP data
		$headerFields["kunnr"] = "1"; //customer id
		$headerFields["vbeln"] = "856999"; //SAP ord no
		$headerFields["audat"] = "01/04/2018"; //Order date
		$headerFields["waerk"] = "ITL"; //currency
		$headerFields["emad"] = "fltest@gmail.com"; //Email Submitting Documents
		
		$header = $this->faberHeader->editHeader(141, $headerFields);
		
		//$headerIdRecord = $header["data"]->idRecord;
		
		if($header["data"]->error){
			//use $header["data"]->messagebody to show error message
			$response = array("status"=>false, "msg"=>__("Error editing document. Please try again"));
		}else{
			$response = array(
				"status"=>true,
				"headerId"=>$headerIdRecord
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