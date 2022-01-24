<?php
namespace Dolphin\Sap\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Newsletter\Model\SubscriberFactory;

class GetPurchase implements ObserverInterface
{      
   protected $orderFactory;

    public function __construct(\Magento\Quote\Model\QuoteFactory $quoteFactory,
    \Magento\Sales\Model\Order $orderFactory)
    {
        $this->orderFactory = $orderFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
		if($order->getCustomerIsGuest() == '1'){
			$guestCustomer = $order->getBillingAddress();
			
			$postDataArray['Nome'] =  $guestCustomer->getFirstname() ? $guestCustomer->getFirstname() : '';
			$postDataArray['Cognome'] =  $guestCustomer->getLastname() ?  $guestCustomer->getLastname() : '';
			$postDataArray['Telefono'] = $guestCustomer->getTelephone() ?  $guestCustomer->getTelephone() : '';
			$postDataArray['DataNascita'] = '';
			$postDataArray['Sesso'] =  '';
			$postDataArray['Email'] =  $guestCustomer->getEmail();
			$postDataArray['BrandOrigine'] =  '4';
			$postDataArray['PaeseOrigine'] =  "IT";
			$postDataArray['Newsletter'] =  "";
			$postDataArray['Lingua'] =  "IT";
			$postDataArray['Privacy'] = '173990001';
			$this->getGuestApiCall($postDataArray);

		}
		/*$writer = new \Zend\Log\Writer\Stream(BP . '/var/log/orderCRMDataFINAL.log');
		   $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);
		   $logger->info(print_r($order->getCustomerIsGuest(),true));
		   $logger->info(print_r($order->getBillingAddress()->getData(),true));
		   exit();*/
		
		$incrementId = $order->getIncrementId();
		$customer = $order->getCustomerId();
		$email = $order->getCustomerEmail();
		$billingAddress = $order->getBillingAddress();
		$createAt = $order->getCreatedAt();
		
		$firstName = $billingAddress->getFirstname();
		$lastName = $billingAddress->getLastname();
		$city = $billingAddress->getCity();
		$country_id = $billingAddress->getCountryId();
		$postcode = $billingAddress->getPostcode();
		$telephone = $billingAddress->getTelephone();
		
		$productArray = array();
		
		
		
		foreach ($order->getAllItems() as $item) {
		   $productArray['Nome'] = $firstName;
		   $productArray['Cognome'] = $lastName;
		   $productArray['Email'] = $email;
		   $productArray['Citta'] = $city;
		   $productArray['Paese'] = $country_id;
		   $productArray['Cap'] = $postcode;
		   $productArray['Telefono'] = $telephone;
		   $productArray['sku'] = $item->getSku();
		   $productArray['pap'] = $item->getBaseRowTotal();
		   $productArray['Sconto'] = 0;
		   $productArray['Totale'] = $item->getBaseRowTotal();
		   $productArray['DataAcquisto'] = $createAt;
		   $productArray['Origine'] = 4;
		   $productArray['Valuta'] = "eur";
           $productArray['Quantita'] = $item['product_options']['info_buyRequest']['qty'];
		   $productArray['NumeroOrdine'] = $order->getIncrementId();
           $this->getApiCall($productArray);
		  /* $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/orderCRMDataFINAL.log');
		   $logger = new \Zend\Log\Logger();
           $logger->addWriter($writer);
		   $logger->info(print_r($productArray,true));*/    
        }
    }
	
  public function getGuestApiCall($postDataArray){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/CustomergUeStCRM.log');
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
      $logger->info('---Checkout guest event after response CRM-------');
      $response = curl_exec($curl);    
	    $logger->info(print_r($postDataArray,true));
      $logger->info(print_r($response,true));     
  }
   
   
   public function getApiCall($productArray){
        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/orderCRM.log');
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://damiani-api-dev.p365.it/api/Ecommerce/CreatePurchase",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        //CURLOPT_HEADER => 1,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($productArray),
        CURLOPT_HTTPHEADER => array(
            "authorization: Basic JERNTi91c2VyYWNjZXNzOi1AP3UleGd6NlJ5cURBeWRnZVc1Y1pSIV5IVEdBR044blNoM3JQQzRjV3ByVXJtOGtWR3FLLVhWLUVRcw==",
            "cache-control: no-cache",
            "content-type: application/json"
          ),
        ));
      $logger->info('---ORDER event after response CRM-------');
      $response = curl_exec($curl);    
      $logger->info(print_r($productArray,true));  
$logger->info(print_r($response,true));  	  
  }
}