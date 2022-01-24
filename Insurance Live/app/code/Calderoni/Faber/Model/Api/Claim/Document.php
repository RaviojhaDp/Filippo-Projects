<?php
namespace Calderoni\Faber\Model\Api\Claim;

class Document extends \Calderoni\Faber\Model\Api
{
	const ENTITY_NAME = "Document";
	const API_PARAM_DATA_OBJECT = "WAM_V_SINISTRI_ORD";

	const API_URL_TYPE_COMPOSE = "wamappservice/api/document/documentcomposition";
	const API_URL_TYPE_CREATE = "wamappservice/api/sign/createweb2signsession";
	const API_URL_TYPE_SAVE = "wamappservice/api/sign/SaveWeb2SignSession";
	const API_URL_TYPE_CLOSE = "wamappservice/api/sign/CloseWeb2SignSession";
	const API_URL_TYPE_GET = "wamappservice/api/document";

	const DOCUMENT_DEFAULT_SIGN_TYPE = 1;

    public function __construct(
        \Magento\Framework\Model\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Config\Model\Config\Factory $configFactory,
		\Calderoni\Faber\Helper\Data $faberHelper
    ) {
        parent::__construct($context,$registry,$objectManager,$configFactory,$faberHelper);
    }
	
	public function getPostFields(){
		$params = array();

		$params["ServiceName"] = $this->getServiceName();
		$params["TokenValue"] = $this->getOauthToken();
		$params["DataObject"] = self::API_PARAM_DATA_OBJECT;

		return $params;
	}
	
	public function prepareFields($headerId){
		return $headerId;
	}
	
	public function composeDocument($headerId)
    {
		$postFields = $this->getPostFields();
		$postFields["IdRecord"] = $this->prepareFields($headerId);
		$this->setApiUrl(self::API_URL_TYPE_COMPOSE);


		$response = $this->postToFaber($postFields);
		
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		/*echo "<pre>";
		print_r($postFields); 
		print_r($response);
		die;*/
		$returnData["status"] = true;
		$returnData["data"] = $response;
		
		return $returnData;
		//{"ErrorCode": "OK","ErrorMessage": "Elaborazione effettuata correttamente"}
    }
	
	public function createDocument($headerIds)
    {
		$postFields = $this->getPostFields();
		$postFields["DefaultSignType"] = self::DOCUMENT_DEFAULT_SIGN_TYPE;
		$postFields["IdRecords"] = $this->prepareFields($headerIds);
		$this->setApiUrl(self::API_URL_TYPE_CREATE);

		$response = $this->postToFaber($postFields);
		
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;
		
		//{"code": "OK","result": "","sessiontoken": "1cf6e5d641a14c55be466cb28602c9b6","web2signurl": "https://w4s.andxor.it/beta/?token=wam-1cf6e5d641a14c55be466cb28602c9b6"}
		return $returnData;
    }
	
	public function saveDocument($sessionToken, $closeSessionAfterSave=true)
    {
		$postFields = $this->getPostFields();
		$postFields["SessionToken"] = $sessionToken;
		$postFields["CloseSessionAfterSave"] = $closeSessionAfterSave;
		$this->setApiUrl(self::API_URL_TYPE_SAVE);

		$response = $this->postToFaber($postFields);
		/*
		print_r($postFields);
		print_r($response);
		exit;
		*/
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;
		
		return $returnData;
    }
	
	public function closeDocument($sessionToken)
    {
		$postFields = $this->getPostFields();
		$postFields["SessionToken"] = $sessionToken;
		$this->setApiUrl(self::API_URL_TYPE_CLOSE);

		print_r($postFields);
		$response = $this->postToFaber($postFields);
		
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;
		
		print_r($returnData);
    }
	
	public function getDocument($id,$key)
    {
		$postFields = $this->getPostFields();
		$postFields["ID"] = $id;
		$postFields["Key"] = $key;
		
		$this->setApiUrl(self::API_URL_TYPE_GET);

		$response = $this->postToFaber($postFields);
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;
		//print_r($returnData);exit;
		return $returnData;
    }
	
}