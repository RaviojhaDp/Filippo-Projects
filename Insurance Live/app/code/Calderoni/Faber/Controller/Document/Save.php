<?php
namespace Calderoni\Faber\Controller\Document;

class Save extends \Magento\Framework\App\Action\Action
{
	protected $resultJsonFactory;
	protected $faberAuthenticate;
	protected $faberHeader;
	protected $faberRow;
	protected $faberDocument;
	protected $faberHelper;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Calderoni\Faber\Model\Api\Authenticate $faberAuthenticate,
		\Calderoni\Faber\Model\Api\Header $faberHeader,
		\Calderoni\Faber\Model\Api\Row $faberRow,
		\Calderoni\Faber\Model\Api\Document $faberDocument,
		\Calderoni\Faber\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->resultJsonFactory = $resultJsonFactory;
		$this->faberAuthenticate = $faberAuthenticate;
		$this->faberHeader = $faberHeader;
		$this->faberRow = $faberRow;
		$this->faberDocument = $faberDocument;
		$this->faberHelper = $faberHelper;
	}

    public function execute()
    {
		$post = $this->getRequest()->getPost();
	
		$docData = $this->faberDocument->saveDocument($post["sessiontoken"]);

		if(strtoupper($docData["data"]->code) == strtoupper(\Calderoni\Faber\Model\Api::API_ERR_CODE)){
			$response = array("status"=>false, "msg"=>__("Error saving document. Please try again."));
		}elseif(empty($docData["data"]->signedid)){
			$response = array("status"=>false, "msg"=>__("Please sign document before save"));
		}else{
			$response = array(
				"status"=>true,
				"signedid"=>$docData["data"]->signedid[0]
			);
		}
		$result = $this->resultJsonFactory->create();
		return $result->setData($response);
	}

	public function removePrices($rows){
		foreach($rows as $k=>$row){
			$rows[$k]["price"] = false;
			//optional. For testing only
			$rows[$k]["view"] = $rows[$k]["view"].$row["id"];
		}
		return $rows;
	}
	
}