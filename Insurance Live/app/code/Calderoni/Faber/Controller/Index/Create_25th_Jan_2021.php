<?php
namespace Calderoni\Faber\Controller\Index;

class Create extends \Magento\Framework\App\Action\Action
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
		\Magento\Framework\ObjectManagerInterface $objectmanager,
		\Calderoni\Faber\Model\Api\Authenticate $faberAuthenticate,
		\Calderoni\Faber\Model\Api\Header $faberHeader,
		\Calderoni\Faber\Model\Api\Row $faberRow,
		\Calderoni\Faber\Model\Api\Document $faberDocument,
		\Calderoni\Faber\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->_objectManager = $objectmanager;
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
		$equpiment=$this->getRequest()->getPost("equpiment");
		if(strtolower($post['brand']) == "bliss"){
			$equpiment='-';
		}
		else
		{
			if($equpiment == ''){
				$equpiment='0000';
			}
		}
		if(strtolower($post['brand']) == "calderoni"){
			$serial_number=$post['stone_code'];
		}else{
		$serial_number=$this->getRequest()->getPost("model");
		if($serial_number == ''){
			$serial_number='0000';
		 }
		}
		
		if($this->getRequest()->getPost("region") == ''){
			$region1 = $this->getRequest()->getPost("state");	
		}else{
			$region1 = $this->getRequest()->getPost("region");

		}
		if($region1 != '' && is_numeric($region1)){
			$region_name = $this->_objectManager->get('Magento\Directory\Model\RegionFactory')->create()->load($region1);

			$state = ($region_name->getData('name') == '') ? $region_name->getData('default_name') : $region_name->getData('name');
		}else{
			$state = $region1;
		}

		if($this->getRequest()->getPost("fiscal_code") == ''){
			$fiscal_code_val = '0000';
		}else{
			$fiscal_code_val = $this->getRequest()->getPost("fiscal_code");
		}
		if($post['country_id'] == ''){
			$nazra = 'IT';
		}else{
			$nazra = $post['country_id'];
		}
		$tempID = '5';
		if($post['storename'] == "italiano" && strtolower($post['brand']) == "damiani"){
			$tempID = '5';
		}
		if($post['storename'] == "italiano" && strtolower($post['brand']) == "salvini"){
			$tempID = '7';
		}
		if($post['storename'] == "italiano" && strtolower($post['brand']) == "bliss"){
			$tempID = '9';
		}
		if($post['storename'] == "italiano" && strtolower($post['brand']) == "rocca"){
			$tempID = '11';
		}
		if($post['storename'] == "italiano" && strtolower($post['brand']) == "calderoni"){
			$tempID = '13';
		}
		if($post['storename'] == "english" && strtolower($post['brand']) == "damiani"){
			$tempID = '6';
		}
		if($post['storename'] == "english" && strtolower($post['brand']) == "salvini"){
			$tempID = '8';
		}
		if($post['storename'] == "english" && strtolower($post['brand']) == "bliss"){
			$tempID = '10';
		}
		if($post['storename'] == "english" && strtolower($post['brand']) == "rocca"){
			$tempID = '12';
		}
		if($post['storename'] == "english" && strtolower($post['brand']) == "calderoni"){
			$tempID = '14';
		}
		$cityinput = $this->getRequest()->getPost("cityinput");
		if(isset($cityinput) && $cityinput != ''){
			$city = $this->getRequest()->getPost("cityinput");
		}else{
			$city = $this->getRequest()->getPost("city");
		}
		$date = explode(' ', $this->getRequest()->getPost("created_at"));
		$headerFields = array(
			"namea" => $this->getRequest()->getPost("name"),
			"surna" => $this->getRequest()->getPost("surname"),
			"dobia" => $this->getRequest()->getPost("dob"),
			"addra" => $this->getRequest()->getPost("address"),
			"capra" => $this->getRequest()->getPost("zipcode"),
			"citra" => ucwords(strtolower($city)),
			"prora" => $state,
			"nazra" => $nazra,
			"telra" => $this->getRequest()->getPost("mobile_phone"),
			"emara" => $this->getRequest()->getPost("email"),
			"cfisa" => $fiscal_code_val,
			"equipm" => $equpiment,
			"codmat" => $serial_number,
			"libero1" => "Test",
			//"sernum" => $this->getRequest()->getPost("serial_number"),
			//"datatt" => $this->getRequest()->getPost("created_at"),
			"datatt" => $date[0],
			"numcer" => $this->getRequest()->getPost("certificato_code"),
			"Template_lkp" =>  $tempID // You can use template 4 that it’s the only that works for now
						); 
		
		//for testing
		/*$headerFields = array(
				'namea' => 'test',
				'surna' => 'tetst',
				'dobia' => '20/07/2001',
				'addra' => 'tetst',
				'capra' => 'tetst',
				'citra' => 'trest',
				'prora' => 'trest',
				'nazra' => 'IT',
				'telra' => 'tetete',
				'emara' => 'test30@test.com',
				'cfisa' => '1323123',
				'equipm' => '123123123',
				'codmat' => 'eqwe',
				'datatt' => '2019-07-29',
				'numcer' => 'D144',
				'Template_lkp' => '4'
			);*/

		//echo "<pre>"; print_r($headerFields);die;

			$this->faberAuthenticate->generateAccessToken();
			$header = $this->faberHeader->addHeader( $headerFields );
			$headerIdRecord = $header["data"]->idRecord;
			$this->faberDocument->composeDocument($headerIdRecord);
			$docData = $this->faberDocument->createDocument([$headerIdRecord]);

			if(strtoupper($docData["data"]->code) == strtoupper(\Calderoni\Faber\Model\Api::API_ERR_CODE)){

				$response = array("status" => false, "msg" => __("Error generating document. Please try again"));
			}else{
			//print_r($docData["data"]);
				$response = array(
					"status" => true,
					"headerId" => $headerIdRecord,
					"sessiontoken" => $docData["data"]->sessiontoken,
					"web2signurl" => $docData["data"]->web2signurl
				);
			}
			$result = $this->resultJsonFactory->create();
			return $result->setData($response);

		}
		
	}