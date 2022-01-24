<?php
namespace Calderoni\Faber\Controller\Document;

class Show extends \Magento\Framework\App\Action\Action
{
	protected $orderRepository;
	protected $faberAuthenticate;
	protected $faberHeader;
	protected $faberRow;
	protected $faberDocument;
	protected $faberHelper;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		\Calderoni\Faber\Model\Api\Authenticate $faberAuthenticate,
		\Calderoni\Faber\Model\Api\Header $faberHeader,
		\Calderoni\Faber\Model\Api\Row $faberRow,
		\Calderoni\Faber\Model\Api\Document $faberDocument,
		\Calderoni\Faber\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->faberAuthenticate = $faberAuthenticate;
		$this->orderRepository = $orderRepository;
		$this->faberHeader = $faberHeader;
		$this->faberRow = $faberRow;
		$this->faberDocument = $faberDocument;
		$this->faberHelper = $faberHelper;
	}

    public function execute()
    {
		//$order = $this->orderRepository->get($this->getRequest()->getParam("id"));
		if(isset($_GET['signid']) && isset($_GET['certificato_code'])){
		$certificato_code = $_GET['certificato_code'];
    	$header_id = $_GET['signid'];
		if($header_id){
			//getting Key
			$headerData = $this->faberHeader->searchHeader(array(["Id","eq",$header_id]));
			if($headerData["data"] != ''){
			if($headerData["status"]){
				//$key = array_search('DocumentoFirmato', array_column($headerData["data"][0]["Fields"], 'Key'));
				$headerData = json_decode(json_encode($headerData), true);
				
				$docKey = $headerData["data"][0]["Fields"][array_search('DocumentoFirmato', array_column($headerData["data"][0]["Fields"], 'Key'))]["Value"];
				$docKey = substr($docKey, strpos($docKey, "#") + 1);

				$docData = $this->faberDocument->getDocument($header_id,$docKey);
				

				if(strtoupper($docData["data"]->ErrorCode) == strtoupper(\Calderoni\Faber\Model\Api::API_ERR_CODE)){
					echo __("Error generating document. Please try again");
					exit;
				}
				$docx = base64_decode($docData["data"]->FileBase64);
				
				$resultPage = $this->resultFactory
					->create(\Magento\Framework\Controller\ResultFactory::TYPE_RAW)
					//->setHeader('Content-Type', 'application/octet-stream', true)
					//->setHeader('Content-Disposition', "attachment; filename=\"Signed_Document.docx\"", true)
					->setHeader('Content-Type', 'application/x-pdf', true)
					->setHeader('Content-Disposition', "attachment; filename=\"$certificato_code.pdf\"", true)
					->setContents($docx)
					;
					
				return $resultPage;
			}else{
				echo __("No document found");
				exit;
			}
			}
			else{
				echo __("The document could not be recovered. Try again later or contact our customer service.");
				exit;
			}
		}
		else{
			echo __("No document found");
			exit;
		}
	}
	else{
			echo __("No document found");
			exit;
		}
	}
}