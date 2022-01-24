<?php
namespace Calderoni\Faber\Controller\Index;

class Createclaim extends \Magento\Framework\App\Action\Action
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
		\Calderoni\Faber\Model\Api\Claim\Authenticate $faberAuthenticate,
		\Calderoni\Faber\Model\Api\Claim\Header $faberHeader,
		\Calderoni\Faber\Model\Api\Claim\Row $faberRow,
		\Magento\Directory\Model\CountryFactory $countryFactory,
		\Calderoni\Faber\Model\Api\Claim\Document $faberDocument,
		\Calderoni\Faber\Helper\Data $faberHelper
	) {
		parent::__construct($context);
		$this->_objectManager = $objectmanager;
		$this->_countryFactory = $countryFactory;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->faberAuthenticate = $faberAuthenticate;
		$this->faberHeader = $faberHeader;
		$this->faberRow = $faberRow;
		$this->faberDocument = $faberDocument;
		$this->faberHelper = $faberHelper;
	}

	public function execute()
	{

		$objDate = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\TimezoneInterface');
		$date = $objDate->date()->format('Y-m-d H:i:s');
		$his = explode(' ',$date);
		$main = $this->getRequest()->getPost();
		if(strtolower($main['storename']) == "italiano"){
			$tempID = '15';
		}else{
			$tempID = '16';
		}
		if($main['left_region'] != ''){
			$left_region = $main['left_region'];
		}else{
			$left_region = $main['region'];		
		}
		if($main['left-city'] != ''){
			$left_city = $main['left-city'];
		}else{
			$left_city = $main['city'];		
		}
		/*if($left_region != '' && is_numeric($left_region)){
			$region_name = $this->_objectManager->get('Magento\Directory\Model\RegionFactory')->create()->load($left_region);
			$left_state = ($region_name->getData('name') == '') ? $region_name->getData('default_name') : $region_name->getData('name');
		}else{
			$left_state = $left_region;
		}
*/

		$deseve = ($main['describe_events'])? $main['describe_events'] : "-";
		$libero2 = ($main['date_of_termination'])? $main['date_of_termination'] : "-";
		$datden = ($main['date_of_termination'])? $main['date_of_termination'] : "-";
		$datasin = ($main['left_date'])? $main['left_date'] : "-";
		$indsin = ($main['left_address'])? $main['left_address'] : "-";
		$capsin = ($main['left_zipcode'])? $main['left_zipcode'] : "-";
		$citsin = ucwords(strtolower($left_city));
		$prosin = '-';
		$nazsin = $country = $this->_countryFactory->create()->loadByCode($main['left_country'])->getName();
		$autori = ($main['authority'])? $main['authority'] : "-";
		$libero3 = '-';


		$val = $this->getRequest()->getPost('faber_post');
		$post =	json_decode($val, true);

		$equpiment = ($post['equpiment'] == "0000")?$post['model']:$post['equpiment'];

		if($equpiment == '')
			$equpiment='0000';
		if($post['region'] == ''){
			$state = "Roma";	
		}else{
			$state = $post['region'];	
		}
		if($post['fiscal_code'] == ''){
			$fiscal_code_val = '0000';
		}else{
			$fiscal_code_val = $post['fiscal_code'];
		}
		if($post['country_id'] == ''){
			$nazra = 'IT';
		}else{
			$nazra = $post['country_id'];
		}
		if($post['phone'] == ''){
			$phone = $post['mobile_phone'];
		}else{
			$phone = $post['phone'];
		}
		$date = explode(' ',$post['created_at']);


		$serial_number = $main['model'];
		if(isset($main['model']) && $main['model'] == ''){

                    $serial_number='0000';//"codmat"
                }
                $headerFields = array(
                	"namea" => $post['name'],
                	"surna" => $post['surname'],
                	"dobia" => $post['dob'],
                	"addra" => $post['address'],
                	"capra" => $post['zipcode'],
                	"citra" => ucwords(strtolower($post['city'])),
                	"prora" => $state,
                	"nazra" => $nazra,
                	"telra" => $phone,
                	"emara" => @$post['email'],
                	"cfisa" => @$fiscal_code_val,
                	"equipm" => $equpiment,
                	"codmat" => $equpiment,
                	"datatt" => @$date[0],
                	"numcer" => @$post['certificato_code'],
                	"datasin" => $datasin,
                	"libero1" => "Test",
                	"indsin" => $indsin,
                	"capsin" => $capsin,
                	"citsin" => $citsin,
                	"prosin" => $prosin,
                	"nazsin" => $nazsin,
                	"deseve" => $deseve,
                	"autori" => $autori,
                	"datden" => $datden,
                	"libero2" => $libero2,
                	"libero3" =>$libero3,
					"libero4" => $his[1],
				    "Template_lkp" =>  $tempID // You can use template 3 that it’s the only that works for now
							
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


		//echo "<pre>"; print_r($headerFields); exit;

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