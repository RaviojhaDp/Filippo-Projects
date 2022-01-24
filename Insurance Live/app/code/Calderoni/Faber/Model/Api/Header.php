<?php
namespace Calderoni\Faber\Model\Api;

class Header extends \Calderoni\Faber\Model\Api
{
	const ENTITY_NAME = "Header";
	//const API_PARAM_DATA_OBJECT = "WAM_V_TMP_ORDINI_HEADER";
	const API_PARAM_DATA_OBJECT = "WAM_V_CERT_SOST_ORD";

	const API_URL_TYPE_HEADER_ADD = "wamappservice/api/managedata";
	const API_URL_TYPE_HEADER_SEARCH = "wamappservice/api/data";
	const API_URL_TYPE_HEADER_EDIT = "wamappservice/api/managedata";
	const API_URL_TYPE_HEADER_DELETE = "wamappservice/api/managedata";

	const OPERATION_ADD = "ADD";
	const OPERATION_EDIT = "EDIT";
	const OPERATION_DELETE = "DEL";

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
	
	public function prepareFields($fields=array(),$id=NULL){
		$f = array();

		if(!is_null($id)) $f[] = array("Key"=>"ID", "Value"=>(int)$id);
		foreach($fields as $k=>$v){
			$f[] = array("Key"=>$k, "Value"=>$v);
		}
		return $f;
	}

	public function addHeader($fields=array())
    {
		$postFields = $this->getPostFields();
		$postFields["Op"] = self::OPERATION_ADD;
		$postFields["Fields"] = $this->prepareFields($fields);
		$this->setApiUrl(self::API_URL_TYPE_HEADER_ADD);

		$response = $this->postToFaber($postFields);
		
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;

		
	//{"error":false,"messagetitle":"INFORMATION","messagebody":"FORMCOMMAND_SUCCESSFULL","url":"","clientcommand":"","attach":null,"idRecord":"212","filename":null,"affectedRecord":0,"selectedRecord":0}
		return $returnData;
		//print_r($returnData);
    }
	
	public function editHeader($id,$fields=array())
    {
		$postFields = $this->getPostFields();
		$postFields["Op"] = self::OPERATION_EDIT;
		$postFields["Fields"] = $this->prepareFields($fields, $id);
		$this->setApiUrl(self::API_URL_TYPE_HEADER_EDIT);
		
		//echo "<pre>";print_r($postFields);//exit;
		
		$response = $this->postToFaber($postFields);
		
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;
		
		return $returnData;
		//print_r($returnData);
    }
	
	public function deleteHeader($id)
    {
		$postFields = $this->getPostFields();
		$postFields["Op"] = self::OPERATION_DELETE;
		$postFields["Fields"] = $this->prepareFields(array(), $id);
		$this->setApiUrl(self::API_URL_TYPE_HEADER_DELETE);

		print_r($postFields);
		$response = $this->postToFaber($postFields);
		
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;
		
		print_r($returnData);
    }
	
	public function prepareFilters($filters=array()){
		$f = array();

		foreach($filters as $filter){
			$f[] = array("Field"=>$filter[0], "Operator"=>$filter[1], "FilterValue"=>$filter[2]);
		}
		return $f;
	}

	public function searchHeader($filters,$pageNo=1,$pageSize=1)
    {
		$postFields = $this->getPostFields();
		$postFields["Page"] = $pageNo;
		$postFields["Rows"] = $pageSize;
		
		$postFields["Filters"] = $this->prepareFilters($filters);
		$this->setApiUrl(self::API_URL_TYPE_HEADER_SEARCH);
		
		$response = $this->postToFaber($postFields);
		//print_r($response);exit;
		//re-generate TOKEN
		if($this->isRegenerateToken($response)){
			$this->generateAccessToken();
			$this->updateTokenParam($postFields);
			$response = $this->postToFaber($postFields);
		}
		
		$returnData["status"] = true;
		$returnData["data"] = $response;

		return $returnData;
		print_r($returnData);
    }
	
}