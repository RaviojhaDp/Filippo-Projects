<?php
namespace Calderoni\Faber\Controller\Document;

class Showclaim extends \Magento\Framework\App\Action\Action
{
	protected $orderRepository;
	protected $faberAuthenticate;
	protected $faberHeader;
	protected $fileFactory;
	protected $faberRow;
	protected $faberDocument;
	protected $faberHelper;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		\Calderoni\Faber\Model\Api\Claim\Authenticate $faberAuthenticate,
		\Calderoni\Faber\Model\Api\Claim\Header $faberHeader,
		\Magento\Framework\App\Response\Http\FileFactory $fileFactory,
		\Calderoni\Faber\Model\Api\Claim\Row $faberRow,
		\Calderoni\Faber\Model\Api\Claim\Document $faberDocument,
		\Calderoni\Faber\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->faberAuthenticate = $faberAuthenticate;
		$this->orderRepository = $orderRepository;
		$this->faberHeader = $faberHeader;
		$this->faberRow = $faberRow;
		 $this->fileFactory = $fileFactory;
		$this->faberDocument = $faberDocument;
		$this->faberHelper = $faberHelper;
	}

    public function execute()
    {
    	 $om = \Magento\Framework\App\ObjectManager::getInstance();
    	 $filesystem = $om->get('Magento\Framework\Filesystem');
   		 $directoryList = $om->get('Magento\Framework\App\Filesystem\DirectoryList');
 

    	 $post = $this->getRequest()->getPostValue();
		//$order = $this->orderRepository->get($this->getRequest()->getParam("id"));
    	$header_id = $post['header_id'];
    	$faber_post = json_decode($post['faber_post'],true);
    	//$header_id = '340';
		if($header_id){
			//getting Key
			$headerData = $this->faberHeader->searchHeader(array(["Id","eq",$header_id]));
			
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
					->setHeader('Content-Type', 'application/pdf', true)
					->setHeader('Content-Disposition', "attachment; filename=\"".$faber_post['certificato_code'].".pdf\"", true)
					->setContents($docx)
					;

		/*$this->fileFactory->create(
          "Signed_Document.pdf",
           $docx,
           \Magento\Framework\App\Filesystem\DirectoryList::MEDIA, // this pdf will be saved in var directory with the name example.pdf
           'application/pdf'
        );*/
        $media = $filesystem->getDirectoryWrite($directoryList::MEDIA);
         $path = "Signed_Document/Claim/$header_id/".$faber_post['certificato_code'].".pdf";
        
    	$contents = $docx;
            $media->writeFile($path,$contents);
					
				//return $resultPage;
			}else{
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