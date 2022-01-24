<?php
namespace Calderoni\Faber\Model;

class Api extends \Magento\Framework\Model\AbstractModel
{
    const API_HOST = "https://wam.fabersystem.it/";
	const API_URL_TYPE_AUTHENTICATE = "wamappservice/api/authenticate";
	const XML_CONFIG_USERNAME = "faber/settings/username";
	const XML_CONFIG_PASSWORD = "faber/settings/password";
    const XML_CONFIG_SERVICE_NAME = "faber/settings/service_name";
    const XML_CONFIG_OAUTH_TOKEN = "faber/settings/oauth_token";
	const API_ERR_CODE = "KO";
	const API_ERR_CODE_INVALID_TOKEN = "KO= token invalido";
	const API_ERR_MSG_INVALID_TOKEN = "INVALID TOKEN";

    protected $objectManager;
    protected $configFactory;
    protected $faberHelper;

	public $curl_url;
	public $curl_custom_request;//POST=Insert, PUT=Update

	public function __construct(
        \Magento\Framework\Model\Context $context,
		\Magento\Framework\Registry $registry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Config\Model\Config\Factory $configFactory,
        \Calderoni\Faber\Helper\Data $faberHelper
    ){
        $this->objectManager = $objectManager;
        $this->configFactory = $configFactory;
        $this->faberHelper = $faberHelper;
		parent::__construct($context,$registry);
    }

	public function getApiUrl()
    {
		return $this->curl_url;
    }
	public function setApiUrl($apiType=NULL)
    {
		$this->curl_url = self::API_HOST.(!is_null($apiType)?$apiType:"");
    }
	public function getApiUrlAuthenticate()
    {
		return self::API_HOST.self::API_URL_TYPE_AUTHENTICATE;
    }

	public function getUsername(){
		return $this->faberHelper->getConfig(self::XML_CONFIG_USERNAME);
	}
	public function getPassword(){
		return $this->faberHelper->getConfig(self::XML_CONFIG_PASSWORD);
	}
	public function getServiceName(){
		return $this->faberHelper->getConfig(self::XML_CONFIG_SERVICE_NAME);
	}
	public function getOauthToken()
    {
        return $this->faberHelper->getConfig(self::XML_CONFIG_OAUTH_TOKEN);
    }
	
	public function generateAccessToken()
    {
		$postfields = array(
		  'Username' => $this->getUsername(),
		  'Password' => $this->getPassword(),
		  'ServiceName' => $this->getServiceName()
		);

		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL, $this->getApiUrlAuthenticate());
		curl_setopt($curl,CURLOPT_POST, sizeof($postfields));
		curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($postfields));
		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		curl_close($curl);

		if ($err) {
			echo 'cURL Error. Please try again.';
			return false;
		} else {
			$newToken = json_decode( $response );
			$this->saveFaberConfigSettings(array("config"=>"oauth_token", "value"=>$newToken->Token));
			return true;
		}
    }
	
	public function saveFaberConfigSettings($config = array())
    {
		$configData = [
			'section' => "faber",
			'groups' => 
				array('settings'=>
					array('fields' => 
						array($config["config"] => 
							array('value' => $config["value"])
						)
					)
				)
		];
		$this->configFactory->create(['data' => $configData])->save();
    }
	
	public function postToFaber($postfields=array())
    {
		//Increasing Script execution time to infi.
		set_time_limit(0);
		
		$curl = curl_init();
		//echo "ApiUrl: ".$this->getApiUrl();
		curl_setopt($curl,CURLOPT_URL, $this->getApiUrl());
		curl_setopt($curl,CURLOPT_POST, sizeof($postfields));
		curl_setopt($curl,CURLOPT_POSTFIELDS, http_build_query($postfields));
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); //timeout to infi.
		curl_setopt($curl, CURLOPT_TIMEOUT, 30); //timeout in seconds. 30 seconds

		curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		
		
		/*echo "RESP: ";
		print_r($response);
		echo "ERR: ";
		print_r($err);
		die;*/
		curl_close($curl);
		/*
		if ($err) {
			echo 'cURL Error. Please try again.';
			return false;
		} else {
			$newToken = json_decode( $response );
			$this->saveFaberConfigSettings(array("config"=>"oauth_token", "value"=>$newToken->Token));
			return true;
		}
		*/
		
		$api_response_obj = json_decode(utf8_encode($response));
		//$api_response_obj = $this->faberHelper->jsonDecode(utf8_encode($response));
		//$api_response_obj = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response));
		return $api_response_obj;
    }
	
	public function isRegenerateToken($response)
    {
		try{
			//re-generate TOKEN

			//if Response is array
			if(is_array( $response ) && array_key_exists("ErrorCode",$response) && strtoupper($response["ErrorCode"]) == strtoupper(self::API_ERR_CODE)){
				if(array_key_exists("ErrorMessage",$response) && strtoupper($response["ErrorMessage"]) == strtoupper(self::API_ERR_MSG_INVALID_TOKEN))
					return true;
			}
			//for API_ERR_CODE_INVALID_TOKEN
			if(is_array( $response ) && array_key_exists("ErrorCode",$response) && strtoupper($response["ErrorCode"]) == strtoupper(self::API_ERR_CODE_INVALID_TOKEN)){
				return true;
			}
			
			//if Response is Object
			if(is_object( $response ) && property_exists($response,"ErrorCode") && strtoupper($response->ErrorCode) == strtoupper(self::API_ERR_CODE)){
				if(property_exists($response,"ErrorMessage") && strtoupper($response->ErrorMessage) == strtoupper(self::API_ERR_MSG_INVALID_TOKEN))
					return true;
			}
			//for API_ERR_CODE_INVALID_TOKEN
			if(is_object( $response ) && property_exists($response,"ErrorCode") && strtoupper($response->ErrorCode) == strtoupper(self::API_ERR_CODE_INVALID_TOKEN)){
				return true;
			}
			return false;
		}catch(Exception $ex){
			return true;
		}
    }
	
	public function updateTokenParam(&$params){
		$params["TokenValue"] = $this->getOauthToken();
	}
}