<?php
namespace Dolphin\Sap\Observer;

use Magento\Framework\Event\ObserverInterface;

class GetCustomerDetails implements ObserverInterface
{      

  
	/**
	 * @var \Magento\Framework\App\RequestInterface
	 */
	public $request;
	
	protected $_customerRepositoryInterface;

  public function __construct(
    \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
	\Magento\Framework\App\RequestInterface $request

	) {
	    $this->_customerRepositoryInterface = $customerRepositoryInterface;
		$this->request = $request;
	}

 public function execute(\Magento\Framework\Event\Observer $observer)
 {
    $customer = $observer->getEvent()->getCustomer();

    $is_transfer = '173990000';
    $is_profiling = '173990000';
	$is_privacy = '173990000';
	$is_marketing = '173990000';
	
	if(isset($_REQUEST['wedding_date'])){
      $wedding_date = $_REQUEST['wedding_date'];
       }
	if(isset($_REQUEST['is_privacy'])){
      if($_REQUEST['is_privacy'] == '1'){
        $is_privacy = '173990001';
        }
       }
	if(isset($_REQUEST['is_marketing'])){
      if($_REQUEST['is_marketing'] == '1'){
        $is_marketing = '173990001';
        }
       }
    if(isset($_REQUEST['is_profiling'])){
      if($_REQUEST['is_profiling'] == '1'){
        $is_profiling = '173990001';
        }
       }
    if(isset($_REQUEST['is_transfer'])){
        if($_REQUEST['is_transfer'] == '1'){
        $is_transfer = '173990001';
        }
       }
    if($customer->getGender() == '1'){
        $gender = "279640001";
       }
    elseif ($customer->getGender() == '2') {
        $gender = "279640000"; 
    }
    else{
        $gender = "173990000"; 
    }
    
    $postDataArray['Nome'] =  $customer->getFirstname() ? $customer->getFirstname() : '';
    $postDataArray['Cognome'] =  $customer->getLastname() ?  $customer->getLastname() : '';
    $postDataArray['Telefono'] = "";
    $postDataArray['DataNascita'] =  $customer->getDob() ?  $customer->getDob() : '';
    $postDataArray['Sesso'] =  $gender;
    $postDataArray['Email'] =  $customer->getEmail();
    $postDataArray['BrandOrigine'] =  '4';
    $postDataArray['PaeseOrigine'] =  "IT";
    $postDataArray['Newsletter'] =  "";
    $postDataArray['Lingua'] =  "IT";
	  $postDataArray['DataMatrimonio'] = date("Y-m-d", strtotime($wedding_date));
	  $postDataArray['Privacy'] = $is_privacy;
	  $postDataArray['Marketing'] = $is_marketing;
    $postDataArray['Profilazione'] = $is_profiling;
    $postDataArray['Cessione'] = $is_transfer;
	//echo "<pre>";
	//print_r($postDataArray);die;
    $this->getApiCall($postDataArray);
  }

  public function getApiCall($postDataArray){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/CustomerCRM.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://damiani-api-dev.p365.it/api/Ecommerce/UpsertCustomer",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        //CURLOPT_HEADER => 1,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($postDataArray),
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic JERNTi91c2VyYWNjZXNzOi1AP3UleGd6NlJ5cURBeWRnZVc1Y1pSIV5IVEdBR044blNoM3JQQzRjV3ByVXJtOGtWR3FLLVhWLUVRcw==",
            "cache-control: no-cache",
            "content-type: application/json"
          ),
        ));
      $logger->info('---Customer Register event after response CRM-------');
      $response = curl_exec($curl);    
	        $logger->info(print_r($postDataArray,true));
      $logger->info(print_r($response,true));     
  }
}